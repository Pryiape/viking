<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BlizzardController extends Controller
{
    public function getAccessToken()
    {
        $client_id = env('BLIZZARD_CLIENT_ID');
        $client_secret = env('BLIZZARD_CLIENT_SECRET');

        $response = Http::asForm()->post(config('services.blizzard.api_url'), [
            'grant_type' => 'client_credentials',
            'client_id' => $client_id,
            'client_secret' => $client_secret,
        ]);

        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        return null;
    }

    public function fetchGameData()
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return response()->json(['error' => 'Impossible de récupérer le token d\'accès.'], 500);
        }

        $response = Http::withToken($accessToken)->get(config('services.blizzard.data_api_url') . '/data/wow/playable-class/index?namespace=static-us&locale=fr_FR');

        if ($response->failed()) {
            return response()->json(['error' => 'Échec de récupération des données de Blizzard.'], 500);
        }

        return response()->json($response->json());
    }
}
