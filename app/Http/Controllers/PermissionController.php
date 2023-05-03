<?php
/**
 * Created by PhpStorm.
 * User: James
 * Date: 12/7/17
 * Time: 2:07 PM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\PermissionsDataTable;
use Auth;
//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Response;
use Session;

class PermissionController extends Controller
{
    public function __construct() {
//        $this->middleware(['auth', 'isAdmin']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
    }

    /**
     * Display a listing of the resource.
     * @param \App\DataTables\PermissionsDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function index(PermissionsDataTable $dataTable) {

        return $dataTable->render('admin.permissions.index');
//        $permissions = Permission::all(); //Get all permissions
//
//        return view('permissions.index')->with('permissions', $permissions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $roles = Role::get(); //Get all roles

        return view('admin.permissions.create')->with('roles', $roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
//        $this->validate($request, [
//            'name'=>'required|max:40',
//        ]);

        if ($request->ajax()) {
            $perm = $request->get('permission');
            $name = $perm['name'];
            $desc = $perm['desc'];
            $type = $perm['type'];
            $guard = $perm['guard'];
        } else {
            $name = $request['name'];
            $desc = $request['description'];
            $type = $request['type'];
            $guard = $request['guard_name'];
        }

        $permission = new Permission();
        $permission->name = $name;
        $permission->description = $desc;
        $permission->type = $type;
        $permission->guard_name = $guard;

        $roles = $request['roles'];

        $permission->save();

        if (!empty($request['roles'])) { //If one or more role is selected
            foreach ($roles as $role) {
                $r = Role::where('id', '=', $role)->firstOrFail(); //Match input role to db record

                $permission = Permission::where('name', '=', $name)->first(); //Match input //permission to db record
                $r->givePermissionTo($permission);
            }
        }

        if ($request->ajax()) {
            return Response::JSON(array('status' => 'Success', 'message' => 'Permission ' . $permission->name . ' added!',
                'id' => $permission->id, 'name' => $permission->name, 'desc' => $permission->description, 'type' => $permission->type,
                'guard' => $permission->guard_name, 'created' => date($permission->created_at), 'updated' => date($permission->updated_at)
                ));
        } else {
            alert()->success('Permission ' . $permission->name . ' added!')->persistent('OK');
            return redirect()->route('permissions.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return redirect('permissions');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $permission = Permission::findOrFail($id);

        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        if ($request->ajax()) {
            $input = $request->get('permission');
        } else {
            $input = $request->all();
        }

        $permission = Permission::findOrFail($id);
//        $this->validate($request, [
//            'name'=>'required|max:40',
//        ]);
//        $input = $request->all();
        $permission->fill($input)->save();

        if ($request->ajax()) {
            return Response::JSON(array('status' => 'Success', 'message' => 'Permission ' . $permission->name . ' updated!',
                'updated' => date($permission->updated_at)));
        } else {
            alert()->success('Permission ' . $permission->name . ' updated!')->persistent('OK');
            return redirect()->route('permissions.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $permission = Permission::findOrFail($id);

        //Make it impossible to delete this specific permission
        if ($permission->name == "Administer roles & permissions") {
            return redirect()->route('permissions.index')
                ->with('flash_message',
                    'Cannot delete this Permission!');
        }

        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('flash_message',
                'Permission deleted!');

    }

    public function getList(Request $request)
    {
        if ($request->ajax()) {
            $permissions = Permission::all(); //Get all permissions
            $result = array();
            foreach ($permissions as $permission) {
                array_push($result, array('id' => $permission->id, 'name' => $permission->name, 'desc' => $permission->description, 'type' => $permission->type,
                    'guard' => $permission->guard_name, 'created' => date($permission->created_at), 'updated' => date($permission->updated_at)));
            }
            return Response::JSON($result);
        }
    }

}