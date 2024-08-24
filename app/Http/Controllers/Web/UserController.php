<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Str;
use App\Exports\AdminExport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Exports\CustomerExport;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;
use App\Notifications\emailVerification;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Auth\EmailVerifyNotification;

class UserController extends Controller
{
    public function profile(){
        return view('users.profile')->with([
            'title' => 'Profile',
            'user' => Auth::user()
        ]);
    }

    public function update_profile(Request $request){
        $user = User::findOrFail($request->id);

        $request->validate([
            'name' => ['required','string','max:255','regex:/^[\pL\s]+$/u'],
            'email' => ['required','string','email','max:255'],
            'profile_image' => ['image','max:1024','mimes:jpeg,png,jpg,gif,svg',],
        ],['name.regex' => 'Input hanya boleh mengandung huruf dan spasi...']);

        if ($request->file('profile_image')){
            if($user->profile_image){
                Storage::delete($user->profile_image);
            }
            $user->profile_image = $request->file('profile_image')->store('profile_image','public');
        }
        $user->name = Str::title($request->name);

        $user->save();
        if($request->email !== $user->email){
            $user->email_verified_at = null;
            $user->email = $request->email;
            $user->save();
            Notification::send($user, new EmailVerifyNotification());
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerate();
            $request->session()->forget('remember_me');
            return redirect()->route('verification.notice')->with([
                'email' => $user->email,
                'success' => 'Verification link sent!, Please check your inbox for email verification before logging back in.'
            ]);
        }
        return redirect()->route('dashboard')->with(['success' => "Your profile has been updated successfully"]);
    }

    public function change_password(Request $request){
        $user = User::findOrFail($request->user()->id);

        $request->validate([
            'current_password' => 'required',
            'password' => ['required','min:6','confirmed'],
        ]);

        if (!(Hash::check($request->get('current_password'), $user->password))) {
            return back()->with(["error" => "Your current password does not matches with the password."]);
        }

        $user->update([
            'password' =>  Hash::make($request->get('password'))
        ]);;

        return back()->with(["success" => "Password changed successfully!"]);
    }

    public function delete_account(Request $request){
        $request->validate([
            'password' => 'required'
        ]);

        $user = User::findOrFail($request->user()->id);
        if (!(Hash::check($request->get('password'), $user->password))) {
            return back()->with(["error" => "Password does not match with the your password."]);
        }
        else{
            if($user->profile_image){
                Storage::delete($user->profile_image);
            }
            $user->delete();
            Auth::logout();
            return redirect()->route('login')->with(['success' => 'Your account has been deleted successfully!']);
        }
    }

    public function list_users(){
        return view('users.index')->with([
            'title' => 'User List',
        ]);
    }

    public function load_data(){
        $users = User::all();
        return DataTables::of($users)
        ->addIndexColumn()
        ->addColumn('profile', function($users){
            if ($users->profile_image)
                return '<img class="rounded-circle" style="width: 50px;height:50px" src="'. asset('storage/' . $users->profile_image).' "> </td>';
            else
                return '<img class="rounded-circle" style="width: 50px;height:50px" src="https://ui-avatars.com/api/?name='. $users->name .'&color=7F9CF5&background=EBF4FF"> </td>';
        })
        ->addColumn('action', function($users){
            return '<a href="'.route('users.detail', $users->id) .'" class="btn btn-outline-info" style="font-size:1rem"><i class="fa-solid fa-eye"></i>Detail</a>';
        })
        ->addColumn('role', function($users){
            if($users->is_admin)
                return '<label class="badge '. (Auth::user()->id == $users->id ? 'badge-primary' : 'badge-outline-primary') .'">Admin</label>';
            else
                return '<label class="badge badge-outline-warning">User</label>';
        })
        ->rawColumns(['action','profile','role'])
        ->make(true);
    }

    public function user_detail(User $user){
        return view('users.detail')->with([
            'title' => 'User Detail',
            'user' => $user,
            'orders' => Order::where('user_id', $user->id)->with('orderItems')->get(),
        ]);
    }

    public function export(){
        $name = 'User_' . Carbon::now()->format('Ymd') . rand(10,99) . '.xlsx';
        return Excel::download(new AdminExport(), $name);
    }

    public function export_customer(){
        $name = 'Customer_' . Carbon::now()->format('Ymd') . rand(10,99) . '.xlsx';
        return Excel::download(new CustomerExport(), $name);
    }
}