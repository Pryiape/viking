<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/',[HomeController::class,'home'])->name('app_home');
Route::get('/about',[HomeController::class,'about'])->name('app_about');

Route::match(['get','post'],'/dashboard', [HomeController::class , 'dashboard'])->name(('app_dashboard'))
->middleware('auth')
->name('app_dashboard');

Route::get('/logout', [LoginController::class , 'logout'])->name(('app_logout'));
Route::post('/existEmail', [LoginController::class , 'existEmail'])->name(('app_existEmail'));
/*Route::match(['get','post'],'/login', [LoginController::class , 'login'])->name(('app_login'));
Route::match(['get','post'],'/register', [LoginController::class , 'register'])->name(('app_register'));*/
