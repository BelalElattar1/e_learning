<?php

namespace App\Services\answers;

use Exception;
use App\Models\Choose;
use App\Models\Answer;
use App\Models\Section;
use App\Models\StudentExam;
use Illuminate\Support\Facades\DB;

class AnswerService
{

    public function answer($request) {

        $teacher_id = $this->check_exam_exists($request['exam_id']);
        DB::transaction(function () use ($request, $teacher_id) {  
            
            $student_exam = $this->create_student_exam($request['exam_id'], $teacher_id);
            $this->answer_registration($request['chooses'], $student_exam);
            $this->degree_calculation($student_exam);   

        });

    }

    private function check_exam_exists($exam_id) {

        $teacher_id = Section::where('id', $exam_id)
                            ->where('type', 'exam')
                            ->pluck('teacher_id')
                            ->first();

        throw_unless($teacher_id, new Exception('This is not an exam'));

        return $teacher_id;

    }

    private function create_student_exam($exam_id, $teacher_id) {

        return StudentExam::create([
            'student_id' => auth()->user()->student->id,
            'teacher_id' => $teacher_id,
            'exam_id'    => $exam_id
        ]);

    }

    private function answer_registration($chooses, $student_exam) {

        $chooses = Choose::whereIn('id', array_unique($chooses))->get();
    
        $answers = [];
    
        foreach ($chooses as $choose) {
            $answers[] = [
                'status'          => $choose->status,
                'choose_id'       => $choose->id,
                'student_exam_id' => $student_exam->id,
                'student_id'      => $student_exam->student_id,
                'created_at'      => now(),
                'updated_at'      => now()
            ];
        }

        Answer::insert($answers);
    }
    

    private function degree_calculation($student_exam) {

        $degree = Answer::where('student_id', $student_exam->student_id)
                        ->where('student_exam_id', $student_exam->id)
                        ->where('status', 1)->count();

        $student_exam->update([
            'degree' => $degree
        ]);

    }

}
