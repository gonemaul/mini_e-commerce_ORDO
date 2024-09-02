<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\Order;
use App\Models\Session;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use App\Exports\AdminExport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Exports\CustomerExport;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Notifications\Auth\EmailVerifyNotification;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:user_export', only: ['export','export_customer']),
            new Middleware('permission:user_detail', only: ['user_detail']),
            new Middleware('permission:user_view|user_detail|user_export', only: ['list_users','load_data']),
        ];
    }
    public function profile(){
        $auth = Auth::user();
        $sessions = Session::where('user_id', $auth->id)->get();
        foreach ($sessions as $session) {
            $agent = new Agent();
            $agent->setUserAgent($session->user_agent);

            $session->device = $agent->device(); // Dapatkan nama perangkat
            $session->platform = $agent->platform(); // Dapatkan platform (OS)
            $session->browser = $agent->browser(); // Dapatkan nama browser
        }
        return view('users.profile')->with([
            'title' => 'Profile',
            'user' => $auth,
            'sessions' => $sessions,
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
                'success' => __('auth.update_profile_email_success')
            ]);
        }
        return redirect()->route('dashboard')->with(['success' => __('auth.update_profile_success')]);
    }

    public function change_password(Request $request){
        $user = User::findOrFail($request->user()->id);

        $request->validate([
            'current_password' => 'required',
            'password' => ['required','min:6','confirmed'],
        ]);

        if (!(Hash::check($request->get('current_password'), $user->password))) {
            return back()->with(["error" => __('auth.update_password_failed')]);
        }

        $user->update([
            'password' =>  Hash::make($request->get('password'))
        ]);;

        return back()->with(["success" => __('auth.update_password_success')]);
    }

    public function delete_account(Request $request){
        if($request->ajax()) {
            $user = User::findOrFail($request->user()->id);
            if (!(Hash::check($request->get('password'), $user->password))) {
                return response()->json(['status' => 'error', "pesan" => __('auth.password')]);
            }
            if($user->id == 1 && $user->hasRole('Super Admin')){
                return response()->json(['status' =>  'error', 'pesan'=> __('auth.delete_account.failed') ]);
            }
            if($user->profile_image){
                Storage::delete($user->profile_image);
            }
            $user->sessions()->delete();
            $user->delete();
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return response()->json([
                'status' => 'success',
                'pesan' => __('auth.delete_account.success')
            ]);
        }

    }

    public function logout_other(Request $request){
        if($request->ajax()){
            $currentSessionId = session()->getId();
            $user = Auth::user();
            if (!(Hash::check($request->get('password'), $user->password))) {
                return response()->json(['status' => 'error', "pesan" => __('auth.password')]);
            }
            $sesi = Session::where('user_id', $user->id)->where('id', '<>', $currentSessionId)->delete();
            return response()->json(['status' => 'success', 'pesan' => __('auth.logout_other')]);
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
            if (Gate::allows('user_detail')) {
            return '<a href="'.route('users.detail', $users->id) .'" class="btn btn-outline-info" style="font-size:1rem"><i class="fa-solid fa-eye"></i>Detail</a>';
            }
        })
        ->addColumn('role', function($users){
            if($users->is_admin)
                return '<label class="badge '. ($users->hasRole('Super Admin') ? 'badge-primary' : 'badge-outline-primary') .'">Web</label>';
            else
                return '<label class="badge badge-outline-warning">Api</label>';
        })
        ->rawColumns(['action','profile','role'])
        ->make(true);
    }

    public function user_detail(User $user){
        $permissions = $user->getAllPermissions();
        $lastActivitySession = Session::where('user_id',$user->id)->orderBy('last_activity', 'desc')->first();
        if ($lastActivitySession) {
            $lastActivity = Carbon::createFromTimestamp($lastActivitySession->last_activity)->toDateTimeString();
        } else {
            $lastActivity = 'No activity found';
        }
        return view('users.detail')->with([
            'title' => 'User Detail',
            'user' => $user,
            'orders' => Order::where('user_id', $user->id)->with('orderItems')->get(),
            'permissions' => $permissions->map(function($permission){
                return $permission->name;
            }),
            'last_activity' => $lastActivity
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