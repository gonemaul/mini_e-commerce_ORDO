<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Notifications\NewUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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
        $new_user = User::create([
            'name' => Str::title($request->name),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => true
        ]);
        Auth::login($new_user);
        $admin = User::where('is_admin', true)->where('id', '!=', $new_user->id)->get();
        if($admin){
            Notification::send($admin, new NewUser($new_user));
        }
        event(new Registered($new_user));
        return redirect()->route('verifyForm')->with('success', 'Registration successful. You can now login.');
    }

    public function authenticate(Request $request){
        $user = User::where('email', $request->email)->first();
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
        $remember = $request->has('remember');
        if(Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'is_admin' => true],$remember)){
            $request->session()->regenerate();
            $user->update(['last_login' => now()]);

            return redirect()->route('dashboard');
        }

        return back()->with(['error' => 'Your provided credentials do not match in our records!!'])->onlyInput('email');
    }

    public function verify(){
        return view('auth.login')->with('title', 'Email verification');
    }

    public function verifyHandler(EmailVerificationRequest $request){
        $request->fulfill();

        return dd($request);
    }

    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerate();
        $request->session()->forget('remember_me');

        return redirect()->route('login')->withSuccess('You have successfully logged in!');
    }
}