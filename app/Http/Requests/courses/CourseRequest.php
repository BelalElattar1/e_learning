<?php

namespace App\Http\Requests\courses;

use App\Http\Requests\BaseRequest;

class CourseRequest extends BaseRequest
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
            'title'            => ['required', 'string', 'max:50'],
            'description'      => ['required', 'string'],
            'image'            => ['required', 'image', 'max:1048576', 'mimes:jpg,jpeg,png', 'unique:courses'],
            'price'            => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
        ];
    }
}
