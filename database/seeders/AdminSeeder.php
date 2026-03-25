<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->name = 'admin';
        $user->email = 'admin@gmail.com';
        $user->phone_code = '+963';
        $user->phone = '96677842';
        $user->password = Hash::make('Aa123456');
        $user->account_role = RoleEnum::ADMIN->value;
        $user->save();

    }
}
