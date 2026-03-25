<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Users\IndexUserRequest;
use App\Http\Requests\Api\Users\StoreUserRequest;
use App\Http\Requests\Api\Users\UpdateUserPasswordRequest;
use App\Http\Requests\Api\User\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\ApiResponse\ApiResponseClass;
use App\Http\Services\User\UserServices;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private UserServices $userServices;

    public function __construct()
    {
        $this->userServices = new UserServices;
    }

    public function index(IndexUserRequest $request){

        $users = $this->userServices->getUsers($request);

        return ApiResponseClass::successResponse(UserResource::collection($users));
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userServices->store($request);
        return ApiResponseClass::successResponse([
            'user' => new UserResource($user),
        ]);
    }

    public function show(User $user)
    {
        return ApiResponseClass::successResponse(new UserResource($user));
    }


    public function update(UpdateProfileRequest $request, User $user)
    {
        $this->userServices->update($request, $user);
        return ApiResponseClass::successResponse(new UserResource($user));
    }

    public function updatePassword(UpdateUserPasswordRequest $request, User $user)
    {
        $user->password = Hash::make($request->input('password'));
        $user->save();
        return ApiResponseClass::successResponse(new UserResource($user));
    }

    public function delete(User $user)
    {
        $user->delete();

        return ApiResponseClass::successMsgResponse();
    }

    public function profile(Request $request){
        $user = $request->user();
        return ApiResponseClass::successResponse(new UserResource($user));
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $user->update($request->validated());
        $user->refresh();
        return ApiResponseClass::successResponse(new UserResource($user));
    }
    
    public function toggelActive(Request $request, User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        return ApiResponseClass::successMsgResponse();
    }

}
