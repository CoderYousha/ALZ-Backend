<?php

namespace App\Http\Middleware;

use App\Models\ListingLocale;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LangMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    private $langs;
    public function __construct()
    {
        $this->langs = \App\Enums\LanguageEnum::values();
    }

    public function handle(Request $request, Closure $next)
    {
        // $requestLang = $request->language;
        $requestLang = $request->header('Accept-Language');
        App::setLocale(
            isset($requestLang) && in_array($requestLang,$this->langs)
                ?$requestLang
                :"en"
        );

        return $next($request);
    }
}
