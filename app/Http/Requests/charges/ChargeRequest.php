<?php

namespace App\Http\Requests\charges;

use Illuminate\Validation\Rule;
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
            'code' => ['required', 'string', 'min:13', 'max:13', Rule::exists('codes', 'code')->where('is_active', true)],
        ];
    }
}
