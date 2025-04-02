<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Build;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BuildController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user(); 
        $myBuilds = Build::where('user_id', $user->id)->latest()->get();
        return view('builds.index', compact('myBuilds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('builds.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

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
        ]);
        
        return redirect()->route('builds.index')->with('success', 'Build créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Build $build)
    {
        $this->authorize('view', $build);
        return view('builds.show', compact('build'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Build $build)
    {
        $this->authorize('update', $build);
        return view('builds.edit', compact('build'));
    }

    /**
     * Update the specified resource in storage.
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
        ]);

        return redirect()->route('builds.index')->with('success', 'Build mis à jour avec succès!');
    }

    /**
     * Remove the specified resource from storage.
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
     * Display a listing of the app builds.
     */
    public function appBuilds()
    {
        // Logic to retrieve and display app builds can be added here.
        return view('builds.app_builds'); // Assuming there is a view for app builds.
    }
}