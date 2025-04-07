<?php

namespace App\Http\Requests\answers;

use Illuminate\Validation\Rule;
use App\Http\Requests\BaseRequest;

class AnswerRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'chooses'   => ['array', 'required'],
            'chooses.*' => ['exists:chooses,id'],
            'exam_id'   => ['required', 'integer', Rule::exists('sections', 'id')->where('type', 'exam')]
        ];
    }
}
