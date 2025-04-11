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
        
        $degrees = StudentExam::select('id', 'exam_id', 'degree')
        ->where('student_id', auth()->user()->student->id)
        ->with(['exam:id,name,exam_mark'])->get();
        abort_if($degrees->isEmpty(), 404, 'No degrees found');
        return DegreeResource::collection($degrees);

    }

    public function show_details_degree(StudentExam $exam) {

        abort_if($exam->student_id !== auth()->user()->student->id, 404, 'There is a glitch');
        $answers = Section::select('id')
        ->where('id', $exam->exam_id)
        ->where('type', 'exam')
        ->with([
            'questions:id,exam_id,name',
            'questions.chooses:id,question_id,name,status',
            'questions.chooses.answers:choose_id,status'
        ])
        ->get();
        return AnswerResource::collection($answers);

    }

}
