<?php

namespace App\Http\Middleware;

use App\Exceptions\ErrorMsgException;
use Closure;
use Illuminate\Http\Request;

class ActiveAccountMiddleware
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
        if(!$user->is_active){
            throw new ErrorMsgException(transMsg('your_account_is_not_active'));
        }

        return $next($request);
    }
}
