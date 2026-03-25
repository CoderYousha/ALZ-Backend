<?php

namespace App\Http\Services\Auth;


use App\Events\UserLoggedIn;
use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\DTO\User\AuthData;
use App\Http\DTO\User\UserData;
use App\Http\Services\Firebase\FirebaseServices;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthServices
{

    /**
     * @param UserData $userData
     * @return User
     */
    public function createUser($userData)
    {
        $user = User::create($userData->all());
        event(new UserLoggedIn($user, $userData->fcm_token));
        return $user;
    }

    /**
     * @param AuthData $authData
     * @throws ErrorMsgException
     * @return User
     */
    public function login(AuthData $authData): User
    {
        $credentials = $this->getCredentials($authData);

        if (!Auth::attempt($credentials)) {
            throw new ErrorMsgException(transMsg('wrong_credentials'));
        }

        $user = Auth::user();

        if ($authData->account_role && $user->account_role !== $authData->account_role) {
            throw new ErrorUnAuthorizationException();
        }

        event(new UserLoggedIn($user, $authData->fcm_token));

        return $user;
    }


    /**
     * @param User $user
     * @return void
     */
    public function deleteAccount(User $user)
    {
        $user->delete();
    }

    /**
     * @param User $user
     * @param string $firebaseToken
     */
    public function logout(User $user, $fcmToken = '')
    {
        $user->tokens()->delete();

        // TODO split to event and listener
        $firebaseServices = new FirebaseServices($user, $fcmToken);
        $firebaseServices->deleteFcmToken();
    }

    /**
     * @param User $user
     * @return string
     */
    public function generateAccessToken(User $user): string
    {
        // return $user->createToken('token')->plainTextToken;
        // $user->tokens()->delete();
        return $user->createToken('token')->accessToken;
    }


    /**
     * @param User $user
     * @param AuthData $authData
     * @throws ErrorMsgException
     */
    public function updatePassword($user, $authData, $newPassword){
        $this->checkUpdatePasswordCredentials($authData);
        $user->update([
            'password' => $newPassword
        ]);
    }

    /**
     * @param AuthData $authData
     * @throws ErrorMsgException
     */
    public function forgetPassword($authData){

        // $confirmationCodeClass = new ConfirmationCodeClass($user);
        // $confirmationCodeClass->checkCanResendConfirmationCode($request->time_zone);
        // ConfirmationAccountServices::reSendConfirmationCode($user);


        $user = User::where($authData->getFilledValues())->first();
        if(is_null($user))
            throw new ErrorMsgException(transMsg('forget_password_invalid_data'));

        ConfirmationAccountServices::sendConfirmationCode($user);

    }

    /**
     * @param AuthData $authData
     * @param string $code
     * @return User
     * @throws ErrorMsgException
     */
    public function checkForgetPasswordCode($authData, $code){

        $data = $authData->getFilledValues();
        unset($data['password']);
        $user = User::where($data)->first();
        if(is_null($user))
            throw new ErrorMsgException(transMsg('forget_password_invalid_data'));

        // ConfirmationAccountServices::checkCodeExpireDate(
        //     $user->verified_code_created_at
        // );

        // $confirmationCodeClass = new ConfirmationCodeClass($user);
        // $confirmationCodeClass->resetAttempts();

        ConfirmationAccountServices::checkValidCode($user, $code);

        return $user;

    }

    /**
     * @param AuthData $authData
     * @throws ErrorMsgException
     */
    protected function checkUpdatePasswordCredentials($authData){
        $credentials = $this->getCredentials($authData);

        if (!auth()->guard('web')->attempt($credentials))
            throw new ErrorMsgException(transMsg('wrong_password'));
    }


    /**
     * @param AuthData $authData
     * @throws ErrorMsgException
     * @return array
     */
    private function getCredentials($authData)
    {
        $credentials = [];
        $credentials['password'] = $authData->password;

        if(isset($authData->email)){
            $credentials['email'] = $authData->email;
        }
        else if(isset($authData->phone)){
            $credentials['phone'] = $authData->phone;
            $credentials['phone_code'] = $authData->phone_code;
        }
        return  $credentials;

    }

    /**
     * @param User $user
     * @param UserData $userData
     * @return User
     */
    public function updateUserProfile($user, $userData){
        try {
            DB::beginTransaction();
            $user->update($userData->initializeForUpdate());
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new ErrorMsgException($exception->getMessage());
        }

        return $user;
    }

}
