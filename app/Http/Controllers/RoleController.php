<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:assign_roles', only: ['show','assign','remove_member']),
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
        return back()->with('success', 'Role created successfully');
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
        // return $role->users;
        return view('roles.assign_role')->with([
            'title' => 'Role - '.$role->name,
            'role' => $role->id,
            'users' => User::doesntHave('roles')->where('is_admin', true)->get(),
            'role_users' => $role->users
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
        if($request->permissions[0] != null){
            $role->update(['name' => Str::title($request->name)]);
            if($request->has('all')){
                $permissions = Permission::all();
            } else{
                $permissions = json_decode($request->permissions,true);
            }
            $role->syncPermissions($permissions);
            return redirect()->route('roles.show',$role->id);
        }
        return back()->with(['error', 'Permissions Not Allowed']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function assign($id,Request $request){
        $request->validate([
            'member' => 'required'
        ]);
        $role = Role::find($id);
        $user = User::find($id);

        if($user && $role){
            $user->assignRole($role);
            return back()->with('success' , 'User has been assigned');
        } else{
            return back()->with('error' , 'Error');
        }
    }

    public function remove_member($member){
        $user = User::find($member);
        $role = $user->getRoleNames()[0];
        if($user && $role){
            $user->removeRole($role);
            return back()->with('success' , 'User has been removed');
        } else{
            return back()->with('error' , 'Error');
        }
    }
}