<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    protected $request;

    function __construct(Request $request)
    {
        $this->request = $request;
    }



    public function showLoginForm()
    {
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
            // Authentication passed...
            return redirect()->intended('/');
        }

        // If authentication fails, throw a validation exception
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
    public function existEmail()
    {
        $email= $this->request-> input('Email');
       $user = User::where('email', $email)->first();
       $response ="";
         ($user) ? $response ="exist" : $response ="not_exist";
        return response()->json([
            'code'=> 200,
            'response'=> $response
        ]);

    }
}
