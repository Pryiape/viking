<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TalentController extends Controller
{
    private $apiUrl = 'https://us.api.blizzard.com/data/wow/talent-tree/774/playable-specialization/';
    private $clientId = 'VOTRE_CLIENT_ID'; // Remplacez par votre ID
    private $clientSecret = 'VOTRE_CLIENT_SECRET'; // Remplacez par votre secret

    /**
     * Récupérer le token d'accès pour l'API Blizzard
     */
    public function getAccessToken()
    {
        $response = Http::asForm()->post('https://oauth.battle.net/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ]);

        return $response->json()['access_token'];
    }

    /**
     * Récupérer l'arbre de talents d'une spécialisation spécifique
     */
    public function getTalentTree($specializationId)
    {
        try {
            $accessToken = $this->getBlizzardAccessToken();
            if (!$accessToken) {
                return response()->json(['error' => 'Impossible de récupérer le token d\'accès.'], 500);
            }
    
            // Vérifier et mettre à jour l'ID de l'arbre de talents 
            $talentTreeId = 774; 
    
            // Mise à jour du namespace pour refléter la bonne version
            $namespace = 'static-11.1.0_59095-us';
    
            $url = "https://us.api.blizzard.com/data/wow/talent-tree/{$talentTreeId}/playable-specialization/{$specializationId}?namespace={$namespace}";
    
            $response = Http::withToken($accessToken)->get($url);
    
            if ($response->failed()) {
                return response()->json(['error' => 'Erreur API Blizzard', 'details' => $response->body()], 500);
            }
    
            return response()->json($response->json(), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur serveur.', 'message' => $e->getMessage()], 500);
        }
    }
    
}
