<?php

use App\Http\Controllers\PromotionCodeController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix' => '/'
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'userInfo']);
    Route::post('assign-promotion', [PromotionCodeController::class, 'assign']);

});

Route::group([
    'middleware' => ['api', 'auth', 'isAdmin'],
    'prefix' => 'backoffice/'
], function () {
    Route::get('promotion-codes', [PromotionCodeController::class, 'viewCodes']);
    Route::get('promotion-codes/{id}', [PromotionCodeController::class, 'viewCode']);
    Route::post('promotion-codes', [PromotionCodeController::class, 'createCode']);
    
});

