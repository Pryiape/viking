<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Récupère l'utilisateur authentifié et retourne la vue du profil avec les données de l'utilisateur.
     */
    public function profile()

    {
        // Récupérer l'utilisateur authentifié
        $user = Auth::user();

        // Retourner la vue du profil avec les données de l'utilisateur
        return view('user.profile', compact('user'));
    }
}
