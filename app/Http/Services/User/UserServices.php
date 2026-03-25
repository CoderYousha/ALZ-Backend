<?php

namespace App\Http\Services\User;

use App\Exceptions\ErrorMsgException;
use App\Http\DTO\User\UserData;
use App\Http\Requests\Api\Users\StoreUserRequest;
use App\Http\Requests\Api\User\UpdateProfileRequest;
use App\Http\Services\Auth\AuthServices;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserServices
{

    private AuthServices $authServices;

    public function __construct()
    {
        $this->authServices = new AuthServices();
    }

    /**
     * @param StoreUserRequest $storeUserRequest
     * @return User
     */
    public function store(StoreUserRequest $storeUserRequest){
        try {
            DB::beginTransaction();
            $data = UserData::fromRequest($storeUserRequest, $storeUserRequest->account_role);
            $user = $this->authServices->createUser($data);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new ErrorMsgException($exception->getMessage());
        }

        return $user;
    }
    
    /**
     * @param UpdateProfileRequest $request
     * @param User $user
     * @return User
     */
    public function update(UpdateProfileRequest $request, User $user){
        $user = $this->authServices->updateUserProfile(
            $user,
            UserData::forUpdate($request),
        );

        return $user;
    }

    /**
     * @param string|null $role
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getUsers($request)
    {
        $query = User::query();

        $query->when($request->account_role, function ($q) use ($request) {
            $q->where('account_role', $request->account_role);
        });

        $query->when(isset($request->active), function ($q) use ($request) {
            $q->where('is_active', $request->active);
        });

        $query->when($request->search, function ($q) use ($request) {
            $search = $request->search;
            $q->where(function ($q) use ($search) {
                $q->whereRaw("name LIKE ?", ["%{$search}%"])
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhereRaw("CONCAT(phone_code, phone) LIKE ?", ["%{$search}%"]);
            });
        });

        $query->when($request->has('from'), function ($q) use ($request) {
            $q->whereDate('created_at', '>=', $request->from); 
        });

        $query->when($request->has('to'), function ($q) use ($request) {
            $q->whereDate('created_at', '<=', $request->to);
        });

        return $query->dynamicOrder($request)->paginate($request->per_page ?? 10);
    }

    /**
     * @param User $user
     * @return User
     */
    public function toggleUserActiveStatus(User $user){
        $user->is_active = !$user->is_active;
        $user->save();
        $user->refresh();
        return $user;
    }
}
