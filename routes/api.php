<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;

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

// API Version 1 Routes
Route::prefix('v1')->group(function () {
    // Public routes
    Route::middleware(['throttle:only_five_visits'])->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');
        Route::post('/upload-profile-image', [AuthController::class, 'uploadProfileImage'])->name('upload-profile-image');
        Route::get('/account-activation/{activation_token}', [AuthController::class, 'accountActivation'])->name("account-activation");
    });

    // Email validation
    Route::post('/validate-user-email', [AuthController::class, 'validateUserEmail'])->name('validate-user-email');

    // Protected routes
    Route::middleware(['auth:sanctum', 'verified'])->group(function () {
        // Auth routes
        Route::get('/get-user-list-pdf', [AuthController::class, 'getUserListPdf'])->name('get-user-list-pdf');
        Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change-password');
        Route::get('/pending-user-list', [AuthController::class, 'pendingUserList'])->name('pending-user-list');
        Route::post('/pending-user-approve-action', [AuthController::class, 'pendingUserApproveAction'])->name('pending-user-approve-action');
        Route::get('/get-user-list', [AuthController::class, 'getUserList'])->name('get-user-list');
        Route::get('/get-profile', [AuthController::class, 'getProfile'])->name('get-profile');
        Route::put('/update-profile', [AuthController::class, 'updateProfile'])->name('update-profile');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/get-blocked-user-list', [AuthController::class, 'getBlockedUserList'])->name('get-blocked-user-list');
        Route::post('/unblock-user', [AuthController::class, 'unblockedUser'])->name('unblock-user');

        // File upload routes
        Route::post('/upload', [FileUploadController::class, 'upload'])->name('upload');
        Route::post('/submit', [FileUploadController::class, 'submit'])->name('submit');
    });

    // System routes
    Route::get('/health', function () {
        return response()->json([
            'status' => 'success',
            'message' => 'System is healthy',
            'timestamp' => now()->toIso8601String()
        ]);
    })->name('health');

    Route::get('/status', function () {
        return response()->json([
            'status' => 'success',
            'message' => 'API is running',
            'version' => 'v1',
            'timestamp' => now()->toIso8601String()
        ]);
    })->name('status');

    Route::apiResource('users', UserController::class);
});

Route::get('/test-redis', function () {
    try {
        Redis::set('test_key', 'Redis is working!');
        $value = Redis::get('test_key');
        return response()->json([
            'status' => 'success',
            'message' => 'Redis is working properly',
            'data' => $value
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Redis connection failed',
            'error' => $e->getMessage()
        ], 500);
    }
});
