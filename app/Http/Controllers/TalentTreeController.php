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
        if ($response->failed()) {
            // Afficher une erreur si la récupération du jeton échoue
            return null;
        }
        return $response->json()['access_token'] ?? null;
    }

    private function getSpellMediaUrl($spellId, $accessToken)
    {
        $url = "{$this->baseUrl}/data/wow/media/spell/{$spellId}";
        $response = Http::withToken($accessToken)->get($url, [
            'namespace' => $this->namespace,
            'locale' => $this->locale,
        ]);

        if ($response->failed()) {
            return null;
        }

        $media = $response->json();
        return $media['assets'][0]['value'] ?? null;
    }

    public function fetchTalentTree($specId)
    {
        // Augmenter le temps d'exécution maximal à 120 secondes
        set_time_limit(120);

        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return response()->json(['error' => 'Token Blizzard manquant ou invalide'], 500);
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

            // Utiliser l'ID du sort pour récupérer l'URL de l'image
            $spellId = $spell['id'] ?? null;
            $iconUrl = $spellId ? $this->getSpellMediaUrl($spellId, $accessToken) : '';

            $talents[] = [
                'id' => $node['id'],
                'name' => $spell['name'] ?? 'Nom inconnu',
                'description' => $tooltip['description'] ?? '',
                'icon' => $iconUrl,
                'row' => $node['display_row'] ?? 0,
                'column' => $node['display_col'] ?? 0,
            ];
        }

        return response()->json($talents);
    }
}