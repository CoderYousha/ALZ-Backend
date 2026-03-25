<?php

namespace App\Exceptions;

use App\Http\Services\ApiResponse\ApiResponseClass;
use Exception;
use Throwable;

class InvalidTokenException extends Exception
{
    protected $message;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $this->message = transMsg('invalid_token');

        parent::__construct($this->message, $code, $previous);
    }

    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report()
    {
    }

    public function render($request)
    {
        return  ApiResponseClass::errorMsgResponse($this->message,401);
    }
}
