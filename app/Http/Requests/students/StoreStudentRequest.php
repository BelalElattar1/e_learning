<?php

namespace App\Http\Requests\students;

use App\Http\Requests\BaseRequest;

class StoreStudentRequest extends BaseRequest
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
            'name'             => 'required|string|max:30',
            'email'            => 'required|string|email|max:50|unique:users',
            'password'         => 'required|string|min:6||confirmed',
            'phone_number'     => ['required', 'regex:/^(010|011|012|015)[0-9]{8}$/', 'unique:students'],
            'father_phone'     => ['required', 'regex:/^(010|011|012|015)[0-9]{8}$/'],
            'mother_phone'     => ['required', 'regex:/^(010|011|012|015)[0-9]{8}$/'],
            'school_name'      => ['required', 'string', 'min:3', 'max:30'],
            'father_job'       => ['required', 'string', 'min:3', 'max:30'],
            'card_photo'       => ['required', 'file', 'max:1048576', 'mimes:jpg,jpeg,png', 'unique:students'],
            'gender'           => ['required', 'in:male,female'],
            'mayor_id'         => ['required', 'integer', 'exists:mayors,id'],
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
        ];
    }

}
