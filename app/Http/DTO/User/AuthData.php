<?php

namespace App\Http\DTO\User;

use App\Http\DTO\Base\ObjectData;
use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\RoleEnum;

final class AuthData extends ObjectData
{
    public ?int       $id=null;
    public ?string    $email;
    public ?string    $account_role;
    public ?string    $phone_code;
    public ?string    $phone;
    public ?string    $password;
    public ?string    $fcm_token;

    public static function fromRequest(Request $request): self
    {
         return new self([
            'email' => isset($request->email) ? $request->email : null,
            'phone_code' => !isset($request->email) ? $request->phone_code : null,
            'phone' => !isset($request->email) ? $request->phone : null,
            'password' => $request->password,
            'fcm_token' => $request->fcm_token,
            'account_role' => $request->account_role ?? null,

        ]);
    }


    public static function forChangePassword(Request $request, User $user): self
    {
        return new self([
            'email' => $user->email,
            'phone_code' => $user->phone_code,
            'phone' => $user->phone,
            'password' => $request->old_password,
        ]);
    }

    public function getFilledValues(){
        return $this->initializeForUpdate();
    }






}
