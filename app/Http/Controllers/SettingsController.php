<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Contrôleur pour gérer les paramètres de l'application.
 *
 * @group Paramètres
 * 
 * Ce contrôleur affiche la page des paramètres.
 */
class SettingsController extends Controller
{
    /**
     * Affiche la page des paramètres.
     *
     * @authenticated
     * @response 200 Vue de la page des paramètres.
     */
    public function index()
    {
        return view('settings.index');
    }
}
