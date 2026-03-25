<?php

namespace App\Enums;

use App\Http\Traits\EnumValues;

enum LoginTypeEnum: string
{

    use EnumValues;

    case EMAIL = 'email';
    case PHONE = 'phone';
    case GOOGLE = 'google';
    case FACEBOOK = 'facebook';

}
