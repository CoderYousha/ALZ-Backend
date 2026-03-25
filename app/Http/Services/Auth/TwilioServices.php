<?php


namespace App\Http\Services\Auth;

use Exception;
use Twilio\Rest\Client;

class TwilioServices
{
    private $token;
    private $twilio_sid;
    private $twilio_verify_sid;

    public function __construct()
    {
        $this->token = env("TWILIO_AUTH_TOKEN");
        $this->twilio_sid = env("TWILIO_SID");
        $this->twilio_verify_sid = env("TWILIO_VERIFY_SID");
    }

    public function createVerificationCode($phoneNumber)
    {
        try {
            $twilio = new Client($this->twilio_sid, $this->token);
            $twilio->verify->v2->services($this->twilio_verify_sid)
                ->verifications
                ->create($phoneNumber, "sms");
        } catch (Exception $e) {
            return $e;
        }
        return true;
    }

    public function Verify($phoneNumber, $verificationCode)
    {
        try {
            $twilio = new Client($this->twilio_sid, $this->token);

            $verification = $twilio->verify->v2->services($this->twilio_verify_sid)
                ->verificationChecks
                ->create(array(
                    'code' => $verificationCode,
                    'to' => $phoneNumber
                ));

            if ($verification->valid)
                return true;
        } catch (Exception $e) {
            return $e;
        }
        return false;
    }
}
