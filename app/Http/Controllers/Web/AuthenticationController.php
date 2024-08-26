<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\EmailVerify;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Notifications\NewUser;
use Illuminate\Support\Carbon;
use \Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Auth\EmailVerifyNotification;
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
        $admin = User::where('is_admin', true)->where('id', '!=', $new_user->id)->get();
        if($admin){
            Notification::send($admin, new NewUser($new_user));
        }
        Notification::send($new_user, new EmailVerifyNotification());
        return redirect()->route('verification.notice')->with([
            'email' => $new_user->email,
            'success' => 'You have successfully registered, Please check your inbox for a verification email.'
        ]);
    }

    public function authenticate(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
        $user = User::where('email', $request->email)->first();
        $remember = $request->has('remember');
        if($user){
            if(!$user->hasVerifiedEmail()){
                return back()->with(['error' => 'Account not actived, Please verify your email, <a href="'.route('verification.email', $request->email).'">Verify</a>']);
            } else {
                if(Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'is_admin' => true],$remember)){
                    $request->session()->regenerate();
                    $user->update(['last_login' => now()]);

                    return redirect()->route('dashboard');
                }
                return back()->with(['error' => 'Your provided credentials do not match in our records!!'])->onlyInput('email');
            }
        }
        return back()->with(['error' => 'Your provided credentials do not match in our records!! Please register your account.'])->onlyInput('email');

    }

    public function verify(Request $request ){
        return view('auth.verification')->with([
            'title' => 'Email verification',
        ]);
    }

    public function verifyHandler($id,$hash,Request $request ){
        $verify = EmailVerify::where('token', $hash)->first();
        if(!$verify){
            return 'Your verification is invalid';
        }
        try {
            $user = User::where('id', $id)->where('email', $verify->email)->first();

            if(Carbon::now()->greaterThan($verify->created_at->addMinutes(10))){
                $verify->delete();
                return 'Verification link expired.';
            }

            if($user->hasVerifiedEmail()){
                if($request->is('api/*')){
                    return response()->json([
                       'status' => 'error',
                       'message' => 'Email already verified.'
                    ], 500);
                } else{
                    return redirect()->route('login')->with([
                        'success' => 'Email already verified'
                    ]);
                }
            }

            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }
            $verify->delete();

            if(!$user->is_admin) {
                return response()->json([
                   'status' =>'success',
                   'message' => 'Email verified successfully. Your account has been actived.'
                ], 200);
            } else{
                return redirect()->route('login')->with([
                   'success' => 'Email verified successfully. Your account has been actived.'
                ]);
            }
        } catch (\Exception $e){
            Log::error($e->getMessage());
            return 'Error verifying email.';
        }
    }

    public function verifySend(Request $request){
        $request->validate([
            'email' => ['required', 'email', 'exists:users']
        ],['email.exists' => 'Invalid email address']);
        $user = User::where('email', $request->email)->first();
        if($user->hasVerifiedEmail()){
            return redirect()->route('login')->with([
                'success' => 'Email already verified'
            ]);
        }

        Notification::send($user, new EmailVerifyNotification());

        return redirect()->route('verification.notice')->with([
            'email' => $user->email,
            'success' => 'Verification link sent successfully, Please check your inbox for a verification email.'
        ]);
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

    public function forgotPWreset(Request $request,string $token){
        $user = User::where('email',$request->email)->first();
        if($user->is_admin){
           return view('auth.reset-password')->with(['title' => 'Reset Password', 'token' => $token]);
        } else{
            return response()->json([
                'message' => 'Please enter token for reset password...',
                'token' => $token,
            ]);
        }
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