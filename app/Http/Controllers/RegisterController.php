<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * Contrôleur pour gérer l'inscription des utilisateurs.
 *
 * @group Inscription
 * 
 * Ce contrôleur affiche le formulaire d'inscription et gère la création d'un nouvel utilisateur.
 */
class RegisterController extends Controller
{
    /**
     * Affiche le formulaire d'inscription.
     *
     * @response 200 Vue du formulaire d'inscription.
     */
    public function showRegisterForm()
    {
        return view('auth.register'); // Assure-toi que cette vue existe : resources/views/auth/register.blade.php
    }

    /**
     * Traite l'inscription d'un nouvel utilisateur.
     *
     * @bodyParam Username string required Nom d'utilisateur (lettres uniquement).
     * @bodyParam email string required Adresse email unique.
     * @bodyParam password string required Mot de passe (min 12 caractères, majuscule, minuscule, chiffre, confirmation).
     * 
     * @response 302 Redirection vers la page d'accueil après inscription réussie.
     * @response 422 Validation échouée.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Username' => ['required', 'string', 'regex:/^[a-zA-Z]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:12', 'regex:/[A-Z]/', 'regex:/[a-z]/', 'regex:/[0-9]/', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check if the email already exists
        if (User::where('email', $request->email)->exists()) {
            return back()->withErrors(['email' => 'Cet email est déjà utilisé.'])->withInput();
        }
        
        // Create the user
        $user = User::create([
            'name' => $request->Username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Log the user in after registration
        Auth::login($user);

        // Redirect to home
        return redirect()->route('app_home');
    }
}
