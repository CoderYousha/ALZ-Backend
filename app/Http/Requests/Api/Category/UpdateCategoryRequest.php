<?php

namespace App\Http\Requests\Api\Category;

use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
        return  [
            'name_en'       => 'nullable|string',
            'name_ar'       => 'nullable|string',
            'description_en'   => 'nullable|string',
            'description_ar'   => 'nullable|string',
            'image'        => 'nullable|image',
        ];
    }
}