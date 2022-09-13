<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;

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

    public function login(LoginRequest $request)
    {
        if (auth()->attempt($request->only('email', 'password'))) {
            return redirect()->intended('/dashboard');
        }

        return redirect()->route('login')->with('error', 'Invalid credentials');
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
