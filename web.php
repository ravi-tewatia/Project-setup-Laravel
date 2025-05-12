<?php

use App\Http\Controllers\FileUploadController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
// Route::view('account-activation', 'auth.account-activate')->with(['result' => ['message' => "hello"]]);
Route::get('/account-activation', function () {
    return view('pdf.demo-pdf-export')->with(['result' => ['message' => "hello"]]);
});
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard1', function () {
    return "Hi there";
})->name('dashboard1');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/dashboard2', function () {
        return "Hi there 2";
    })->name('dashboard2');
});

/* ------ START ---------- */
Route::view("file", "fileUpload/file_upload");
Route::post("submit", [FileUploadController::class,'submit']);
Route::post("upload", [FileUploadController::class,'upload']);
/* ------ END ---------- */

/*this is For Queue concept File Upload */
Route::get('/queue-work', function () {
    set_time_limit(0);
    Artisan::call('queue:work', ['--stop-when-empty' => true]);
    return Artisan::output();
});
