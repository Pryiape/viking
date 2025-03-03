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
    public function home()
    {    
        $blizzardController = new BlizzardController();
        $data = $blizzardController->fetchGameData(); // Fetch class data
        $classes = $data['classes'] ?? []; // Extract classes from the data
        // ✅ Récupération des spécialisations avec SpecializationController
        $specializationController = new SpecializationController();
        $specializationData = $specializationController->getAllSpecializations();

        return view('home.home', [
            'data' => $data,
            'specializationData' => $specializationData->getData(),
            'classes' => $classes // Pass classes to the view
        ]);
    }

    //la page / vue dashboard.blade.php
    public function dashboard()
    {
        return view('home.dashboard');
    }
}
