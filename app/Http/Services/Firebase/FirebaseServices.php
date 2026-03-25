<?php

namespace App\Http\Services\Firebase;


use App\Models\FcmToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

class FirebaseServices
{

    /** @var User  */
    private $user;
    /** @var string|null  */
    private $fcmToken;
    /** @var string  */
    private $lang;

    public function __construct(User $user, ?string $fcmToken){
        $this->user = $user;
        $this->fcmToken = $fcmToken;
        $this->lang = App::getLocale();
    }


    /**
     * @param null|string $lang
     */
    public function saveFcmToken(): void
    {

        $foundToken = FcmToken::/*where('token', $this->fcmToken)
            ->*/where('user_id', $this->user->id)
            ->first();

        if (is_null($foundToken))
            FcmToken::create([
                'user_id' => $this->user->id,
                'token' => $this->fcmToken,
                'lang' => $this->lang
            ]);
        else
            $foundToken->update([
                'token' => $this->fcmToken,
                'updated_at' => Carbon::now(),
                'lang' => $this->lang,
            ]);

        $this->updateLang($this->lang);
    }


    public function deleteFcmToken(): void
    {
        FcmToken::where('user_id', $this->user->id)
            ->where('token', $this->fcmToken)->delete();
    }

    public function updateLang($lang): void
    {
        FcmToken::where('token', $this->fcmToken)
            // ->where('user_id', $this->user->id)
            ->update([
                'lang' => $lang,
            ]);

    }


}
