<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 12/11/17
 * Time: 3:21 PM
 */

namespace App\Http\Controllers;

use App\User, App\Role, App\Group, App\LabActivity;
use App\UserGroupRole;
use App\DataTables\UsersDataTable;
use App\UserProfile;
use Response;
use Adldap\Laravel\Facades\Adldap;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller {

    //protected $currentuser;

    public function __construct()
    {
     //   $this->middleware(['auth', 'isAdmin']); //middleware
    //    $this->currentuser = Auth::user();
    }

    /**
     * Display a listing of the resource.
     * @param \App\DataTables\UsersDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('admin.users.index');
        //        $users = User::all();
//        return view('users.index')->with('users', $users);
    }

    public function timeline()
    {
        return view('admin.users.timeline')->with('userid',0);
    }

    public function timelinewithid($id)
    {
        return view('admin.users.timeline')->with('userid',$id);
    }


    public function getTimelinebyUser(Request $request){
        if ($request->ajax()) {
            $userid = $request->get('userid');
            if($userid==0){
                $userid =Auth::user()->id;
            }
            $user = User::findOrFail($userid);
            $events=LabActivity::where('causer_id', '=', $userid)->orderBy('created_at','asc')->get();
            $result = array();
            if ($events !== null){
                foreach ($events as $event) {

                    array_push($result,array('id' => $userid, 'email' => $user->email,'time'=>$event->created_at->timestamp,'type'=>$event->log_name,'desc'=>$event->description));
//                    array_push($result, array('id' => $userid, 'email' => $user->email, 'institute' => $user->institute,
//                        'activated' => date($user->activated_at), 'last_login' => date($user->last_login), 'last_activity' => date($user->last_activity),
//                        'enabled' => $user->banned, 'created' => date($user->created_at), 'updated' => date($user->updated_at),
//                        'roles' => str_replace(array('[',']','"'),'', $user->roles()->pluck('name'))));
                }
            }
            return Response::JSON($result);
        }
    }

    public function create()
    {
        $roles = Role::where('type', '=', 'system')->get();
        $groups = Group::where('enabled', '=', '1')->get();
        return view('admin.users.create', ['roles'=>$roles, 'groups'=>$groups]);
    }

    public function store(Request $request)
    {
        alert()->warning('Add User is not ready yet!')->persistent('OK');
        return redirect()->route('usermanagement.index');
//
//        $this->validate($request, [
//            'name'=>'required|max:120',
//            'email'=>'required|email|unique:users',
//            'password'=>'required|min:6|confirmed'
//        ]);
//
//        $user = User::create($request->only('email', 'name', 'password'));
//
//        $roles = $request['roles']; //Retrieving the roles field
//        //Checking if a role was selected
//        if (isset($roles)) {
//            foreach ($roles as $role) {
//                $role_r = Role::where('id', '=', $role)->firstOrFail();
//                $user->assignRole($role_r); //Assigning role to user
//            }
//        }
//        //Redirect to the users.index view and display message
//        alert()->success('User ' . $user->name . ' added!');
//        return redirect()->route('admin.users.index');
    }

    public function show($id)
    {
        return redirect('usermanagement');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::get(); //Get all roles
        return view('admin.users.edit', compact('user', 'roles')); //pass user and roles data to view
    }

    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

//        $this->validate($request, [
//            'name'=>'required|max:120',
//            'email'=>'required|email|unique:users,email,'.$id,
//            'password'=>'required|min:6|confirmed'
//        ]);
//        $input = $request->only(['name', 'email', 'password']);

        if ($request->ajax()) {
            $roles = $request->get('roles');
            $group_roles = $request->get('group_roles');
        } else {
            $roles = $request['roles'];
        }
//        $user->fill($input)->save();

        if (isset($roles)) {
            $user->roles()->sync($roles);
        }
        else {
            $user->roles()->detach();
        }

        $user_group_roles = User::with('usersGroupsRoles.group', 'usersGroupsRoles.role')->find($id);
        if (isset($group_roles)) {
            $user_group_roles->usersGroupsRoles()->delete();
            foreach ($group_roles as $group) {
                foreach ($group['roles'] as $role) {
                    $ug_role = new UserGroupRole();
                    $ug_role->user_id = $id;
                    $ug_role->group_id = $group['gId'];
                    $ug_role->role_id = $role;
                    $ug_role->save();
                }
            }
        } else {
            $user_group_roles->usersGroupsRoles()->delete();
        }

        if ($request->ajax()) {
            return Response::JSON(['status' => 'Success', 'message' => $user->getAttribute('email') . '\'s roles updated!',
                'roles' => str_replace(array('[',']','"'),'', $user->roles()->pluck('name'))]);
        } else {
            return redirect()->route('usermanagement.index')
                ->with('flash_message',
                    'User successfully edited.');
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->get('user_id');
        $user = User::findOrFail($id);
        $groups = UserGroupRole::where('user_id', '=', $id)->get();
        $groupnames = "";
        foreach ($groups as $group) {
            $groupnames .= Group::find($group->group_id)->name;
        }

        if (count($groups) > 0) {
            return Response::json(['status' => 'Failed',
                'message' => 'User ' . $user->email . ' is a member in ' . $groupnames . '<br>Pleas remove him/her from there groups first.']);
        } else {
            $ldap_user = Adldap::search()->where('cn', '=', $user->email)->get()->first();
            $ldap_user->delete();

            $profile = UserProfile::where('user_id', '=', $id);

            $profile->delete();
            $user->delete();
            return Response::json(['status' => 'Success', 'message' => 'User ' . $user->email . ' deleted!']);
        }
    }

    function getUsersTable() {
//        $users = User::select('id', 'name', 'email', 'institute', 'org_id')->get();
        $users = User::select('id', 'name', 'email', 'institute')->get();
        return DataTables::of($users)->addColumn('checkbox', function ($row) { return $row->id; })->make(true);
            //->addColumn('status', function($row) { return $this->getUserStatus($row->getAttribute('id')); })->make(true);
    }

    function getUsersJson() {
        $users = User::select('id', 'name', 'email', 'institute', 'org_id')->get();
        return Response::json($users->toArray());
    }

    public function getList(Request $request)
    {
        if ($request->ajax()) {
            $filters = $request->get('filters');
            $role = $request->get('role');
            if (isset($role) and strlen($role) > 0) {
                $users = User::role($role)->get();
            } else {
                if (isset($filters) and strlen($filters) > 0) {
                    $users = User::where('email', 'like', '%' . $filters . '%')->get();
                } else {
                    $users = User::all(); //Get all users
                }
            }

            $result = array();
            foreach ($users as $user) {
                //$roles = $user->roles()->pluck('name')->implode(',');
                array_push($result, array('id' => $user->id, 'email' => $user->email, 'institute' => $user->institute,
                    'activated' => date($user->activated_at), 'last_login' => date($user->last_login), 'last_activity' => date($user->last_activity),
                    'enabled' => $user->banned, 'created' => date($user->created_at), 'updated' => date($user->updated_at),
                    'roles' => str_replace(array('[',']','"'),'', $user->roles()->pluck('name'))));
            }
            return Response::JSON($result);
        }
    }

    public function getGroups(Request $request, $id)
    {
        if ($request->ajax()) {
            $user = User::findOrFail($id);

            $result = array();
            foreach ($user->groups as $group) {  // need to change to UserGroupRole model
                array_push($result, array('site_id' => $group['site_id'], 'name' => $group['name']));
            }
            return Response::JSON($result);
        }
    }

    public function getProfileRoleGroups(Request $request, $id)
    {
        if ($request->ajax()) {
            $user = User::with('profile', 'usersGroupsRoles.group', 'usersGroupsRoles.role')->find($id);
            $roles = Role::get();
            $groups = array();
            $ugrs = $user->usersGroupsRoles()->select('group_id')->distinct()->get();
            foreach ($ugrs as $ugr) {
                $g_roles = array();
                $site = array();
                $group = $ugr->group;
                if (is_null($group)) {
                    $group['site_id'] = 0;
                    $site['name'] = 0;
                    $group['id'] = $ugr->getAttribute('group_id');
                    $group['name'] = 'Group' . $group['id'] . 'NotExist';
                } else {
                    $site = $group->site()->first();
                }
                $ug_roles = $user->usersGroupsRoles()->where('group_id', '=', $ugr['group_id'])->get();
                foreach ($ug_roles as $ug_role) {
                    //array_push($g_roles, ['rId' => $role->role['id'], 'rName' => $role->role['name']]);
                    array_push($g_roles, $ug_role->getAttribute('role_id'));
                }
                array_push($groups, array('site_id' => $group['site_id'], 'site_name' => $site['name'], 'gid' => $group['id'], 'name' => $group['name'], 'roles' => $g_roles));
            }
            return Response::JSON(['user' => $user, 'roles' => $roles, 'groups' => $groups]);
        }
    }

    public function get(Request $request)
    {
        $user_id = $request->get("uid", 0);
        $user = User::find($user_id);
        return $user;
    }
}