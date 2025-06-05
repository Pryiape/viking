<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

/**
 * Contrôleur pour gérer les pages principales de l'application.
 *
 * @group Pages principales
 * 
 * Ce contrôleur gère l'affichage des vues home, about, index et dashboard.
 */
class HomeController extends Controller
{
    /**
     * Récupère les données de jeu, les spécialisations et les builds publics, puis affiche la vue home.
     *
     * @response 200 Vue de la page d'accueil avec les données nécessaires.
     */
    public function home()
    {    
        $blizzardController = new BlizzardController();
        $data = $blizzardController->fetchGameData(); // Fetch class data
        $classes = $data['classes'] ?? []; // Extract classes from the data
        // ✅ Récupération des spécialisations avec SpecializationController
        $specializationController = new SpecializationController();
        $specializationData = $specializationController->getAllSpecializations();

        // Récupération des talents pour la spécialisation choisie
        $firstSpecializationId = $specializationData->data[0]->id ?? null; // Utiliser l'ID de la première spécialisation
        $talentData = $firstSpecializationId ? $this->getTalentTree($firstSpecializationId) : null;

        // Fetch public builds
        $publicBuilds = \App\Models\Build::where('is_public', true)->latest()->get();
        
        return view('home.home', [
            'publicBuilds' => $publicBuilds,
            'data' => $data,
            'specializationData' => $specializationData->getData(),
            'classes' => $classes, // Pass classes to the view
            'talentData' => $talentData // Pass talent data to the view
        ]);
    }

    /**
     * Affiche la page "À propos".
     *
     * @response 200 Vue de la page about.
     */
    public function about()
    {
        return view('home.about');
    }

    /**
     * Affiche la page d'index avec les builds publics.
     *
     * @response 200 Vue de la page index.
     */
    public function index()
    {
        $publicBuilds = \App\Models\Build::where('is_public', true)->latest()->get();

        return view('home', compact('publicBuilds'));
    }

    /**
     * Affiche la page du tableau de bord.
     *
     * @response 200 Vue du dashboard.
     */
    public function dashboard()
    {
        return view('home.dashboard');
    }
}
