<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Contrôleur pour gérer les actions liées à l'utilisateur.
 *
 * @group Utilisateur
 * 
 * Ce contrôleur gère l'affichage du profil de l'utilisateur authentifié.
 */
class UserController extends Controller
{
    /**
     * Affiche le profil de l'utilisateur authentifié.
     *
     * @authenticated
     * @response 200 Vue du profil utilisateur avec les données.
     */
    public function profile()
    {
        // Récupérer l'utilisateur authentifié
        $user = Auth::user();

        // Retourner la vue du profil avec les données de l'utilisateur
        return view('user.profile', compact('user'));
    }
}
