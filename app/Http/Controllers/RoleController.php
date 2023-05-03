<?php
/**
 * Created by PhpStorm.
 * User: James
 * Date: 12/7/17
 * Time: 2:07 PM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\RolesDataTable;
use Auth;
//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Response;
use Session;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    public function __construct() {
//        $this->middleware(['auth', 'isAdmin']);//isAdmin middleware lets only users with a //specific permission permission to access these resources
    }

    /**
     * Display a listing of the resource.
     * @param \App\DataTables\RolesDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function index(RolesDataTable $dataTable) {
        return $dataTable->render('admin.roles.index');
//    public function index() {
//        $roles = Role::all();//Get all roles
//
//        return view('admin.roles.index')->with('roles', $roles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $permissions = Permission::all();//Get all permissions

        return view('admin.roles.create', ['permissions'=>$permissions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function store(Request $request) {
        //Validate name and permissions field
//        $this->validate($request, [
//                'name'=>'required|unique:roles|max:10',
//                'permissions' =>'required',
//            ]
//        );

        if ($request->ajax()) {
            $newRole = $request->get('role');
            $name = $newRole['name'];
            $desc = $newRole['desc'];
            $type = $newRole['type'];
            $guard = $newRole['guard'];
            $permissions = $newRole['perms'];
        } else {
            $name = $request['name'];
            $desc = $request['description'];
            $type = $request['type'];
            $guard = $request['guard_name'];
            $permissions = $request['permissions'];
        }

        $role = new Role();
        $role->name = $name;
        $role->description = $desc;
        $role->type = $type;
        $role->guard_name = $guard;

        $role->save();

        //Looping thru selected permissions
        foreach ($permissions as $permission) {
            $p = Permission::where('id', '=', $permission)->firstOrFail();
            //Fetch the newly created role and assign permission
            $role = Role::where('name', '=', $name)->first();
            $role->givePermissionTo($p);
        }

        if ($request->ajax()) {
            return Response::JSON(array('status' => 'Success', 'message' => 'Role ' . $role->name . ' added!',
                'id' => $role->id, 'name' => $role->name, 'desc' => $role->description, 'type' => $role->type,
                'guard' => $role->guard_name, 'created' => date($role->created_at), 'updated' => date($role->updated_at),
                'permissions' => str_replace(array('[',']','"'),'', $role->permissions()->pluck('name'))
            ));
        } else {
            alert()->success('Role ' . $role->name . ' added!')->persistent('OK');
            return redirect()->route('roles.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return redirect('roles');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();

        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return mixed
     */
    public function update(Request $request, $id) {
        if ($request->ajax()) {
            $input = $request->get('role');
            $permissions = $request->get('permissions');
        } else {
            $input = $request->except(['permissions']);
            $permissions = $request['permissions'];
        }

        $role = Role::findOrFail($id);//Get role with the given id
        //Validate name and permission fields
//        $this->validate($request, [
//            'name'=>'required|max:10|unique:roles,name,'.$id,
//            'permissions' =>'required',
//        ]);

        $role->fill($input)->save();

        $p_all = Permission::all();//Get all permissions

        foreach ($p_all as $p) {
            $role->revokePermissionTo($p); //Remove all permissions associated with role
        }

        foreach ($permissions as $permission) {
            $p = Permission::where('id', '=', $permission)->firstOrFail(); //Get corresponding form //permission in db
            $role->givePermissionTo($p);  //Assign permission to role
        }

        if ($request->ajax()) {
            return Response::JSON(array('status' => 'Success', 'message' => 'Role ' . $role->name . ' updated!',
                'permissions' => str_replace(array('[',']','"'),'', $role->permissions()->pluck('name')), 'updated' => date($role->updated_at)));
        } else {
            alert()->success('Role ' . $role->name . ' updated!')->persistent('OK');
            return redirect()->route('roles.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        alert()->success('Role ' . $role->name . ' deleted!')->persistent('OK');
        return redirect()->route('roles.index');
    }

    public function getList(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::all(); //Get all roles
            $result = array();
            foreach ($roles as $role) {
                array_push($result, array('id' => $role->id, 'name' => $role->name, 'desc' => $role->description, 'type' => $role->type,
                    'guard' => $role->guard_name, 'created' => date($role->created_at), 'updated' => date($role->updated_at),
                    'permissions' => str_replace(array('[',']','"'),'', $role->permissions()->pluck('name'))));
            }
            return Response::JSON($result);
        }
    }
}