<?php

namespace App\Http\Requests\Api\User;

use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{


    use ResponseValidationFormRequest;

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
        $user_id = $this?->user?->id ?? $this->user()->id;
    
        return [
            'email' => 'nullable|unique:users,email,'.$user_id,
            'first_name' => 'nullable',
            'last_name' => 'nullable',
            'birth_date' => 'nullable|date|date_format:Y-m-d|before:today',
            'phone_code' => 'nullable',
            'phone' => 'nullable',
            'major_id' => 'nullable|exists:majors,id',
            'academic_degree_id' => 'nullable|exists:academic_degrees,id',
            'specialization_id' => 'nullable|exists:teacher_specializations,id',
            'experience_years' => 'nullable|numeric|min:0',
        ];
    }
}
