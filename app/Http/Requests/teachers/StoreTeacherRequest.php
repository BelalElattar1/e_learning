<?php

namespace App\Http\Requests\teachers;

use App\Http\Requests\BaseRequest;

class StoreTeacherRequest extends BaseRequest
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
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'email', 'unique:users'],
            'phone_number' => ['required', 'regex:/^(010|011|012|015)[0-9]{8}$/', 'unique:teachers'],
            'password'     => ['required', 'string', 'min:6', 'confirmed'],
            'gender'       => ['required', 'in:male,female'],
            'material_id'  => ['required', 'integer', 'exists:materials,id'],
            'pay_photo'    => ['required', 'file', 'max:1048576', 'mimes:jpg,jpeg,png', 'unique:subscribes']
        ];
    }
}
