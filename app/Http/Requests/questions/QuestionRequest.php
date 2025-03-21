<?php

namespace App\Http\Requests\questions;

use Illuminate\Validation\Rule;
use App\Http\Requests\BaseRequest;

class QuestionRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'question_name' => 'required|string|max:255',
            'choose_1'      => 'required|string|max:255',
            'choose_2'      => 'required|string|max:255',
            'choose_3'      => 'required|string|max:255',
            'choose_4'      => 'required|string|max:255',
            'status'        => 'required|integer|in:1,2,3,4',
            'exam_id'       => ['required', 'integer', Rule::exists('sections', 'id')->where('type', 'exam')]
        ];
    }
}
