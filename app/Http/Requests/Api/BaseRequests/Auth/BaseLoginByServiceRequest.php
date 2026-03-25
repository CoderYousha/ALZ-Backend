<?php

namespace App\Http\Requests\Api\BaseRequests\Auth;

use App\Exceptions\ErrorMsgException;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BaseLoginByServiceRequest extends FormRequest
{


    use ResponseValidationFormRequest;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if(!in_array($this->service,\App\Enums\LoginTypeEnum::values())){
            throw new ErrorMsgException('invalid service name use : google,facebook');
        }

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
            'service_token' => 'required',
            'fcm_token' => 'required',
        ];
    }
}
