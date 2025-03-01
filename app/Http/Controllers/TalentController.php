<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TalentController extends Controller
{
    protected $blizzardController;

    public function __construct(BlizzardController $blizzardController)
    {
        $this->blizzardController = $blizzardController;
    }

    public function getClasses()
    {
        $token = $this->blizzardController->getAccessToken();
        if (!$token) {
            return response()->json(['error' => 'Impossible de récupérer le token d\'accès.'], 500);
        }

        $response = Http::withHeaders([
            'Authorization' => "Bearer $token",
        ])->get("https://us.api.blizzard.com/data/wow/playable-class/index?namespace=static-eu&locale=fr_FR");

        if ($response->failed()) {
            return response()->json(['error' => 'Échec de récupération des classes WoW.'], 500);
        }

        return response()->json($response->json()['classes']);
    }

    public function getTalents($class)
    {
        $token = $this->blizzardController->getAccessToken();
        if (!$token) {
            return response()->json(['error' => 'Impossible de récupérer le token d\'accès.'], 500);
        }

        $response = Http::withHeaders([
            'Authorization' => "Bearer $token",
        ])->get("https://us.api.blizzard.com/data/wow/playable-class/{$class}/talent-tree?namespace=static-eu&locale=fr_FR");

        if ($response->failed()) {
            return response()->json(['error' => 'Impossible de récupérer les talents.'], 500);
        }

        return response()->json($response->json());
    }
}
