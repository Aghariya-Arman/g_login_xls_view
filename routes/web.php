<?php

use App\Http\Controllers\GoogleAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [GoogleAuthController::class, 'loginpage'])->name('login');

Route::get('AddUser', [GoogleAuthController::class, 'AddUser'])->name('AddUser');
Route::Post('create', [GoogleAuthController::class, 'register'])->name('createuser');

Route::get('dashboard', [GoogleAuthController::class, 'dashboard'])->name('home');
Route::get('/users', [GoogleAuthController::class, 'getuser'])->name('get.users');
Route::get('/filter', [GoogleAuthController::class, 'filteruser'])->name('get.filtered.users');
Route::post('/download-csv', [GoogleAuthController::class, 'downloadFilteredFile'])->name('downloadFilteredFile');

Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google-auth');
Route::get('auth/google/call-back', [GoogleAuthController::class, 'CallbackGoogle'])->name('');
Route::post('chklogin', [GoogleAuthController::class, 'login'])->name('chklogin');
Route::get('logout', [GoogleAuthController::class, 'logout'])->name('logout');
