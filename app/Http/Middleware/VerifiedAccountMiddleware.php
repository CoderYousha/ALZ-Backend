<?php

namespace App\Http\Middleware;

use App\Exceptions\ErrorMsgException;
use Closure;
use Illuminate\Http\Request;

class VerifiedAccountMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if(is_null($user->verified_at)){
            throw new ErrorMsgException(transMsg('your_account_is_not_verified'));
        }

        return $next($request);
    }
}
