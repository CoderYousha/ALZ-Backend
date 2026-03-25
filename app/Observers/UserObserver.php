<?php

namespace App\Observers;

use App\Http\Services\Auth\ConfirmationAccountServices;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        if($user->account_role === \App\Enums\RoleEnum::ADMIN->value || $user->account_role === \App\Enums\RoleEnum::TEACHER->value){
            // ConfirmationAccountServices::sendConfirmationCode($user);
            ConfirmationAccountServices::confirmAccount($user);
        }else{
            if($user->login_service_id)
                ConfirmationAccountServices::confirmAccount($user);
            else
                ConfirmationAccountServices::sendConfirmationCode($user);
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
