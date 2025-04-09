<?php

namespace App\Http\Requests\materials;

use App\Http\Requests\BaseRequest;

class MaterialRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'             => ['required', 'string', 'max:20'],
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id']
        ];
    }
}
