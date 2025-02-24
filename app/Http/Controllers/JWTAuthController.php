<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\DB;

class JWTAuthController extends Controller
{
    // User registration
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users',
            'password'     => 'required|string|min:6',
            'phone_number' => ['required', 'regex:/^(010|011|012|015)[0-9]{8}$/'],
            'father_phone' => ['required', 'regex:/^(010|011|012|015)[0-9]{8}$/'],
            'mother_phone' => ['required', 'regex:/^(010|011|012|015)[0-9]{8}$/'],
            'school_name'  => ['required', 'string', 'min:3', 'max:100'],
            'father_job'   => ['required', 'string', 'min:3', 'max:50'],
            'card_photo'   => ['required', 'file', 'max:1048576', 'mimes:jpg,jpeg,png'],
            'gender'       => ['required', 'in:male,female'],
            'mayor_id'     => ['required', 'integer', 'exists:mayors,id'],
            'academic_id'  => ['required', 'integer', 'exists:academic_years,id'],
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        if($request->hasfile('card_photo')) {
            $path  = $request->file('card_photo');
            $card_photo = $path->store('cards', 'public');
        }

        try {

            DB::beginTransaction();

                $user = User::create([
                    'name' => $request->get('name'),
                    'email' => $request->get('email'),
                    'password' => Hash::make($request->get('password')),
                ]);
        
                Student::Create([
                    'phone_number' => $request->phone_number,
                    'father_phone' => $request->father_phone,
                    'mother_phone' => $request->mother_phone,
                    'school_name'  => $request->school_name,
                    'father_job'   => $request->father_job,
                    'card_photo'   => $request->card_photo,
                    'gender'       => $request->gender,
                    'mayor_id'     => $request->mayor_id,
                    'academic_id'  => $request->academic_id,
                    'user_id'      => $user->id
                ]);

                $token = JWTAuth::fromUser($user);

            DB::commit();

            return response()->json([
                'Message' => 'Registerd Suc',
                'Token' => $token
            ], 201);

        } catch (Exception $e) {

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

            // (optional) Attach the role to the token.
            $token = JWTAuth::claims(['role' => $user->role])->fromUser($user);

            return response()->json(compact('token'));
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

        return response()->json(compact('user'));
    }

    // User logout
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Successfully logged out']);
    }
}
