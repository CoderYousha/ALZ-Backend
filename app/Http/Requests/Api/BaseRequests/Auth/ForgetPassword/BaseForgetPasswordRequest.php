<?php

namespace App\Http\Requests\Api\BaseRequests\Auth\ForgetPassword;

use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\RoleEnum;

class BaseForgetPasswordRequest extends FormRequest
{
    /**
     * @uses ResponseValidationFormRequest it is responsible to return validation
     * messages error as json
     */
    use ResponseValidationFormRequest;



    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'account_role' => ['required',Rule::in([RoleEnum::values()])],
        ];
        
        $rules['phone_code'] = 'required_without:email';
        $rules['phone'] = 'required_with:phone_code';
        $rules['email'] = 'required_without:phone_code|email';

        return $rules;

    }

}
