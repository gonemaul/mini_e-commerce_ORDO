<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function profile(){
        return view('profile')->with([
            'title' => 'Profile',
            'user' => auth()->user()
        ]);
    }

    public function update_profile(Request $request){
        $user = User::findOrFail($request->id);

        $validatedData = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','string','email','max:255'],
            'profile_image' => ['image','max:1024','mimes:jpeg,png,jpg,gif,svg',],
        ]);

        if ($request->file('profile_image')){
            if($user->profile_image){
                Storage::delete($user->profile_image);
            }
            $validatedData['profile_image'] = $request->file('profile_image')->store('profile_image','public');
        }

        $validatedData['name'] = Str::title($request->name);

        $user->update($validatedData);
        return redirect()->route('dashboard')->with(['success' => "Your profile has been updated successfully"]);
    }

    public function change_password(Request $request){
        $user = User::findOrFail(auth()->user()->id);

        if (!(Hash::check($request->get('current_password'), $user->password))) {
            return back()->with(["error" => "Your current password does not matches with the password."]);
        }

        $validatedData = $request->validate([
            'current_password' => 'required',
            'password' => ['required','min:6','confirmed'],
        ]);

        $user->update([
            'password' =>  Hash::make($request->get('password'))
        ]);;

        return redirect()->route('dashboard')->with(["success" => "Password changed successfully!"]);
    }

    public function list_users(){
        return view('users.index')->with([
            'title' => 'User List',
            'users' => User::orderBy('is_admin', 'desc')->get()
        ]);
    }

    public function user_detail(User $user){
        return view('users.detail')->with([
            'title' => 'User Detail',
            'user' => $user
        ]);
    }
}
