<?php
namespace App\Http\Services\Auth;

use App\Exceptions\ErrorMsgException;
use App\Mail\ConfirmationMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Exception;

class ConfirmationAccountServices{

    /**
     * @param User $user
     * @return User $user
     * if the account is verified before the just return
     * else update the status to verified and return
     */
    public static function confirmAccount(User $user): User
    {
        if(!is_null($user->verified_at))
            return $user;

        $user->update([
            'verified_at' => Carbon::now(),
//            'is_active' => true,
        ]);
        return $user;
    }

    public static function checkValidCode(User $user, $requestedCode){

        // TODO: twilio
        // if(isClient($user)){
        //     $twilio = new TwilioServices();
        //     $verify = $twilio->Verify(getFullPhone($user), $requestedCode);
        //     if ($verify instanceof Exception)
        //         throw $verify;

        //     if(!$verify)
        //         throw new ErrorMsgException(transMsg('invalid_confirmation_code'));
        // }

        if($user->verified_code != $requestedCode || !self::checkCodeExpireDate($user->verified_code_created_at))
            throw new ErrorMsgException(transMsg('invalid_confirmation_code'));

        return true;
    }

    protected static function checkCodeExpireDate($codeCreatedAt, $codeLongTime=null){
        if(is_null($codeLongTime))
            $codeLongTime = config('panel.account_confirmation_code_long_time', 15);

        $expireDate = Carbon::createFromFormat(
            'Y-m-d'.' '.'H:i:s', $codeCreatedAt
        )->addMinutes($codeLongTime);

        if($expireDate < Carbon::now())
            throw new ErrorMsgException(transMsg('invalid_confirmation_code_expiration_date'));

        return true;

    }

    /**
     * @return string of numbers
     */
    public static function generateConfirmationCode():string
    {
        $code = self::generateRandomString(config('panel.confirmation_code_length', 4), '0123456789');
        return $code;
    }

    protected static function generateRandomString($length = 10, $characters ='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'/*'!@#$%^&*_-'*/) {
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    /**
     * we have observer working after update
     * @see UserObserver for send notification
     */
    public static function sendConfirmationCode(User $user):User
    {

        /**
         * we used this because this filed are hidden in model
         */

        // TODO: remove this
        $user->verified_code = '0000';
        $user->verified_code_created_at = Carbon::now();
        $user->save();
        return $user;

        $user->verified_code = self::generateConfirmationCode();
        $user->verified_code_created_at = Carbon::now();
        $user->save();
        self::sendEmail($user, $user->verified_code);
        return $user;
    }

    protected static function sendEmail(User $user, $code)
    {
        Mail::to($user)->send(new ConfirmationMail($code));
    }

}
