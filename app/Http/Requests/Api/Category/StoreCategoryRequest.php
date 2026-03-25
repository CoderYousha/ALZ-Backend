<?php

namespace App\Http\Requests\Api\Category;

use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
            'name_en'       => 'required|string',
            'name_ar'       => 'required|string',
            'description_en'   => 'nullable|string',
            'description_ar'   => 'nullable|string',
            'image'        => 'required|image',
        ];
    }
}