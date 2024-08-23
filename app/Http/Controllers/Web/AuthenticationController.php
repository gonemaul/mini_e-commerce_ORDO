<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Notifications\NewUser;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
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
        return redirect()->route('verification.notice')->with('success', 'Verification link sent successfully, Please check your inbox for a verification email.');
    }

    public function authenticate(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
        $user = User::where('email', $request->email)->first();
        $remember = $request->has('remember');
        if(!$user->hasVerifiedEmail()){
            return back()->with(['error' => 'Email not verified!, Please verify your email']);
        } else {
            if(Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'is_admin' => true],$remember)){
                $request->session()->regenerate();
                $user->update(['last_login' => now()]);

                return redirect()->route('dashboard');
            }
            return back()->with(['error' => 'Your provided credentials do not match in our records!!'])->onlyInput('email');
        }

    }

    public function verify(){
        if(Auth::user()->email_verified_at == null){
            return view('auth.verification')->with('title', 'Email verification');
        }
        else{
            return redirect()->route('dashboard')->with('success', 'Email already verified');
        }
    }

    public function verifyHandler(EmailVerificationRequest $request){
        if (!$request->hasValidSignature()) {
            return redirect()->route('verification.notice')->with('error', 'Invalid/Expired url provided.');
        }
        $user = $request->user();
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard')->with('status', 'Email already verified.');
        }

        $request->fulfill();

        return redirect()->route('dashboard')->with('status', 'Email verified successfully.');
    }

    public function verifySend(Request $request){
        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent successfully, Please check your inbox for a verification email.');
    }

    public function forgotPWverify(){
        return view('auth.forgot-password')->with('title', 'Forgot Password Verification');
    }

    public function forgotPWsend(Request $request){
        $request->validate(['email' => 'required|email|exists:users']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['success' => __($status)])
                    : back()->withErrors(['error' => __($status)]);
    }

    public function forgotPWreset(string $token){
        return view('auth.reset-password')->with(['title' => 'Reset Password', 'token' => $token]);
    }

    public function resetPassword(Request $request){
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // return dd($request);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        // return $status;
        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('success', __($status))
                    : back()->withErrors(['error' => [__($status)]]);
    }

    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerate();
        $request->session()->forget('remember_me');

        return redirect()->route('login')->withSuccess('You have successfully logged in!');
    }
}