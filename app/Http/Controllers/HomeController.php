<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    //la page / vue home.blade.php
    /**
     * Récupère les données de jeu et les spécialisations, puis passe les données à la vue home.
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

        return view('home.home', [
            'data' => $data,
            'specializationData' => $specializationData->getData(),
            'classes' => $classes, // Pass classes to the view
            'talentData' => $talentData // Pass talent data to the view
        ]);
    }

    //la page / vue dashboard.blade.php
    /**
     * Retourne la vue du tableau de bord.
     */
    public function dashboard()
    {
        return view('home.dashboard');
    }
}
