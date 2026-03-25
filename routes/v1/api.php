<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ConfigController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\User\AccountController;
use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\User\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::group(['prefix' => 'lists','as' => 'lists.'],function () {
    Route::get('config',[ConfigController::class,'getConfig'])->name('config');
});

Route::post('login',[AuthController::class,'login'])->name('login');
Route::post('register',[AuthController::class,'register'])->name('register');

Route::group(['prefix' => 'forget-password','as' => 'forget-password.'],function () {
    Route::post('/',[AuthController::class,'forgetPassword'])->name('index');
    Route::post('/check-code',[AuthController::class,'checkForgetPasswordCode'])->name('checkcode');
});

Route::group(['prefix' => 'categories','as' => 'categories.'],function () {
    Route::get('/', [CategoryController::class,'index'])->name('index');
    Route::get('/{category}/show', [CategoryController::class,'show'])->name('show');
});

Route::group([ 'prefix' => 'products', 'as' => 'products.'], function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{product}/show', [ProductController::class,'show'])->name('show');
});

Route::group(['middleware' => ['auth:api']], function() {

    Route::group(['prefix' => 'account','as' => 'account.'],function () {
        Route::post('update-password',[AuthController::class,'updatePassword'])->name('update-password');
        Route::post('update-lang',[AccountController::class,'updateLang'])->name('update-lang');
        Route::delete('/delete',[AccountController::class,'deleteAccount'])->name('delete');
        Route::get('get-profile',[AccountController::class,'getProfile'])->name('get-profile');
        Route::post('update-profile',[AccountController::class,'updateProfile'])->name('update-profile');
    });

    Route::post('confirm-account',[AuthController::class,'confirmAccount'])->name('confirm.account');
    Route::post('confirmation-code/resend',[AuthController::class,'resendConfirmationAccountCode'])->name('confirm.account.resend');

    Route::post('logout',[AuthController::class,'logout'])->name('logout');


    // admin
    Route::group(['prefix' => 'admin','as' => 'admin.'],function () {
        Route::group(['prefix' => 'users','as' => 'users.'],function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/{user}/show', [UserController::class, 'show'])->name('show');
            Route::post('/store', [UserController::class, 'store'])->name('store');
            Route::post('/{user}/update', [UserController::class, 'update'])->name('update');
            Route::post('/{user}/password/update', [UserController::class, 'updatePassword'])->name('update-password');
            Route::delete('/{user}/delete', [UserController::class, 'delete'])->name('delete');
            Route::post('/{user}/active', [UserController::class, 'toggelActive'])->name('active');
        });


        Route::group(['prefix' => 'config','as' => 'config'],function () {
            Route::get('/', [ConfigController::class,'listConfig'])->name('index');
            Route::post('/update', [ConfigController::class, 'updateConfig'])->name('update');
            Route::post('upload-image', [ConfigController::class, 'uploadImage']);
        });

        Route::group(['prefix' => 'categories','as' => 'categories'],function () {
            Route::post('/store', [CategoryController::class, 'store'])->name('store');
            Route::post('/{category}/update', [CategoryController::class,'update'])->name('update');
            Route::delete('/{category}/delete', [CategoryController::class, 'delete'])->name('delete');
        });

        Route::group(['prefix' => 'products','as' => 'products.'],function () {
            Route::post('/store',[ProductController::class,'store'])->name('store');
            Route::post('{product}/update',[ProductController::class,'update'])->name('update');
            Route::delete('{product}/delete',[ProductController::class,'delete'])->name('delete');
        });

    });
});
