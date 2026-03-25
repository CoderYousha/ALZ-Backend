<?php

namespace App\Http\Requests\Api\Product;

use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            // 'store_id'          => 'nullable|exists:stores,id',
            'category_id'      => 'nullable|exists:categories,id',
            'name_en'           => 'nullable|string',
            'name_ar'           => 'nullable|string',
            'price'             => 'nullable|numeric',
            'description_en'    => 'nullable|string',
            'description_ar'    => 'nullable|string',
            'images'            => 'nullable|array',
            'images.*'          => 'required_with:images|image',
            'old_images_ids'    => 'nullable|array',
            'old_images_ids.*'  => 'required_with:old_images_ids|numeric',
        ];
    }
}