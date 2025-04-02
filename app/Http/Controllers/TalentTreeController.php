<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TalentTreeController extends Controller
{
    private $namespace = 'static-11.1.0_59095-us';
    private $locale = 'fr_FR';
    private $baseUrl = 'https://us.api.blizzard.com';

    private function getAccessToken()
    {
        $url = 'https://oauth.battle.net/token';
        $response = Http::asForm()->post($url, [
            'grant_type' => 'client_credentials',
            'client_id' => env('BLIZZARD_CLIENT_ID'),
            'client_secret' => env('BLIZZARD_CLIENT_SECRET'),
        ]);
        return $response->json()['access_token'] ?? null;
    }

    public function fetchTalentTree($specId)
    {
        set_time_limit(60);
    
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return response()->json(['error' => 'Token Blizzard manquant'], 500);
        }
    
        $specResponse = Http::withToken($accessToken)->get("{$this->baseUrl}/data/wow/playable-specialization/{$specId}", [
            'namespace' => $this->namespace,
            'locale' => $this->locale,
        ]);
    
        if ($specResponse->failed()) {
            return response()->json(['error' => 'Erreur lors de la récupération de la spécialisation'], 500);
        }
    
        $json = $specResponse->json();
        $href = $json['spec_talent_tree']['key']['href'] ?? null;
        if (!$href || !preg_match('/talent-tree\/(\d+)/', $href, $matches)) {
            return response()->json(['error' => 'Aucun treeId trouvé pour cette spécialisation.'], 404);
        }
    
        $treeId = $matches[1];
    
        $treeResponse = Http::withToken($accessToken)->get("{$this->baseUrl}/data/wow/talent-tree/{$treeId}/playable-specialization/{$specId}", [
            'namespace' => $this->namespace,
            'locale' => $this->locale,
        ]);
    
        if ($treeResponse->failed()) {
            return response()->json(['error' => 'Erreur lors de la récupération de l\'arbre'], 500);
        }
    
        $tree = $treeResponse->json();
    
        $nodes = array_merge(
            $tree['class_talent_nodes'] ?? [],
            $tree['spec_talent_nodes'] ?? []
        );
    
        $talents = [];
    
        foreach ($nodes as $node) {
            $ranks = $node['ranks'][0]['tooltip'] ?? null;
        
            // Vérification que les données existent bien
            if (!$ranks || !isset($ranks['spell_tooltip'])) {
                continue; // on saute ce talent si les données sont incomplètes
            }
        
            $tooltip = $ranks['spell_tooltip'];
            $spell = $tooltip['spell'] ?? null;
        
            $talents[] = [
                'id' => $node['id'],
                'name' => $spell['name'] ?? 'Nom inconnu',
                'description' => $tooltip['description'] ?? '',
                'icon' => isset($spell['id']) ? "https://render.worldofwarcraft.com/us/icons/56/{$spell['id']}.jpg" : '',
                'row' => $node['display_row'] ?? 0,
                'column' => $node['display_col'] ?? 0,
            ];
        }
        
    
        return response()->json($talents);
    }
    
}
