<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Notifications\NewUser;
use Illuminate\Support\Facades\Bus;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Auth\EmailVerifyNotification;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class AuthenticationController extends Controller
{
    public function register(Request $request){
        $validatedData = $request->validate([
            'name' =>['required', 'max:30', 'string','regex:/^[\pL\s]+$/u'],
            'email' => ['required', 'email', 'max:30', 'unique:users'],
            'password' => ['required', 'max:250', 'min:6'],
        ]);

        if(!$validatedData){
            return response()->json([
               'status' => 'error',
               'message' => 'Data validation failed',
                'data' => $validatedData,
            ],400);
        }

        $new_user = User::create([
            'name' => Str::title($request->name),
            'email' => $request->email,
            'password' =>  Hash::make($request->password),
            'is_admin' => false
        ]);

        $admin = User::where('is_admin', true)->get();
        if($admin){
            Notification::send($admin, new NewUser($new_user));
        }
        Notification::send($new_user, new EmailVerifyNotification());
        return response()->json([
            'status' => 'success',
            'message' => 'You have successfully registered, Please check your inbox for a verification email.',
        ],201);
    }

    public function verifySend(Request $request){
        $request->validate(['email' => 'required|exists:users|email'
        ],['email.exists' => 'Invalid email address']);
        $user = User::where('email', $request->email)->first();
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email already verified.'
            ], 500);
        }

        Notification::send($user, new EmailVerifyNotification());

        return response()->json([
            'status' => 'success',
            'message' => 'Verification link sent successfully, Please check your inbox for a verification email.'
        ], 200);
    }

    public function forgot_request(Request $request){
        $request->validate(['email' => 'required|email|exists:users']);
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if($status === Password::RESET_LINK_SENT){
            return response()->json([
               'status' =>'success',
               'message' => 'Reset password link sent to your email',
            ], 200);
        } else{
            return response()->json([
               'status' => 'error',
               'message' => 'Failed to send reset password link'
            ], 500);
        }
    }

    public function forgot_reset(Request $request){
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
                if($user->tokens()) {
                    $user->tokens()->delete();
                }
            }
        );
        if($status === Password::PASSWORD_RESET){

            return response()->json([
               'status' =>'success',
               'message' => 'Your password reset was successful',
            ], 200);
        } else{
            return response()->json([
               'status' => 'error',
               'message' => $status
            ], 500);
        }
    }

    public function login(Request $request){
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        $user = User::where('email', $request->email)->first();
        if($user && Hash::check($request->password, $user->password)){
            if (!$user->hasVerifiedEmail()) {
                return response()->json([
                   'status' => 'error',
                   'message' => 'Account not actived, Please verify your email.',
                ], 401);
            }else{
                if($user->tokens()) {
                    $user->tokens()->delete();
                }
                $token =  $user->createToken('authToken')->plainTextToken;
                $user->update(['last_login' => now()]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login successfully',
                    'data' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'profile_image' => $user->profile_image ? url('storage/' . $user->profile_image) : 'No Profile Image',
                        'last_login' => $user->last_login,
                        'created_at' => $user->created_at,
                        'token' => [
                            'access_token' => $token,
                            'token_type' => 'Bearer',
                        ]
                    ]
                ], 200);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials',
                'errors' => [
                    'email' => ['The provided email is not registered'],
                    'password' => ['The provided password is incorrect']
                ]
            ], 401);
        }
    }

    public function me(Request $request){
        $user = $request->user();
        return response()->json([
            'status' => 'success',
            'account' => [
                'name' => $user->name,
                'email' => $user->email,
                'profile_image' => $user->profile_image ? url('storage/' . $user->profile_image) : 'No Profile Image',
                'last_login' => $user->last_login,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
        ]);
    }

    public function update_me(Request $request){
        $user = User::findOrFail($request->user()->id);

        $request->validate([
            'name' => ['string', 'max:250','regex:/^[\pL\s]+$/u'],
            'email' => ['email','max:250','unique:users'],
            'password' => 'min:8',
            'profile_image' => ['image|file|max:1024|mimes:jpeg,png,jpg,gif,svg'],
        ]);

        if($request->name){
            $user->name = Str::title($request->name);
        }

        if($request->password){
            if(Hash::check($request->password, $user->password)){
                return response()->json([
                    'status' =>'error',
                    'message' => 'The new password is the same as the old password'
                ],403);
            } else{
                $user->password = Hash::make($request->password);
            }
        }

        if ($request->file('profile_image')){
            if($user->profile_image){
                Storage::delete($user->profile_image);
            }
            $user->profile_image = $request->file('profile_image')->store('profile_image', 'public');
        }

        $user->save();
        if($request->email && $request->email !== $user->email){
            $user->email_verified_at = null;
            $user->email = $request->email;
            $user->tokens()->delete();
            $user->save();
            Notification::send($user, new EmailVerifyNotification());
            return response()->json([
                'status' => 'success',
                'message' => 'Your profile has changed successfully, Please check your inbox for email verification before logging back in.'
            ],200);
        }
        return response()->json([
           'status' =>'success',
            'message' => 'Your profile has changed successfully'
        ],201);
        }

    public function logout(Request $request){
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'You have successfully logged out!'
        ],200);
    }
}