<?php

namespace App\Http\Requests\admins;

use App\Http\Requests\BaseRequest;

class StoreAdminRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:35'],
            'email'     => ['required', 'string', 'email', 'max:50' ,'unique:users'],
            'password'  => ['required', 'string', 'min:6', 'confirmed'],
            'gender'    => ['required', 'in:male,female'],
            'is_active' => ['required', 'in:1,0']
        ];
    }
}
