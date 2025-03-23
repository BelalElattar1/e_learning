<?php

namespace App\Http\Requests\sections;

use App\Http\Requests\BaseRequest;

class UpdateSectionRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'is_active'   => ['required', 'in:1,0'],
            'name'        => ['required', 'string', 'max:255'],
            'type'        => ['required', 'in:video,pdf,exam'],
            'exam_mark'   => ['required_if:type,exam', 'nullable', 'integer'],
            'time'        => ['required_if:type,exam', 'nullable', 'integer'],
            'link'        => ['required_if:type,pdf,video', 'nullable', 'url']
        ];
    }
}
