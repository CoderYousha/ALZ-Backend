<?php

namespace App\Enums;

use App\Http\Traits\EnumValues;

enum LanguageEnum: string
{

    use EnumValues;

    case AR = 'ar';
    case EN = 'en';

}
