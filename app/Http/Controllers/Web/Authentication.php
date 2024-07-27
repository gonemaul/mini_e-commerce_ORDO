<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Authentication extends Controller
{
    public function register(){
        return view('auth.register')->with([
            'title' => 'Register'
        ]);
    }

    public function login(){
        return view('auth.login')->with([
            'title' => 'Login'
        ]);
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'name' =>['required', 'max:30', 'string'],
            'email' => ['required', 'email', 'max:250', 'unique:users'],
            'password' => ['required', 'max:250', 'min:6', 'confirmed'],
        ]);
        $validatedData['is_admin'] = true;
        User::create($validatedData);

        return redirect()->route('login')->with('success', 'Registration successful. You can now login.');
    }

    public function authenticate(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if(auth()->attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'is_admin' => true])){
            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        return back()->with(['error' => 'Your provided credentials do not match in our records!!'])->onlyInput('email');
    }

    public function logout(Request $request){
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerate();

        return redirect()->route('login')->withSuccess('You have successfully logged in!');
    }
}
