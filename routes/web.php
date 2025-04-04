<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GoogleController;


// classe2 pas4


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

Route::post('login', [AuthenticatedSessionController::class, 'login']);
Route::post('register', [AuthenticatedSessionController::class, 'register']);
Route::post('logout', [AuthenticatedSessionController::class, 'logout']);

Route::get('google-auth/redirect', [GoogleController::class, 'googleLogin'])->name('auth.google');
Route::get('google-auth/callback', [GoogleController::class, 'googleAuth'])->name('auth.google.callback');

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::view('/{any?}', 'main-view')
    ->name('dashboard')
    ->where('any', '.*');


