<?php

namespace App\Http\Requests\codes;

use App\Http\Requests\BaseRequest;

class CodeRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'price' => ['required', 'integer']
        ];
    }
}
