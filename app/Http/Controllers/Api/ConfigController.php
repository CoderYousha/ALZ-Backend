<?php

namespace App\Http\Controllers\Api;

use App\Enums\ConfigEnum;
use App\Enums\LanguageEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Config\ConfigRequest;
use App\Http\Services\ApiResponse\ApiResponseClass;
use App\Http\Services\Config\AppConfigService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Models\AppConfig;
use Illuminate\Support\Facades\File;

class ConfigController extends Controller
{
    public function getConfig(ConfigRequest $request)
    {
        $config = collect();

        foreach ($request->input('keys') as $key) {
            $configKey = $key;
            if(in_array($key, ConfigEnum::langValues())){
                $lang = App::getLocale();
                $configKey = "{$key}_{$lang}";
            }
    
            $config[$key] = AppConfigService::get($configKey);
        }

        return ApiResponseClass::successResponse($config);
    }

    public function listConfig()
    {
        $data = collect();

        // Lang configs
        foreach (ConfigEnum::langValues() as $key) {
            foreach (LanguageEnum::values() as $lang) {
                $configKey = "{$key}_{$lang}";
                $data[$configKey] = AppConfigService::get($configKey);
            }
        }

        // Non-lang configs
        foreach (ConfigEnum::nonLangValues() as $key) {
            $data[$key] = AppConfigService::get($key);
        }

        return ApiResponseClass::successResponse($data);
    }

    public function updateConfig(Request $request)
    {
        // Lang configs
        foreach (ConfigEnum::langValues() as $key) {
            foreach (LanguageEnum::values() as $lang) {
                $configKey = "{$key}_{$lang}";
                if ($request->has($configKey)) {
                    AppConfigService::set($configKey, $request->input($configKey));
                }
            }
        }

        // Non-lang configs
        foreach (ConfigEnum::nonLangValues() as $key) {
            if ($request->has($key)) {
                AppConfigService::set($key, $request->input($key));
            }
        }

        return ApiResponseClass::successMsgResponse();
    }

    public function uploadImage (Request $request) {
        $config = AppConfig::where('key', 'image')->first();
        if($request->file('image')){
            $image = $request->file('image')->storePublicly('Background', 'public');
        }
        if(!$config){
            AppConfig::create([
                'key' => 'image',
                'value' => 'storage/' . $image,
            ]);
        }else{
            if(File::exists($config->image)){
                File::delete($config->image);
            }
            $config->update([
                'key' => 'image',
                'value' => $image,
            ]);
        }
    }
}
