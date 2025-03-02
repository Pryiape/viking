<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;

// Page d'accueil et autres pages
Route::get('/', [HomeController::class, 'home'])->name('app_home');
Route::get('/about', [HomeController::class, 'about'])->name('app_about');
Route::get('/builds', [HomeController::class, 'builds'])->name('app_builds');
Route::get('/profile', [UserController::class, 'profile'])->name('app_profile');

// Tableau de bord protégé
Route::match(['get', 'post'], '/dashboard', [HomeController::class, 'dashboard'])
    ->name('app_dashboard')
    ->middleware('auth');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/register', [RegisterController::class, 'register'])->name('register'); 
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('app_logout');
Route::post('/existEmail', [LoginController::class, 'existEmail'])->name('app_existEmail');
