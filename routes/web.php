<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController; // Importation du UserController
use Illuminate\Support\Facades\Route;

Route::get('/',[HomeController::class,'home'])->name('app_home');
Route::get('/about',[HomeController::class,'about'])->name('app_about');

Route::match(['get','post'],'/dashboard', [HomeController::class , 'dashboard'])->name('app_dashboard')
->middleware('auth');

Route::get('/logout', [LoginController::class , 'logout'])->name('app_logout');
Route::post('/existEmail', [LoginController::class , 'existEmail'])->name('app_existEmail');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::match(['get','post'],'/login', [LoginController::class , 'login'])->name('app_login'); // Décommenté
Route::get('/builds', [HomeController::class, 'builds'])->name('app_builds'); // Ajout de la route pour les builds
Route::get('/profile', [UserController::class, 'profile'])->name('app_profile'); // Ajout de la route pour le profil
Route::middleware('web')->group(function () {
    // Vos routes ici...
});

/*Route::match(['get','post'],'/register', [LoginController::class , 'register'])->name(('app_register'));*/
