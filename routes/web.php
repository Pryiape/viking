<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\BuildController;
use App\Http\Controllers\TalentTreeController;
use App\Http\Controllers\SpecializationController;

// Pages publiques
Route::get('/', [HomeController::class, 'home'])->name('app_home');
Route::get('/a-propos', [HomeController::class, 'about'])->name('app_about');
Route::get('/Blizzard', [HomeController::class, 'Blizzard'])->name('app_Blizzard');

// Authentification
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/existEmail', [LoginController::class, 'existEmail'])->name('app_existEmail');

// Dashboard protégé
Route::match(['get', 'post'], '/dashboard', [HomeController::class, 'dashboard'])
    ->name('app_dashboard')
    ->middleware('auth');

// Profil utilisateur
Route::get('/profile', [UserController::class, 'profile'])->name('app_profile')->middleware('auth');

// Gestion des talents & spécialisations
Route::get('/specializations/{classId}', [SpecializationController::class, 'getSpecializationsByClass']);
Route::get('/get-talent-tree/{specializationId}', [TalentTreeController::class, 'getTalentTree']);

Route::get('/app_builds', [BuildController::class, 'appBuilds'])->name('app_builds');

// Routes Builds protégées
Route::middleware(['auth'])->group(function () {
    Route::resource('builds', BuildController::class);
});