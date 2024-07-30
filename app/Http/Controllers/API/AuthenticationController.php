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
               'message' => 'data not valid',
                'data' => $validatedData,
            ]);
        }

        User::create([
            'name' => Str::title($request->name),
            'email' => $request->email,
            'password' =>  Hash::make($request->password),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'You have successfully registered',
        ]);
    }

    public function login(Request $request){
        $validatedData = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt(['email' => $validatedData['email'], 'password' => $validatedData['password']])) {
            $user = User::where('email', $request->email)->first();

            if($user->tokens()) {
                $user->tokens()->delete();
            }
            $token =  $user->createToken('authToken')->plainTextToken;
            $user->update(['last_login' => now()]);
            return response()->json([
                'status' => 'success',
                'message' => 'Login successfully',
                'token' => $token,
                'type' => 'Bearer'
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'The provided credentials do not match our records'
        ]);
    }

    public function me(Request $request){
        $user = $request->user();
        return response()->json([
            'user' => $user,
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
                'status' =>'failed',
                'message' => 'The new password is the same as the old password'
            ]);
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
        ]);
        }

    public function logout(Request $request){
        $request->user()->tokens()->delete();

        return response()->json([
           'message' => 'You have successfully logged out!'
        ]);
    }
}
