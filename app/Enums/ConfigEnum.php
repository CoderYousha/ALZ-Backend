<?php

namespace App\Enums;

use App\Http\Traits\EnumValues;

enum ConfigEnum: string
{

    use EnumValues;

    case TERMS_OF_USE = 'terms_of_use';
    case PRIVACY_POLICY = 'privacy_policy';
    case ABOUT_US = 'about_us';

    case CONTACT_US_EMAIL = 'contact_us_email';
    case CONTACT_US_PHONE = 'contact_us_phone';
    case CONTACT_US_WHATSAPP_NUMBER = 'contact_us_whatsapp_number';
    case CONTACT_US_WEBSITE = 'contact_us_website';
    case CONTACT_US_FACEBOOK = 'contact_us_facebook';
    case CONTACT_US_INSTAGRAM = 'contact_us_instagram';

    public static function langValues(): array
    {
        return [
            self::TERMS_OF_USE->value,
            self::PRIVACY_POLICY->value,
            self::ABOUT_US->value,
        ];
    }

    public static function nonLangValues(): array
    {
        return [
            self::CONTACT_US_EMAIL->value,
            self::CONTACT_US_PHONE->value,
            self::CONTACT_US_WHATSAPP_NUMBER->value,
            self::CONTACT_US_WEBSITE->value,
            self::CONTACT_US_FACEBOOK->value,
            self::CONTACT_US_INSTAGRAM->value,
        ];
    }
}
