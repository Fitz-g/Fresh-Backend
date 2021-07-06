<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

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


Route::get('/register', [Controllers\AuthController::class, 'register'])->name('register');
Route::get('/', [Controllers\AuthController::class, 'login'])->name('login');
Route::post('/register/post', [Controllers\AuthController::class, 'register_post'])->name('register.post');
Route::post('/login/post', [Controllers\AuthController::class, 'login_post'])->name('login.post');


Route::middleware('auth')->group(function() {
    Route::get('/home', [Controllers\DashboardController::class, 'index'])->name('home');
    Route::get('/logout', [Controllers\AuthController::class, 'logout'])->name('logout');

    // User
    Route::get("/user-profile", [Controllers\UserController::class, 'profile'])->name('profile');
    Route::post("/update-avatar", [Controllers\UserController::class, 'update_avatar'])->name('update.avatar');
    Route::post("/update-profile", [Controllers\UserController::class, 'update_profile'])->name('update.profile');
    Route::post("/update-password", [Controllers\UserController::class, 'update_password'])->name('update.password');
});


// Commands
Route::get("/fitz/migrate", function () {
    try {
        \Illuminate\Support\Facades\Artisan::call("migrate");
    } catch(\Exception $e) {
        dd($e->getMessage());
    }
});
Route::get("/fitz/clear", function () {
    try {
        \Illuminate\Support\Facades\Artisan::call("route:clear");
        \Illuminate\Support\Facades\Artisan::call("cache:clear");
        \Illuminate\Support\Facades\Artisan::call("config:clear");
        \Illuminate\Support\Facades\Artisan::call("view:clear");

        return "route, cache, config and view cleared";
    } catch(\Exception $e) {
        dd($e->getMessage());
    }
});
Route::get("/fitz/seed", function () {
    try {
        \Illuminate\Support\Facades\Artisan::call("db:seed");
    } catch(\Exception $e) {
        dd($e->getMessage());
    }
});
