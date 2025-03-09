<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
    return view('auth.register'); // Assure-toi que cette vue existe : resources/views/auth/register.blade.php
    }

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
