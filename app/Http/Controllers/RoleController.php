<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use App\Notifications\AssignPermissionNotif;
use Illuminate\Support\Facades\Notification;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:assign_roles', only: ['show','assign','remove_member','assign_permis']),
            new Middleware('role:Super Admin', only: ['create','store','edit','destroy','update']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('roles.index')->with([
            'title' => 'Role',
        ]);
    }

    public function load_role($role){
        $role = Role::find($role);
        $users = $role->users;
        return DataTables::of($users)
        ->addIndexColumn()
        ->addColumn('profile', function($users){
            if ($users->profile_image)
                return '<img class="rounded-circle" style="width: 50px;height:50px" src="'. asset('storage/' . $users->profile_image).' "> </td>';
            else
                return '<img class="rounded-circle" style="width: 50px;height:50px" src="https://ui-avatars.com/api/?name='. $users->name .'&color=7F9CF5&background=EBF4FF"> </td>';
        })
        ->addColumn('action', function($users){
            return '<a href="'.route('users.detail', $users->id) .'" class="btn btn-outline-info mr-2" style="font-size:1rem"><i class="fa-solid fa-eye"></i>Detail</a>
                      <form action="'.route('roles.remove-member', $users->id) .'" method="post">
                          '.method_field('DELETE').'
                          '.csrf_field().'
                          <button type="submit" class="btn btn-outline-danger" onclick="return confirm(\''.__('general.alert_delete').'\');" style="font-size:1rem"><i class="fa-solid fa-trash"></i>'. __('general.delete').' </button></form>';
        })
        ->rawColumns(['action','profile'])
        ->make(true);
    }
    public function load_all(){
        $roles = Role::query();
        return DataTables::of($roles)
        ->filter(function ($query) {
            $query->where('name', '!=', 'Super Admin');
        })
        ->addIndexColumn()
        ->addColumn('action', function($roles){
            return '<a href="'.route('roles.show', $roles->id) .'" class="btn btn-outline-info mr-2" style="font-size:1rem"><i class="fa-solid fa-eye"></i>Detail</a>';
        })
        ->addColumn('member',function($roles){
            return $roles->users()->count();
        })
        ->rawColumns(['action','profile'])
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('roles.create')->with([
            'title' => 'Create Role',
            'permissions' => collect()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255','regex:/^[\pL\s]+$/u', 'unique:roles']
        ]);
        if($request->permissions[0] != null){
            if($request->has('all')){
                $permissions = Permission::all();
            } else{
                $permissions = json_decode($request->permissions,true);
            }
        }
        $role = Role::create(['name' => Str::title($request->name)]);
        $role->givePermissionTo($permissions);
        return back()->with('success', __('roles.alert.create_success'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if($id == 1){
            abort(403);
        }
        $role = Role::find($id);
        return view('roles.assign_role')->with([
            'title' => 'Role - '.$role->name,
            'role' => $role->id,
            'users' => User::doesntHave('roles')->where('is_admin', true)->get(),
            'role_users' => $role->users,
            'permissions' => $role->permissions->map(function($permission){
                return $permission->name;
            })
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::find($id);
        return view('roles.edit')->with([
            'title' => 'Edit Role - '.$role->name,
            'role' => $role,
            'permissions' => $role->permissions->map(function($permission){
                return $permission->name;
            })
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::find($id);
        $request->validate([
            'name' => ['required', 'max:100', 'regex:/^[\pL\s]+$/u'],
            'permissions' => 'required',
        ]);
        // return dd($request);
        if($request->has('all')){
            $permissions = Permission::all();
        } else{
            if(empty(json_decode($request->permissions,true))){
                return back()->with('error', __('roler.alert.edit_failed'));
            } else {
                $permissions = json_decode($request->permissions,true);
            }
        }
        $role->update(['name' => Str::title($request->name)]);
        $role->syncPermissions($permissions);
        return redirect()->route('roles.show',$role->id)->with('success', __('roles.alert.edit_success'));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);
        if($role){
            $role->delete();
            return redirect()->route('roles.index')->with([
                'success' => __('roles.alert.remove_success')
            ]);
        } else{
            return redirect()->route('roles.index')->with([
                'error' => __('roles.alert.remove_failed')
            ]);
        }
    }

    public function assign($id,Request $request){
        $request->validate([
            'member' => 'required'
        ]);
        $role = Role::find($id);
        $user = User::find($request->member);

        if($user && $role){
            $user->assignRole($role);
            return back()->with('success' , __('roles.alert.assign_success'));
        } else{
            return back()->with('error' , __('roles.alert.assign_failed'));
        }
    }
    public function assign_permis(Request $request){
        if($request->ajax()) {
            $request->validate([
                'user_id' => ['required','exists:users,id'],
                'data_permis' =>'required'
            ]);
            $user = User::findOrFail($request->user_id);
            $assign_permission = Auth::user();
            $super_admin = User::where('email', 'support@gonemaul.my.id')->orWhere('id', 1)->get();
            $permissions = json_decode($request->data_permis,true);
            if(empty($permissions)) {
                $permissions = $user->getPermissionsViaRoles();
                $user->syncPermissions($permissions);
                if($assign_permission->id != 1){
                    Notification::send($super_admin,new AssignPermissionNotif($assign_permission,$user));
                }
                return response()->json(['success' => true, 'pesan' => __('roles.alert.reset_permis'), 'permis' => $user->getAllPermissions()->pluck('name')], 200);
            } else{
                $user->syncPermissions($permissions);
                if($assign_permission->id != 1){
                    Notification::send($super_admin,new AssignPermissionNotif($assign_permission,$user));
                }
                return response()->json(['success' => true, 'pesan' => __('roles.alert.assign_permis'), 'permis' => $user->getAllPermissions()->pluck('name')], 200);
            }
        }
    }

    public function remove_member($member){
        $user = User::find($member);
        $role = $user->getRoleNames()[0];
        if($user && $role){
            $user->removeRole($role);
            return back()->with('success' , __('roles.alert.revoke_success'));
        } else{
            return back()->with('error' , __('roles.alert.revoke_failed'));
        }
    }
}