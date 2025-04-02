<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TalentTreeController;




// Route pour récupérer la liste des classes WoW
Route::get('/classes', [TalentTreeController::class, 'getClasses']);
Route::get('/talent-tree/{specId}', [TalentTreeController::class, 'fetchTalentTree']);


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
