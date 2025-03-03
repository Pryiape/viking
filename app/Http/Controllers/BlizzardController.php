<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Psy\Readline\Hoa\Console;

class BlizzardController extends Controller
{
    public function getAccessToken()
    {
        $client_id = env('BLIZZARD_CLIENT_ID');
        $client_secret = env('BLIZZARD_CLIENT_SECRET');

        $response = Http::asForm()->post('https://oauth.battle.net/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $client_id,
            'client_secret' => $client_secret,
        ]);
        
        echo '<script>console.log("requete ok"); </script>';
        if ($response->successful()) {
            echo '<script>console.log('.$response.'); </script>';
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

        $response = Http::withToken($accessToken)->get('https://us.api.blizzard.com/data/wow/playable-class/index?namespace=static-us&locale=en_US');
        echo '<script>console.log('.$response.'); </script>';

        if ($response->failed()) {
            return response()->json(['error' => 'Échec de récupération des données de Blizzard.'], 500);
        }

        return $response->json(); // Return the data directly instead of wrapping it in a JsonResponse
        // Ensure the data structure is correct for classes


    }
    
}
