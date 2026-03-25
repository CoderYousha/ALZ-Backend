<?php

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\App;

if(!function_exists('transMsg')){
    /**
     * @var string $translateString the string we want to translate it
     * @var array $parameters is the parameter you want to send to trans msg
     * (start with upper case letter and the rest chars lower case)
     * @return string
     */
    function transMsg($translateString ,array $parameters=[]){
        return trans('messages.'.$translateString,$parameters);

    }
}

if(!function_exists('transValidationParameter')){
    function transValidationParameter($parameterName ,array $parameters=[]){
            return trans('validationParameters.'.$parameterName,$parameters);

    }
}

if(!function_exists('transRuleMsg')){
    function transRuleMsg($translateString ){
            return trans('validationRuleMessages.'.$translateString);

    }
}

if(!function_exists('baseRoute')){
    function baseRoute(): string
    {

//       return url('/').'/tier/public/storage/';
       return url('/').'/storage/';
    }
}


/**
 * this method just for debug
 */
if(!function_exists('throwError')){
    function throwError($object): string
    {
        throw new \App\Exceptions\ErrorMsgException(json_encode($object));
    }
}

// /**
//  * return default profile picture path depends on user account type
//  */
// if(!function_exists('defaultUserImage')){
//     function defaultUserImage($user): string
//     {
//     }
// }



if(!function_exists('changeLang')){
    function changeLang(string $lang='en') : void
    {
        App::setLocale($lang);
    }
}

if(!function_exists('dispatchJob')){
    function dispatchJob($job) : void
    {
        dispatch($job);
    }
}


if(!function_exists('getSettingKeyByLang')){
    function getSettingKeyByLang($keyName, $lang) : string
    {
        return $keyName."_".$lang;
    }
}


if(!function_exists('explodeImages')){
    function explodeImages($imagesAsString) : array
    {
        $images = json_decode($imagesAsString,true) ?? [];
        $list = [];
        foreach ($images as $image){
            $list[] = baseRoute() . $image;
        }
        return $list;
    }
}

if(!function_exists('getFullPhone')) {
    /**
     * @param User $user
     * @return string
     */
    function getFullPhone($user): bool
    {
        return $user->phone_code . $user->phone;
    }
}