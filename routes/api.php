<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DepartmentItemController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EmailController;

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


//public routes
Route::apiResource('department', DepartmentController::class);
Route::apiResource('department-item', DepartmentItemController::class);
Route::apiResource('email', EmailController::class);

Route::apiResource('document', DocumentController::class);

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('refresh', [AuthController::class, 'refresh']);
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('user', [AuthController::class, 'user']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});


Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('department-master', DepartmentMasterController::class);
    Route::get('dashboard', DashboardController::class);
    Route::apiResource('setting', SettingController::class);
    Route::apiResource('user', UserController::class);
});
