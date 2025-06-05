<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Import the Storage facade
use Illuminate\Support\Facades\Validator;

/**
 * Contrôleur pour gérer le profil utilisateur.
 *
 * @group Profil Utilisateur
 * 
 * Ce contrôleur permet d'afficher, modifier et mettre à jour le profil de l'utilisateur connecté.
 * Toutes les actions nécessitent une authentification.
 */
class ProfileController extends Controller
{
    /**
     * Affiche le profil de l'utilisateur connecté.
     *
     * @authenticated
     * @response 200 {
     *  "user": "Détails de l'utilisateur",
     *  "activities": "Activités récentes de l'utilisateur"
     * }
     */
    public function show()
    {
        $user = Auth::user();
        $activities = $user->activities ?? []; // Ensure activities is an array
        return view('profile.show', compact('user', 'activities'));
    }

    /**
     * Affiche le formulaire d'édition du profil.
     *
     * @authenticated
     * @response 200 {
     *  "user": "Détails de l'utilisateur à éditer"
     * }
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Met à jour le profil de l'utilisateur.
     *
     * @authenticated
     * @bodyParam name string required Nom complet de l'utilisateur.
     * @bodyParam email string required Adresse email unique.
     * @bodyParam profile_picture file Image de profil (jpeg, png, max 2MB).
     * 
     * @response 302 Redirection vers la page de profil avec message de succès.
     * @response 422 Validation échouée.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'profile_picture' => 'nullable|image|mimes:jpeg,png|max:2048', // Validate the image
        ]);

        if ($validator->fails()) {
            return redirect()->route('profile.edit')
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['name', 'email']);

        if ($request->hasFile('profile_picture')) {
            // Delete the old profile picture if exists
            if ($user->profile_picture) {
                Storage::delete($user->profile_picture);
            }

            // Store the new profile picture
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $data['profile_picture'] = $path;
        }

        $user->update($data);

        return redirect()->route('profile.show')->with('success', 'Profil mis à jour avec succès.');
    }
}
