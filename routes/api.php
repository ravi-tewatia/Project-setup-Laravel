<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\FileUploadController;
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

Route::middleware(['throttle:only_five_visits'])->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');
    Route::post('/upload-profile-image', [AuthController::class, 'uploadProfileImage'])->name('upload-profile-image');
    Route::get('/account-activation/{activation_token}', [AuthController::class, 'accountActivation'])->name("account-activation");
});

Route::post('/validate-user-email', [AuthController::class, 'validateUserEmail'])->name('validate-user-email');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    /* Auth routes start*/
    Route::get('/get-user-list-pdf', [AuthController::class, 'getUserListPdf'])->name('get-user-list-pdf');

    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change-password');
    Route::Get('/pending-user-list', [AuthController::class, 'pendingUserList'])->name('pending-user-list');
    Route::Post('/pending-user-approve-action', [AuthController::class, 'pendingUserApproveAction'])->name('pending-user-approve-action');
    Route::get('/get-user-list', [AuthController::class, 'getUserList'])->name('get-user-list');
    Route::get('/get-profile', [AuthController::class, 'getProfile'])->name('get-profile');
    Route::put('/update-profile', [AuthController::class, 'updateProfile'])->name('update-profile');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/get-blocked-user-list', [AuthController::class, 'getBlockedUserList'])->name('get-blocked-user-list');
    Route::post('/unblock-user', [AuthController::class, 'unblockedUser'])->name('unblock-user');


    /* Auth routes end*/

});

Route::post("submit", [FileUploadController::class,'submit']);
Route::post("upload", [FileUploadController::class,'upload']);
