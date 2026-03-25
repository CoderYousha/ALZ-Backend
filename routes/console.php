<?php

// use App\Http\Services\Survey\SurveyServices;

use App\Enums\RoleEnum;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    $afterWeek = Carbon::now()->addWeek();
    $clients = User::where('account_role', RoleEnum::CLIENT->value)
        ->with(['client' => function($query) use ($afterWeek){
            $query->where('driving_license_expiry', '<=', $afterWeek);
        }])->get();

    // TODO: send notification to those clients
})->weekly();