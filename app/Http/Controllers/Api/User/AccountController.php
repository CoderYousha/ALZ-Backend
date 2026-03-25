<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\DTO\User\UserData;
use App\Http\Requests\Api\User\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\ApiResponse\ApiResponseClass;
use App\Http\Services\Auth\AuthServices;
use App\Http\Services\Firebase\FirebaseServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class AccountController extends Controller
{

    private AuthServices $authServices;

    public function __construct()
    {
        $this->authServices = new AuthServices();
    }

    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        $this->authServices->logout($user, $request->fcm_token);
        $this->authServices->deleteAccount($user);

        return ApiResponseClass::successMsgResponse();
    }

    public function updateLang(Request $request)
    {
        $user = $request->user();
        $lang = App::getLocale();
        $firebaseServices = new FirebaseServices($user, $request->fcm_token);
        $firebaseServices->updateLang($lang);
        $user->language = $lang;
        $user->save();
        return ApiResponseClass::successMsgResponse();

    }

    public function getProfile(Request $request){
        $user = $request->user();
        return ApiResponseClass::successResponse(new UserResource($user));
    }

    public function updateProfile(UpdateProfileRequest $request){

        $user = $this->authServices->updateUserProfile(
            $request->user(),
            UserData::forUpdate($request),
        );

        return ApiResponseClass::successResponse(new UserResource($user));
    }

}
