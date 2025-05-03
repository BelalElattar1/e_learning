<?php

namespace App\Http\Requests\views;

use Illuminate\Validation\Rule;
use App\Http\Requests\BaseRequest;

class ViewRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'lecture_id' => ['required', 'integer', Rule::exists('sections', 'id')->where('type', 'video')],
        ];
    }
}
