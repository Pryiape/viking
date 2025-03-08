<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TalentController_updated extends Controller
{
    // Existing methods...

    /**
     * Récupère l'arbre de talents pour un ID de spécialisation donné de l'API Blizzard.
     */
    public function getTalentTree($specializationId)

    {
        $url = "https://us.api.blizzard.com/data/wow/talent-tree/774/playable-specialization/{$specializationId}?namespace=static-us&locale=en_US";
        
        $response = Http::get($url);

        if ($response->failed()) {
            return response()->json(['error' => 'Erreur lors de la récupération des talents.'], 500);
        }

        return response()->json($response->json());
    }
}
