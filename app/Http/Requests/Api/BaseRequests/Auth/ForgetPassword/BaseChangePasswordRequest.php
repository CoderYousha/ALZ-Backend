<?php

namespace App\Http\Requests\Api\BaseRequests\Auth\ForgetPassword;

use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class BaseChangePasswordRequest extends FormRequest
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
        return [
            'password' =>'required|min:6|confirmed',
            'password_confirmation' =>'required_with:password',
        ];
    }

}
