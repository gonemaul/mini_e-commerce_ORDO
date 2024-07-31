<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthenticationController extends Controller
{
    public function register(Request $request){
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:250'],
            'email' => ['required', 'email', 'max:250', 'unique:users'],
            'password' => ['required', 'min:6'],
        ]);

        if(!$validatedData){
            return response()->json([
               'status' => 'error',
               'message' => 'Data validation failed',
                'data' => $validatedData,
            ],400);
        }

        User::create([
            'name' => Str::title($request->name),
            'email' => $request->email,
            'password' =>  Hash::make($request->password),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'You have successfully registered',
        ],201);
    }

    public function login(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = User::where('email', $request->email)->first();
            if($user->tokens()) {
                $user->tokens()->delete();
            }
            $token =  $user->createToken('authToken')->plainTextToken;
            $user->update(['last_login' => now()]);
            return response()->json([
                'status' => 'success',
                'message' => 'Login successfully',
                'data' => [
                    'user' => $user,
                    'token' => [
                        'access_token' => $token,
                        'token_type' => 'Bearer',
                        // 'expires_in' => $token->expires_at->diffInSeconds(now())
                    ]
                ]
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials',
            'errors' => [
                'email' => ['The provided email is not registered'],
                'password' => ['The provided password is incorrect']
            ]
        ], 401);
    }

    public function me(Request $request){
        $user = $request->user();
        return response()->json([
            'status' => 'success',
            'account' => [
                'name' => $user->name,
                'email' => $user->email,
                'profile_image' => url('storage/' . $user->profile_image),
                'last_login' => $user->last_login,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
        ]);
    }

    public function update_me(Request $request){
        $user = User::findOrFail(auth()->user()->id);

        $request->validate([
            'name' => ['string', 'max:250'],
            'email' => ['email','max:250','unique:users'],
            'password' => 'min:8',
            'profile_image' => ['image|file|max:1024'],
        ]);

        if($request->name){
            $user->name = Str::title($request->name);
        }

        if($request->new_password && (Hash::check($request->new_password, $user->password))){
            return response()->json([
                'status' =>'error',
                'message' => 'The new password is the same as the old password'
            ],403);
        }
        else{
            $user->password = Hash::make($request->new_password);
        }

        if ($request->file('profile_image')){
            if($user->profile_image){
                Storage::delete($user->profile_image);
            }
            $user->profile_image = $request->file('profile_image')->store('profile_image', 'public');
        }

        $user->save();
        return response()->json([
           'status' =>'success',
            'message' => 'The profile was updated successfully'
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
