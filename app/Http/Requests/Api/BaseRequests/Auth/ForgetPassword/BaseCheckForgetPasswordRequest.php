<?php

namespace App\Http\Requests\Api\BaseRequests\Auth\ForgetPassword;

use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class BaseCheckForgetPasswordRequest extends BaseForgetPasswordRequest
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
        $rules = Parent::rules();
        $rules['code'] = 'required';
        $rules['password'] = 'required|confirmed|min:6';
        return $rules;
    }

}
