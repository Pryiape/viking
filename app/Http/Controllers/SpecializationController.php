<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SpecializationController extends Controller
{
    protected $allSpecializations = []; // Déclaration de la propriété

    /**
     * Récupère toutes les spécialisations de l'API Blizzard et les classe selon leur ID.
     */
    public function getAllSpecializations()
    {
        try {
            // Récupération du token Blizzard
            $accessToken = $this->getBlizzardAccessToken();
            if (!$accessToken) {
                return response()->json(['error' => 'Impossible de récupérer le token d\'accès.'], 500);
            }

            // Requête à l'API Blizzard
            $url = 'https://us.api.blizzard.com/data/wow/playable-specialization/index';
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken
            ])->get($url, [
                'namespace' => 'static-us',
                'locale' => 'fr_FR'  // Utiliser la langue souhaitée
            ]);

            if ($response->failed()) {
                return response()->json(['error' => 'Erreur API Blizzard', 'details' => $response->body()], 500);
            }

            // Vérifie si on a bien reçu des spécialisations
            $this->allSpecializations = $response->json()['character_specializations'] ?? [];
            if (empty($this->allSpecializations)) {
                return response()->json(['error' => 'Aucune spécialisation trouvée.'], 404);
            }

            return response()->json($this->allSpecializations, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur serveur.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Récupère un token d'accès Blizzard en utilisant les identifiants du client.
     */
    private function getBlizzardAccessToken()
    {
        $url = 'https://oauth.battle.net/token';

        $response = Http::asForm()->post($url, [
            'grant_type' => 'client_credentials',
            'client_id' => env('BLIZZARD_CLIENT_ID'),
            'client_secret' => env('BLIZZARD_CLIENT_SECRET'),
        ]);

        if ($response->failed()) {
            return null;
        }

        return $response->json()['access_token'];
    }

    /**
     * Récupère les spécialisations associées à un ID de classe donné et retourne les noms des spécialisations.
     */
    public function getSpecializationsByClass($classId)
    {
        $classSpecializations = [
            1 => [71, 72, 73], // Guerrier
            2 => [65, 66, 70], // Paladin
            3 => [253, 254, 255], // Chasseur
            4 => [259, 260, 261], // Voleur
            5 => [256, 257, 258], // Prêtre
            6 => [250, 251, 252], // DK
            7 => [262, 263, 264], // Chaman
            8 => [62, 63, 64], // Mage
            9 => [265, 266, 267], // Démoniste
            10 => [268, 269, 270], // Moine
            11 => [102, 103, 104, 105], // Druide
            12 => [577, 581], // DH
            13 => [1467, 1468, 1473], // Evocateur
        ];
    
        $specNames = [
            71 => 'Arms', 72 => 'Fury', 73 => 'Protection',
            65 => 'Holy', 66 => 'Protection', 70 => 'Retribution',
            253 => 'Beast Mastery', 254 => 'Marksmanship', 255 => 'Survival',
            259 => 'Assassination', 260 => 'Outlaw', 261 => 'Subtlety',
            256 => 'Discipline', 257 => 'Holy', 258 => 'Shadow',
            250 => 'Blood', 251 => 'Frost', 252 => 'Unholy',
            262 => 'Elemental', 263 => 'Enhancement', 264 => 'Restoration',
            62 => 'Arcane', 63 => 'Fire', 64 => 'Frost',
            265 => 'Affliction', 266 => 'Demonology', 267 => 'Destruction',
            268 => 'Brewmaster', 269 => 'Windwalker', 270 => 'Mistweaver',
            577 => 'Havoc', 581 => 'Vengeance',
            1467 => 'Devastation', 1468 => 'Preservation', 1473 => 'Augmentation',
            102 => 'Balance', 103 => 'Feral', 104 => 'Guardian', 105 => 'Restoration',
        ];
    
        // Vérifie si la classe existe
        if (!isset($classSpecializations[$classId])) {
            return response()->json(['error' => 'Classe inconnue.'], 404);
        }
    
        // Récupération des spécialisations correspondantes et conversion en noms
        $specializationNames = [];
        foreach ($classSpecializations[$classId] as $specId) {
            if (isset($specNames[$specId])) {
                $specializationNames[] = $specNames[$specId];
            } else {
                $specializationNames[] = "Inconnu ({$specId})"; // Ajout de l'ID pour debug
            }
        }
    
        return response()->json($specializationNames);
    }
}
