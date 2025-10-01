<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // views
    public function showLogin()
    {
        return view('auth.login');
    }
    public function showRegister()
    {
        
        return view('auth.register');
    }

    // logique
   public function register(Request $request)
    {
        // validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // save to database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        
        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function login(Request $request)
    {
        // validation
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if(Auth::attempt($request->only('email', 'password'))){
            return redirect()->route('dashboard');
        }

          return back()->withErrors(['email' => 'Email ou mot de passe incorrect']);
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

}