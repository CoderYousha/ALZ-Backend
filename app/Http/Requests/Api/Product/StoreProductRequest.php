<?php

namespace App\Http\Requests\Api\Product;

use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            // 'store_id'      => 'required|exists:stores,id',
            'category_id'  => 'required|exists:categories,id',
            'name_en'       => 'required|string',
            'name_ar'       => 'required|string',
            'price'         => 'required|numeric',
            'description_en'   => 'nullable|string',
            'description_ar'   => 'nullable|string',
            'images'        => 'required|array',
            'images.*'      => 'required_with:images|image',
        ];
    }
}