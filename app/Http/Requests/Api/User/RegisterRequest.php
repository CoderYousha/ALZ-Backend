<?php

namespace App\Http\Requests\Api\User;

use App\Enums\RoleEnum;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
        return [
            'email' => ['required_without:phone_code', 'email', Rule::unique('users', 'email')],
            'first_name' => 'required',
            'last_name' => 'required',
            'birth_date' => 'required|date|date_format:Y-m-d|before:today',
            'phone_code' => 'required_without:email',
            'phone' => [
                'required_with:phone_code',
                Rule::unique('users', 'phone')->where(function ($query) {
                    return $query->where('phone_code', $this->phone_code);
                })
            ],
            'account_role' => ['required', Rule::in(RoleEnum::STUDENT->value, RoleEnum::TEACHER->value)],
            'password' => 'required|min:6|confirmed',
            'fcm_token' => 'nullable',
            'major_id' => 'nullable|exists:majors,id',
            'academic_degree_id' => 'nullable|exists:academic_degrees,id',
            'specialization_id' => 'nullable|exists:teacher_specializations,id',
            'experience_years' => 'nullable|numeric|min:0',
        ];
    }

}
