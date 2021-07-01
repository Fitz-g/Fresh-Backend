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
Route::get('/login', [Controllers\AuthController::class, 'login'])->name('login');
Route::post('/register/post', [Controllers\AuthController::class, 'register_post'])->name('register.post');
Route::post('/login/post', [Controllers\AuthController::class, 'login_post'])->name('login.post');


Route::get('/home', [Controllers\DashboardController::class, 'index'])->name('home');
