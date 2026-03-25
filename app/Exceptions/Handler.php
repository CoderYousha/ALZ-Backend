<?php

namespace App\Exceptions;

use App\Http\Services\ApiResponse\ApiResponseClass;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


    public function render($request, Throwable $e)
    {
        if ($e instanceof ModelNotFoundException)
        {

            //App\Models\ModelName
            $modelPath = explode('\\',$e->getModel());

            if(isset($modelPath[2]))
                return ApiResponseClass::notFoundResponse($modelPath[2].' '.transMsg('invalid_id'));
            else
                return ApiResponseClass::notFoundResponse(transMsg('invalid_id'));

        }

        $response = parent::render($request, $e);
        return $response;

    }

}
