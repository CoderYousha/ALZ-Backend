<?php

namespace App\Enums;

use App\Http\Traits\EnumValues;

enum NotificationTypeEnum: string
{

    use EnumValues;

    case GLOBAL_NOTIFICATION = 'global_notification';
    
}
