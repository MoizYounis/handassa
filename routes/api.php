<?php

use App\Helpers\Constant;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Client\PostsController;
use App\Http\Controllers\Api\NotificationsController;
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

// Auth Routes
Route::controller(AuthController::class)->group(function () {
    Route::post('check_username', 'checkUsername');
    Route::post('check_mobile_number', 'checkMobileNumber');
    Route::get('locations', 'locations');
    Route::get('services', 'services');
    Route::get('categories', 'categories');
    Route::post('register', 'register');
    Route::post('login', 'login');
});

// Image Upload
Route::controller(AuthController::class)->group(function () {
    Route::post('upload_image', 'uploadImage');
});

Route::middleware('auth:sanctum')->group(function () {
    // Client Routes
    Route::prefix(Constant::CLIENT)->group(function () {
        Route::controller(PostsController::class)->group(function () {
            Route::post('save/post', 'store');
            Route::post('proposal/status', 'proposalStatus');
            Route::get('post/{id}/proposals', 'proposals');
            Route::get('professional/{id}/rating', 'professionalRating');
        });
    });

    // Professional Routes
    Route::prefix(Constant::PROFESSIONAL)->group(function () {
        Route::controller(PostsController::class)->group(function () {
            Route::post('post/proposal', 'proposal');
            Route::get('image/{id}/delete', [NotificationsController::class, 'deleteImage']);
        });
    });
    // All Posts
    Route::get('posts', [PostsController::class, 'index']);
    Route::post('post/status', [PostsController::class, 'postStatus']);
    Route::post('post/rating', [PostsController::class, 'rating']);
    Route::post('update/profile', [AuthController::class, 'updateProfile']);
    Route::get('notifications', [NotificationsController::class, 'index']);
    Route::get('notification/{id}/delete', [NotificationsController::class, 'delete']);
});
