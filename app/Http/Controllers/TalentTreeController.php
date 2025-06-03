<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TalentTreeController extends Controller
{
    private $namespace = 'static-eu';
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
        return $response->successful() ? $response->json()['access_token'] : null;
    }

    private function getSpellMediaUrl($spellId, $accessToken)
    {
        // Check if the image is already cached in the database.
        $cachedImage = DB::table('talent_images')->where('spell_id', $spellId)->first();

        if ($cachedImage) {
            return $cachedImage->image_url;
        }

        // If not, fetch it from the API.
        $url = "{$this->baseUrl}/data/wow/media/spell/{$spellId}";
        $response = Http::withToken($accessToken)->get($url, [
            'namespace' => $this->namespace,
            'locale' => $this->locale,
        ]);

        if ($response->successful()) {
            $media = $response->json();
            foreach ($media['assets'] ?? [] as $asset) {
                if ($asset['key'] === 'icon') {
                    // Cache the image URL in the database.
                    DB::table('talent_images')->updateOrInsert(
                        ['spell_id' => $spellId],
                        [
                            'image_url' => $asset['value'],
                            'updated_at' => now(),
                            'created_at' => now(),
                        ]
                    );
                    return $asset['value'];
                }
            }
            return $media['assets'][0]['value'] ?? null;
        }

        return null;
    }

    public function fetchTalentTree($specId)
    {
        try {
            set_time_limit(120);
    
            $accessToken = $this->getAccessToken();
            if (!$accessToken) {
                \Log::error("❌ Token Blizzard manquant");
                return response()->json(['error' => 'Token Blizzard manquant'], 500);
            }
    
            // Récupération de la spécialisation
            $specDataResponse = Http::withToken($accessToken)->get("{$this->baseUrl}/data/wow/playable-specialization/{$specId}", [
                'namespace' => $this->namespace,
                'locale' => $this->locale,
            ]);
    
            if ($specDataResponse->failed()) {
                \Log::error("❌ Erreur récupération spécialisation pour specId {$specId}", [
                    'status' => $specDataResponse->status(),
                    'response' => $specDataResponse->body()
                ]);
                return response()->json(['error' => 'Erreur récupération spécialisation'], 500);
            }
    
            $specData = $specDataResponse->json();
            $treeHref = $specData['spec_talent_tree']['key']['href'] ?? null;
    
            if (!$treeHref || !preg_match('/talent-tree\/(\d+)/', $treeHref, $match)) {
                \Log::error("❌ Aucun treeId trouvé pour specId {$specId}", [
                    'specData' => $specData
                ]);
                return response()->json(['error' => 'Aucun treeId trouvé.'], 404);
            }
    
            $treeId = $match[1];
    
            // Récupération de l'arbre de talents
            $treeResponse = Http::withToken($accessToken)->get("{$this->baseUrl}/data/wow/talent-tree/{$treeId}/playable-specialization/{$specId}", [
                'namespace' => $this->namespace,
                'locale' => $this->locale,
            ]);
    
            if ($treeResponse->failed()) {
                \Log::error("❌ Échec récupération talent tree pour specId {$specId}, treeId {$treeId}", [
                    'status' => $treeResponse->status(),
                    'response' => $treeResponse->body()
                ]);
                return response()->json(['error' => 'Erreur récupération arbre de talents'], 500);
            }
    
            $treeJson = $treeResponse->json();
            $nodes = array_merge(
                $treeJson['class_talent_nodes'] ?? [],
                $treeJson['spec_talent_nodes'] ?? []
            );
    
            $talents = [];
    
            foreach ($nodes as $node) {
                $entry = [
                    'id' => $node['id'],
                    'row' => $node['display_row'] ?? 0,
                    'column' => $node['display_col'] ?? 0,
                    'requires' => array_column($node['requirements'] ?? [], 'required_node_id'),
                    'name' => '',
                    'description' => '',
                    'icon' => '',
                    'choices' => []
                ];
    
                // CHOIX multiples
                if ($node['node_type']['type'] === 'CHOICE' && isset($node['ranks'])) {
                    foreach ($node['ranks'] as $rank) {
                        $tooltip = $rank['tooltip']['spell_tooltip'] ?? null;
                        $spell = $tooltip['spell'] ?? null;
                        $spellId = $spell['id'] ?? null;
                        $icon = $spellId ? $this->getSpellMediaUrl($spellId, $accessToken) : '';
                        if ($spell && $tooltip) {
                            $entry['choices'][] = [
                                'name' => $spell['name'] ?? '',
                                'description' => $tooltip['description'] ?? '',
                                'icon' => $icon
                            ];
                        }
                    }
                } elseif (!empty($node['ranks'])) {
                    // RANGS simples
                    foreach ($node['ranks'] as $rank) {
                        $tooltip = $rank['tooltip']['spell_tooltip'] ?? null;
                        $spell = $tooltip['spell'] ?? null;
                        if ($spell && $tooltip) {
                            $spellId = $spell['id'] ?? null;
                            $entry['name'] = $spell['name'] ?? '';
                            $entry['description'] = $tooltip['description'] ?? '';
                            $entry['icon'] = $spellId ? $this->getSpellMediaUrl($spellId, $accessToken) : '';
                            break;
                        }
                    }
                }
    
                $talents[] = $entry;
            }
    
            \Log::info("✅ Talent tree récupéré pour specId {$specId} (total: " . count($talents) . " talents)");
    
            return response()->json($talents);
    
        } catch (\Throwable $e) {
            \Log::error("🔥 Exception dans fetchTalentTree pour specId {$specId} : " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Erreur interne serveur'], 500);
        }
    }
    
}