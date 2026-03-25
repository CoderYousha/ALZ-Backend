<?php

namespace App\Http\Requests\Api\Users;

use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NotifyUsersRequest extends FormRequest
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
            // 'role' => ['nullable', 'string', Rule::in(\App\Enums\RoleEnum::values())],
            'title_en' => 'required|string',
            'description_en' => 'required|string',
            'title_ar' => 'required|string',
            'description_ar' => 'required|string',
        ];
    }
}
