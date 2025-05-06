<?php

namespace App\Services\degrees;

use Exception;
use App\Models\Section;
use App\Models\StudentExam;
use App\Http\Resources\AnswerResource;
use App\Http\Resources\DegreeResource;

class DegreeService
{

    public function show_all_degrees() {
        
        $user = auth()->user();
        $query = StudentExam::select('id', 'exam_id', 'degree', 'student_id', 'teacher_id')
                ->with(['exam:id,name,exam_mark']);

        match ($user->type) {

            'student' => $query->where('student_id', $user->student->id)->with(['teacher:id,user_id', 'teacher.user:id,name']),
            'teacher' => $query->where('teacher_id', $user->teacher->id)->with(['student:id,user_id', 'student.user:id,name']),
            
            default   => $query->with([
                'teacher:id,user_id', 
                'teacher.user:id,name',
                'student:id,user_id',
                'student.user:id,name'
            ]) 

        };

        $degrees = $query->get();
        abort_if($degrees->isEmpty(), 404, 'No degrees found');
        return DegreeResource::collection($degrees);

    }

    public function show_exam_answers(StudentExam $exam) {

        $user = auth()->user();
        match ($user->type) {
            'student' => abort_if($exam->student_id !== $user->student->id, 404, 'There is a glitch'),
            'teacher' => abort_if($exam->teacher_id !== $user->teacher->id, 404, 'There is a glitch'),
            default   => NULL
        };

        $answers = Section::select('id')
        ->where('id', $exam->exam_id)
        ->where('type', 'exam')
        ->with([
            'questions:id,exam_id,name',
            'questions.chooses:id,question_id,name,status',
            'questions.chooses.answers' => function ($query) use ($exam) {
                $query->where('student_exam_id', $exam->id)
                      ->select('choose_id', 'status');
            }
        ])
        ->get();
        return AnswerResource::collection($answers);

    }

}
