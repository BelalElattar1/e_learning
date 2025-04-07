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
        $isUpdate = $this->isMethod('PUT');

        return [
            'name'             => ['required', 'string', 'max:20'],
            'academic_year_id' => $isUpdate
                ? ['prohibited']
                : ['required', 'integer', 'exists:academic_years,id'],
        ];
    }
}
