<?php

namespace App\Services\questions;

use Exception;
use App\Models\Choose;
use App\Models\Question;
use Illuminate\Support\Facades\DB;

class QuestionService
{

    public function store($request) {

        DB::transaction(function () use ($request) { 

            $teacher = auth()->user()->teacher->id;
            $question = $this->create_question($request, $teacher);
            $this->create_chooses($request, $question, $teacher);

        });

    }

    private function create_question($request, $teacher) {

        return Question::create([
            'name'       => $request['question_name'],
            'exam_id'    => $request['exam_id'],
            'teacher_id' => $teacher
        ]);

    }

    private function create_chooses($request, $question, $teacher) {

        for($i = 1; $i <= 4; $i++) {

            Choose::create([
                'name'        => $request["choose_$i"],
                'status'      => $request['status'] == $i ? 1 : 0,
                'question_id' => $question->id,
                'teacher_id'  => $teacher
            ]);

        }

    }

    public function destroy(Question $question) {

        $teacher = auth()->user()->teacher->id;
        if($question->teacher_id == $teacher) {

            $question->delete();

        } else {

            throw new Exception('This is not your exam');

        }
        
    }

}
