<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index()
    {
        return view('auth.index');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (auth()->attempt($request->only('email', 'password'))) {
            return redirect()->intended('/dashboard');
        }

        return redirect()->back()->with('error', 'Invalid credentials');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function logout()
    {
        auth()->logout();
        return redirect('/login');
    }
}