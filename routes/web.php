<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\BuildController;
use App\Http\Controllers\TalentController; // Importer le TalentController
use App\Http\Controllers\SpecializationController; // Importer le SpecializationController

// Page d'accueil et autres pages
Route::get('/', [HomeController::class, 'home'])->name('app_home');
Route::get('/a-propos', [HomeController::class, 'about'])->name('app_about'); // Rename route to À propos
Route::get('/builds', [HomeController::class, 'builds'])->name('app_builds');
Route::get('/profile', [UserController::class, 'profile'])->name('app_profile');
Route::get('/Blizzard', [HomeController::class, 'Blizzard'])->name('app_Blizzard');


// Ajouter la route pour récupérer les spécialisations par classe
Route::get('/specializations/{classId}', [SpecializationController::class, 'getSpecializationsByClass']);

// Tableau de bord protégé
Route::match(['get', 'post'], '/dashboard', [HomeController::class, 'dashboard'])
    ->name('app_dashboard')
    ->middleware('auth');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/register', [RegisterController::class, 'register'])->name('register'); // Add registration route
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/existEmail', [LoginController::class, 'existEmail'])->name('app_existEmail');

// Build routes
Route::get('/builds/create', [BuildController::class, 'create'])->name('builds.create')->middleware('auth');
//Route::resource('builds', BuildController::class)->middleware('auth');
