<?php

namespace App\Listeners;

use App\Events\UserLoggedIn;
use App\Http\Services\Firebase\FirebaseServices;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SaveFcmToken
{


    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(UserLoggedIn $event): void
    {
        if($event->fcmToken){
            $firebaseServices = new FirebaseServices($event->user, $event->fcmToken);
            $firebaseServices->saveFcmToken();
        }
    }
}
