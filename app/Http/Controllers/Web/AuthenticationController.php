<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
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
        $request->validate([
            'name' =>['required', 'max:30', 'string','regex:/^[\pL\s]+$/u'],
            'email' => ['required', 'email', 'max:30', 'unique:users'],
            'password' => ['required', 'max:250', 'min:6', 'confirmed'],
        ]);
        User::create([
            'name' => Str::title($request->name),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => true
        ]);

        return redirect()->route('login')->with('success', 'Registration successful. You can now login.');
    }

    public function authenticate(Request $request){
        $user = User::where('email', $request->email)->first();
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if(auth()->attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'is_admin' => true])){
            $request->session()->regenerate();
            $user->update(['last_login' => now()]);

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
