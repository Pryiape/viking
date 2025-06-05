<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SpecializationController extends Controller
{
    protected $allSpecializations = []; 

    /**
     * Récupère toutes les spécialisations de l'API Blizzard et les classe selon leur ID.
     */
    public function getAllSpecializations()
    {
        try {
            $accessToken = $this->getBlizzardAccessToken();
            if (!$accessToken) {
                return response()->json(['error' => 'Impossible de récupérer le token d\'accès.'], 500);
            }

            $url = 'https://us.api.blizzard.com/data/wow/playable-specialization/index';
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken
            ])->get($url, [
                'namespace' => 'static-us',
                'locale' => 'fr_FR'
            ]);

            if ($response->failed()) {
                return response()->json(['error' => 'Erreur API Blizzard', 'details' => $response->body()], 500);
            }

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
     * Récupère un token d'accès Blizzard.
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
     * Récupère les spécialisations associées à un ID de classe donné et retourne `id` et `name`.
     */
    public function getSpecializationsByClass($classId)
    {
        $classSpecializations = [
            1 => [71, 72, 73],       // Guerrier
            2 => [65, 66, 70],       // Paladin
            3 => [253, 254, 255],    // Chasseur
            4 => [259, 260, 261],    // Voleur
            5 => [256, 257, 258],    // Prêtre
            6 => [250, 251, 252],    // Chevalier de la mort
            7 => [262, 263, 264],    // Chaman
            8 => [62, 63, 64],       // Mage
            9 => [265, 266, 267],    // Démoniste
            10 => [268, 269, 270],   // Moine
            11 => [102, 103, 104, 105], // Druide
            12 => [577, 581],        // Chasseur de démons
            13 => [1467, 1468, 1473],// Évocateur
        ];
    
        $specNames = [
            71 => 'Armes', 72 => 'Fureur', 73 => 'Protection',
            65 => 'Sacré', 66 => 'Protection', 70 => 'Vindicte',
            253 => 'Maîtrise des bêtes', 254 => 'Précision', 255 => 'Survie',
            259 => 'Assassinat', 260 => 'Hors-la-loi', 261 => 'Finesse',
            256 => 'Discipline', 257 => 'Sacré', 258 => 'Ombre',
            250 => 'Sang', 251 => 'Givre', 252 => 'Impie',
            262 => 'Élémentaire', 263 => 'Amélioration', 264 => 'Restauration',
            62 => 'Arcanes', 63 => 'Feu', 64 => 'Givre',
            265 => 'Affliction', 266 => 'Démonologie', 267 => 'Destruction',
            268 => 'Maître brasseur', 269 => 'Marche-vent', 270 => 'Tisse-brume',
            577 => 'Dévastation', 581 => 'Vengeance',
            1467 => 'Dévastation', 1468 => 'Préservation', 1473 => 'Augmentation',
            102 => 'Équilibre', 103 => 'Farouche', 104 => 'Gardien', 105 => 'Restauration',
        ];
    
        if (!isset($classSpecializations[$classId])) {
            return response()->json(['error' => 'Classe inconnue.'], 404);
        }
    
        $specializationData = [];
        foreach ($classSpecializations[$classId] as $specId) {
            if (isset($specNames[$specId])) {
                $specializationData[] = [
                    'id' => $specId,
                    'name' => $specNames[$specId]
                ];
            } else {
                $specializationData[] = [
                    'id' => $specId,
                    'name' => "Inconnu ({$specId})"
                ];
            }
        }
    
        return response()->json($specializationData);
    }
}
