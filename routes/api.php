<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TalentController;
use App\Http\Controllers\BlizzardController;





// Route pour récupérer la liste des classes WoW
Route::get('/classes', [TalentController::class, 'getClasses']);
Route::get('/talents/{class}', [TalentController::class, 'getTalents']);// Route d'authentification utilisateur (exemple de base Laravel)
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Ces routes sont chargées par RouteServiceProvider et sont assignées au
| groupe middleware "api". Elles sont conçues pour les échanges JSON.
|
*/
// Route pour récupérer les talents d'une classe

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
