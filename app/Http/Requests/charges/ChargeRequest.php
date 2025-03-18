<?php

namespace App\Http\Requests\charges;

use App\Http\Requests\BaseRequest;

class ChargeRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', 'min:13', 'max:13', 'exists:codes,code'],
        ];
    }
}
