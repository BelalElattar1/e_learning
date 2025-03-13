<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subscribe;
use App\ResponseTrait;

class ImageController extends Controller
{
    use ResponseTrait;

    public function get_private_image($folder, $filename) {

        $user = auth()->user();

        $student = $user->type == 'student'  
                    ? Student::where('user_id', $user->id)
                    ->where('card_photo', $filename)->exists()
                    : false;

        $teacher = $user->type == 'teacher'  
                    ? Subscribe::where('teacher_id', $user->teacher->id)
                    ->where('pay_photo', $filename)->exists() 
                    : false;
        
        $owner_or_admin = in_array($user->type, ['admin', 'owner']);

        if($student || $teacher || $owner_or_admin) {

            $path = storage_path('app/private/' . $folder . '/' . $filename);
            if (!file_exists($path)) {
                return $this->response('The Image Not Found', 404);
            }
        
            return response()->file($path);

        } else {

            return $this->response('Access deniedddddddddddd', 403);

        }
    }
    
}
