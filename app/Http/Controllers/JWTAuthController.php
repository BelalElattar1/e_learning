<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\StudentResource;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\students\StoreStudentRequest;

class JWTAuthController extends Controller
{
    // User registration
    public function register(StoreStudentRequest $request)
    {
        
        try {

            DB::beginTransaction();

                $user = User::create([
                    'name'     => $request->get('name'),
                    'email'    => $request->get('email'),
                    'password' => Hash::make($request->get('password')),
                    'gender'   => $request->gender
                ]);
        
                Student::Create([
                    'phone_number' => $request->phone_number,
                    'father_phone' => $request->father_phone,
                    'mother_phone' => $request->mother_phone,
                    'school_name'  => $request->school_name,
                    'father_job'   => $request->father_job,
                    'card_photo'   => store_image($request->file('card_photo'), 'cards'),
                    'mayor_id'     => $request->mayor_id,
                    'academic_year_id'  => $request->academic_year_id,
                    'user_id'      => $user->id
                ]);

                $user->assignRole('student');

            DB::commit();

            return response()->json([
                'Message' => 'The account has been created successfully. You can contact support to activate the account'
            ], 201);

        } catch (Exception $e) {

            DB::rollBack();
            return response()->json([
                'message' => 'Sorry, an error occurred, please try again'
            ], 500);
            
        }

    }

    // User login
    public function login(Request $request)
    {

        $credentials = $request->only('email', 'password');

        try {

            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            // Get the authenticated user.
            $user = auth()->user();

            if(!$user->is_active) {
                return response()->json(['Message' => 'This account is not activated. You can contact support'], 403);
            }

            // (optional) Attach the role to the token.
            $token = JWTAuth::claims(['role' => $user->role])->fromUser($user);

            return response()->json([
                'Role'        => $user->type,
                'permissions' => $user->getAllPermissions()->pluck('name'),
                'Token' => $token
            ]);

        } catch (JWTException $e) {

            return response()->json(['error' => 'Could not create token'], 500);

        }

    }

    // Get authenticated user
    public function getUser()
    {

        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'User not found'], 404);
            }

        } catch (JWTException $e) {

            return response()->json(['error' => 'Invalid token'], 400);

        }

        $user = new StudentResource($user->load('student.academic_year', 'student.mayor'));
        return response()->json([
            'data'    => $user,
            'Message' => 'User data retrieved successfully.'
        ]);

    }

    public function get_private_image($folder, $filename) {

        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->where('card_photo', $filename)->exists();
        $owner_or_admin = in_array($user->type, ['admin', 'owner']);

        if($student || $owner_or_admin) {

            $path = storage_path('app/private/' . $folder . '/' . $filename);
            if (!file_exists($path)) {
                return response()->json(['error' => 'The Image Not Found'], 404);
            }
        
            return response()->file($path);

        } else {

            return response()->json(['error' => 'Do not play on the site so that your account is not banned'], 404);

        }

    }

    public function get_all_students_inactive() {

        $users = User::where('is_active', 0)->with('student.academic_year', 'student.mayor')->get();
        $users = StudentResource::collection($users);

        if($users) {

            return response()->json([
                'data' => $users,
                'Massege' => 'All students have been successfully recruited'
            ]);

        } else {

            return response()->json([
                'Message' => 'There are no inactive students'
            ], 404);

        }

    }

    public function student_activation($id) {

        User::findOrFail($id)->update([
            'is_active' => 1
        ]);
        
        return response()->json([
            'Message' => 'This student has been activated successfully'
        ], 200);

    }

    // User logout
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Successfully logged out']);
    }
}
