<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProductController;

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

Route::get('/', [LoginController::class, 'showLoginForm']);

Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::middleware('auth')->group(function () {
    Route::resource('home','ProductController')->except(['show','update']);
    
    Route::get('/chart', [ProductController::class, 'chart'])->name('products.chart');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
