<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('app_home');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/');
        }

        throw ValidationException::withMessages([
            'email' => [__('auth.failed')],
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function existEmail(Request $request)
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->first();
        $response = $user ? 'exist' : 'not_exist';

        return response()->json([
            'code' => 200,
            'response' => $response,
        ]);
    }
}
