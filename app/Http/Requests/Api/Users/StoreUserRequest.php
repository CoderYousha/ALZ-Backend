<?php

namespace App\Http\Requests\Api\Users;

use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'name' => 'required',
            'phone_code' => 'required_without:email',
            'phone' => [
                'required_with:phone_code',
                Rule::unique('users', 'phone')->where(function ($query) {
                    return $query->where('phone_code', $this->phone_code);
                })
            ],
            'password' => 'required|min:6|confirmed',
            'fcm_token' => 'nullable',

            'account_role' => ['required',Rule::in([
                \App\Enums\RoleEnum::ADMIN->value,
            ])],
        ];
    }
}
