<?php

use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Http\Request;
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

Route::controller(AuthController::class)->group(function () {
    Route::post('check_username', 'checkUsername');
    Route::post('check_mobile_number', 'checkMobileNumber');
    Route::get('locations', 'locations');
    Route::get('services', 'services');
    Route::get('categories', 'categories');
    Route::post('register', 'register');
    Route::post('login', 'login');
});
Route::middleware('auth:sanctum')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('upload_image', 'uploadImage');
    });
});
