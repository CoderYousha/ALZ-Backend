<?php

namespace App\Claims;

use App\Http\Resources\UserResource;
use App\Models\User;
use CorBosman\Passport\AccessToken;

class UserClaim
{
    public function handle(AccessToken $token, $next)
    {
        $user = User::find($token->getUserIdentifier());
        // if ($user->account_role !== \App\Enums\RoleEnum::ADMIN->value) {
        //     $user->load(["$user->account_role"]);
        // }

        $token->addClaim('user', new UserResource($user));
        return $next($token);
    }
}
