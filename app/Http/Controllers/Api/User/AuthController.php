<?php

namespace App\Http\Controllers\Api\User;

use App\Events\UserLoggedIn;
use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Controller;
use App\Http\DTO\User\AuthData;
use App\Http\DTO\User\UserData;
use App\Http\Requests\Api\User\UpdatePasswordRequest;
use App\Http\Requests\Api\BaseRequests\Auth\ForgetPassword\BaseCheckForgetPasswordRequest;
use App\Http\Requests\Api\BaseRequests\Auth\ForgetPassword\BaseForgetPasswordRequest;
use App\Http\Requests\Api\User\LoginRequest;
use App\Http\Requests\Api\User\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\ApiResponse\ApiResponseClass;
use App\Http\Services\Auth\AuthServices;
use App\Http\Services\Auth\ConfirmationAccountServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller{

    public function __construct(
        protected AuthServices $authServices
    ){}

    public function login(LoginRequest $request)
    {
        $authData = AuthData::fromRequest($request);

        $user = $this->authServices->login($authData);

        if(is_null($user->verified_at)){
            ConfirmationAccountServices::sendConfirmationCode($user);
        }

        event(new UserLoggedIn($user, $request->fcm_token));


        return ApiResponseClass::successResponse([
            'user' => new UserResource($user),
            'token' => $this->authServices->generateAccessToken($user),
        ]);
    }


    public function logout(Request $request)
    {
        $user = $request->user();
        $this->authServices->logout($user, $request->fcm_token);

        return ApiResponseClass::successMsgResponse();
    }

    public function register(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = UserData::fromRequest($request, $request->account_role);
            $user = $this->authServices->createUser($data);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new ErrorMsgException($exception->getMessage());
        }

        ConfirmationAccountServices::sendConfirmationCode($user);
        
        return ApiResponseClass::successResponse([
            'user' => new UserResource($user),
            'token' => $this->authServices->generateAccessToken($user),
        ]);
    }

    public function confirmAccount(Request $request){
        $request->validate([
            'code' => 'required',
        ]);

        $user = $request->user();
        if(ConfirmationAccountServices::checkValidCode($user, $request->code)){
            $user = ConfirmationAccountServices::confirmAccount($user);
        }
        return ApiResponseClass::successMsgResponse();

    }

    public function resendConfirmationAccountCode(Request $request){
        $user = $request->user();
        ConfirmationAccountServices::sendConfirmationCode($user);
        return ApiResponseClass::successMsgResponse();
    }


    public function forgetPassword(BaseForgetPasswordRequest $request){
        $authData = AuthData::fromRequest($request);
        $this->authServices->forgetPassword($authData);

        return ApiResponseClass::successMsgResponse();

    }

    public function checkForgetPasswordCode(BaseCheckForgetPasswordRequest $request){
        try {
            DB::beginTransaction();
            $authData = AuthData::fromRequest($request);
            $user = $this->authServices->checkForgetPasswordCode($authData, $request->code);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new ErrorMsgException($exception->getMessage());
        }

        $user->password = $request->password;
        $user->save();

        return ApiResponseClass::successMsgResponse();

    }

    /**
     * when the user update password he needs to insert his old pass too
     */
    public function updatePassword(UpdatePasswordRequest $request){
        $user = $request->user();
        $authData = AuthData::forChangePassword($request, $user);
        $this->authServices->updatePassword($user, $authData, $request->new_password);
        return ApiResponseClass::successResponse(new UserResource($user));
    }

}
