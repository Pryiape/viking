<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Build;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Contrôleur pour gérer les builds des utilisateurs.
 *
 * @group Builds
 * 
 * Ce contrôleur permet de lister, créer, afficher, modifier et supprimer les builds.
 * Toutes les actions nécessitent une authentification.
 */
class BuildController extends Controller
{
    use AuthorizesRequests;

    /**
     * Affiche la liste des builds de l'utilisateur connecté.
     *
     * @authenticated
     * @response 200 {
     *  "myBuilds": "Liste des builds de l'utilisateur"
     * }
     */
    public function index()
    {
        $user = Auth::user(); 
        $myBuilds = Build::where('user_id', $user->id)->latest()->get();
        return view('builds.index', compact('myBuilds'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau build.
     *
     * @authenticated
     * @response 200 {
     *  "classes": "Liste des classes disponibles pour le build"
     * }
     */
    public function create()
    {
        $blizzardController = new BlizzardController();
        $data = $blizzardController->fetchGameData(); // Fetch class data
        $classes = $data['classes'] ?? []; // Extract classes from the data
        
        return view('builds.create', compact('classes'));
    }

    /**
     * Initialise le middleware d'authentification.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Enregistre un nouveau build en base de données.
     *
     * @authenticated
     * @bodyParam sujet string required Sujet du build. Exemple: "Build DPS"
     * @bodyParam description string required Description détaillée du build.
     * @bodyParam is_public boolean Indique si le build est public.
     * @bodyParam talent_tree json Arbre de talents au format JSON.
     * 
     * @response 302 Redirection vers la liste des builds avec message de succès.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sujet' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
    
        Build::create([
            'user_id' => Auth::id(),
            'sujet' => $request->sujet,
            'description' => $request->description,
            'is_public' => $request->has('is_public'),
            'talent_tree' => $request->input('talent_tree'), // Save talent tree JSON
        ]);
        
        return redirect()->route('builds.index')->with('success', 'Build créé avec succès.');
    }

    /**
     * Affiche un build spécifique.
     *
     * @authenticated
     * @urlParam build int required ID du build.
     * @response 200 {
     *  "build": "Détails du build"
     * }
     */
    public function show(Build $build)
    {
        $this->authorize('view', $build);
        return view('builds.show', compact('build'));
    }

    /**
     * Affiche le formulaire d'édition d'un build.
     *
     * @authenticated
     * @urlParam build int required ID du build.
     * @response 200 {
     *  "build": "Détails du build à éditer"
     * }
     */
    public function edit(Build $build)
    {
        $this->authorize('update', $build);
        return view('builds.edit', compact('build'));
    }

    /**
     * Met à jour un build existant.
     *
     * @authenticated
     * @urlParam build int required ID du build.
     * @bodyParam sujet string required Sujet du build.
     * @bodyParam description string required Description détaillée du build.
     * @bodyParam is_public boolean Indique si le build est public.
     * @bodyParam talent_tree json Arbre de talents au format JSON.
     * 
     * @response 302 Redirection vers la liste des builds avec message de succès.
     */
    public function update(Request $request, Build $build)
    {
        $this->authorize('update', $build);

        $request->validate([
            'sujet' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $build->update([
            'sujet' => $request->sujet,
            'description' => $request->description,
            'is_public' => $request->has('is_public'),
            'talent_tree' => $request->input('talent_tree'), // Update talent tree JSON
        ]);

        return redirect()->route('builds.index')->with('success', 'Build mis à jour avec succès!');
    }

    /**
     * Supprime un build.
     *
     * @authenticated
     * @urlParam id int required ID du build à supprimer.
     * @response 302 Redirection vers la liste des builds avec message de succès.
     * @response 403 Accès refusé si l'utilisateur n'a pas la permission.
     */
    public function destroy($id)
    {
        $build = Build::findOrFail($id);

        $user = Auth::user();

        if (
            $user->id !== $build->user_id &&
            !in_array($user->role, ['admin', 'moderateur'])
        ) {
            abort(403, 'Vous n’avez pas la permission de supprimer ce build.');
        }

        $build->delete();

        return redirect()->route('builds.index')->with('success', 'Build supprimé.');
    }

    /**
     * Affiche la liste des builds de l'application.
     *
     * @authenticated
     * @response 200 Vue de la liste des builds de l'application.
     */
    public function appBuilds()
    {
        // Logic to retrieve and display app builds can be added here.
        return view('builds.app_builds'); // Assuming there is une vue pour les builds de l'application.
    }
}
