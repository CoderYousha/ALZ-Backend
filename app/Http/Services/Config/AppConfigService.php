<?php

namespace App\Http\Services\Config;

use App\Models\AppConfig;
use Illuminate\Support\Facades\Cache;

class AppConfigService
{
    public static function get(string $key, $default = null)
    {
        return Cache::remember(
            "app_config_{$key}",
            now()->addDay(),
            fn () => AppConfig::where('key', $key)->value('value') ?? $default
        );
    }

    public static function set(string $key, $value): void
    {
        AppConfig::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget("app_config_{$key}");
    }
}
