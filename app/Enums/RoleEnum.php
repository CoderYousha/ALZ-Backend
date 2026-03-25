<?php

namespace App\Enums;

use App\Http\Traits\EnumValues;

enum RoleEnum: string
{
    use EnumValues;

    case ADMIN = 'admin';

}
