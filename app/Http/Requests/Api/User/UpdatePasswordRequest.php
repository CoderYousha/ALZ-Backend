<?php

namespace App\Http\Requests\Api\User;

use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
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
          'old_password' => 'required',
          'new_password' => 'required|min:6|confirmed',
          'new_password_confirmation' => 'required_with:new_password',
        ];
    }


}
