<?php

namespace App\Http\Requests\teachers;

use Illuminate\Validation\Rule;
use App\Http\Requests\BaseRequest;

class UpdateTeacherRequest extends BaseRequest
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
            'name'         => ['required', 'string', 'max:30'],
            'email'        => ['required', 'string', 'email', 'max:50', Rule::unique('users')->ignore($this->route('user'))],
            'phone_number' => ['required', 'regex:/^(010|011|012|015)[0-9]{8}$/', Rule::unique('teachers')->ignore($this->route('user')->teacher->id)],
            'password'     => ['required', 'string', 'min:6', 'confirmed'],
            'gender'       => ['required', 'in:male,female'],
            'material_id'  => ['required', 'integer', 'exists:materials,id']
        ];
    }
}
