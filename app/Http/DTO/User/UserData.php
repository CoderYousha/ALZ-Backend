<?php

namespace App\Http\DTO\User;

use App\Http\DTO\Base\ObjectData;
use App\Http\Services\File\FileManagementServicesClass;
use Illuminate\Http\Request;

final class UserData extends ObjectData
{
    public ?string      $phone_code;
    public ?string      $phone;
    public ?string      $name;
    public ?string      $email;
    public ?string      $login_type;
    public ?string      $login_service_id;
    public ?string      $language;
    public ?string      $account_role;
    public ?string      $password;
    public ?string      $fcm_token;
    public ?bool        $is_active;
    public ?string      $image;

    public static $unUpdatableFields=[
        'password',
        'account_role',
        'login_type',
        'login_service_id',
    ];


    public static function fromRequest(Request $request, $role): self
    {
        return new self([
            'email' => $request->email,
            'name' => $request->name,
            'phone_code' => $request->phone_code,
            'phone' => $request->phone,
            'password' => $request->password,
            'login_type' => \App\Enums\LoginTypeEnum::EMAIL->value,
            'login_service_id' => null,
            'language' => \App\Enums\LanguageEnum::EN->value,
            'account_role' => $role,
            'fcm_token' => $request->fcm_token,
            'image' => isset($request->image)
                ? FileManagementServicesClass::storeFiles($request->image, 'profile')
                : null,
            'is_active' => true,
        ]);
    }

    public static function forUpdate(Request $request): self
    {
        return new self([
            'name' => $request->name,
            'email' => $request->email,
            'language' => $request->language ?? null,
            'phone_code' => $request->phone_code,
            'phone' => $request->phone,
            'image' => isset($request->image)
                ? FileManagementServicesClass::storeFiles($request->image, 'profile')
                : null,
        ]);
    }



}
