<?php

namespace App\Http\Requests\categories;

use App\Http\Requests\BaseRequest;

class StoreCategoryRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'      => ['required', 'string', 'max:75'],
            'title'     => ['required', 'string', 'max:50'],
            'course_id' => ['required', 'integer', 'exists:courses,id']
        ];
    }
}
