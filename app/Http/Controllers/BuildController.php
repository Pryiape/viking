<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Build;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class BuildController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $builds = Build::where('user_id', Auth::id())->get();
        return view('builds.index', compact('builds'));
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
        $description = $request->description;
        $description .= " " . now()->format('d/m/Y'); // Always append the date

        if (empty($description)) {
            $description = "pas d'information " . now()->format('d/m/Y');
        }

        $request->validate([         
            /**'name' => 'required|string|regex:/^.{5,100}$/', */

            'name' => 'required|string|regex:/^.{5,255}$/',
            'description' => 'nullable|string'
        ]);

        $name = $request->name;

        if (strlen($name) > 100) {
            $description .= substr($name, 100); // Transfer excess characters to description
            $name = substr($name, 0, 100); // Keep only the first 100 characters in name
        }

        Build::create([
            'name' => $name,
            'description' => $description
        ]);

        return redirect()->route('builds.index')->with('success', 'Build créé avec succès!');
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
            'name' => 'required|string|regex:/^.{5,255}$/',
            'description' => 'nullable|string'
        ]);

        $name = $request->name;
        $description = $request->description;

        if (strlen($name) > 100) {
            $description .= substr($name, 100); // Transfer excess characters to description
            $name = substr($name, 0, 100); // Keep only the first 100 characters in name
        }

        $build->update([
            'name' => $name,
            'description' => $description . " " . now()->format('d/m/Y') // Always append the date
        ]);

        return redirect()->route('builds.index')->with('success', 'Build mis à jour avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Build $build)
    {       
        $this->authorize('delete', $build);
        $build->delete();       
        return redirect()->route('builds.index')->with('success', 'Build supprimé avec succès!');
    }
}
