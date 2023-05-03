<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 9/21/17
 * Time: 10:41 AM
 */

namespace App\Http\Controllers;

use App, App\Group, App\Site, App\User, App\Role, App\Subgroup;
use App\DataTables\GroupsDataTable;
use Auth, Sentry;
use App\UserGroupRole, App\UserProfile;
use Regulus\ActivityLog\Models\Activity;
use Illuminate\Http\Request;
use Response, Redirect, DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Yajra\DataTables\DataTables;
use App\Traits\CheckUserStatusTrait;

class GroupController extends Controller
{
    use CheckUserStatusTrait;

    protected $user;
    protected $timezone;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                redirect('/');
            }

            $this->user = Auth::user();
            try {
                $this->timezone = UserProfile::findOrFail(Auth::user()->id)->timezone;
            } catch (ModelNotFoundException $e) {
                $this->timezone = 'America/Phoenix';
            }
            //            $this->timezone = UserProfile::where('user_id', '=', Auth::user()->id)->first()->timezone;
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     * @param \App\DataTables\GroupsDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function index(GroupsDataTable $dataTable)
    {
        return $dataTable->render('admin.groups.index');
    }

    public function show($id)
    {
        return Group::find($id);
    }

    function getMembersTable($id)
    {
        $grp = $this->user->usersGroupsRoles()->whereHas('Role', function($query) {
            $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
        })->where('group_id', '=', $id)->get();
        if (count($grp) == 0){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        $ugrs = UserGroupRole::where('group_id', '=', $id);
        $gusers = $ugrs->select('users.id as id', 'users.name as name', 'email', 'institute', 'last_activity', 'last_login', 'last_logout',
            DB::raw("group_concat(roles.name) as role"))
            ->join('users', 'users.id', '=', 'users_groups_roles.user_id')
            ->join('roles', 'roles.id', 'users_groups_roles.role_id')
            ->where('group_id', '=', $id)
            ->groupBy('users.email');
        return DataTables::of($gusers)->addColumn('checkbox', function ($row) { return $row->id; })
            ->editColumn('last_activity', function($row) { return Carbon::parse($row->last_activity)->setTimezone($this->timezone)->toDateTimeString(); })
            ->editColumn('last_login', function($row) { return Carbon::parse($row->last_login)->setTimezone($this->timezone)->toDateTimeString(); })
            ->editColumn('last_logout', function($row) { return Carbon::parse($row->last_logout)->setTimezone($this->timezone)->toDateTimeString(); })
            ->addColumn('status', function($row) { return $this->getUserStatus($row->getAttribute('id')); })->make(true);
    }

    function getMembersJson($id) {
        $grp = $this->user->usersGroupsRoles()->whereHas('Role', function($query) {
            $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
        })->where('group_id', '=', $id)->get();
        if (count($grp) == 0){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        $ugrs = UserGroupRole::where('group_id', '=', $id);
        $gusers = $ugrs->select('users.id as id', 'users.name as name', 'email', 'institute', 'org_id',
            DB::raw("group_concat(roles.name) as role"))
            ->join('users', 'users.id', '=', 'users_groups_roles.user_id')
            ->join('roles', 'roles.id', 'users_groups_roles.role_id')
            ->where('group_id', '=', $id)
            ->groupBy('users.email')->get();
        return Response::json($gusers->toArray());
    }

    function getAvailableRoles() {
        return Role::where('type', '=', 'group')->get();
    }

    public function getListBySite(Request $request, $id)
    {
        if ($request->ajax()) {
            $site = Site::findOrFail($id);
            $groups = $site->groups()->orderBy('updated_at', 'desc')->get();

            $result = array();
            foreach ($groups as $group) {
                $owners = $this->getOwners($group->id);
                $owners_email = array();
                foreach ($owners as $owner) {
                    array_push($owners_email, $owner->user->email);
                }
                array_push($result, array('group' => $group, 'owner' => $owners_email, 'status' => $this->status($group)));
            }
            return Response::JSON(['site' => $site, 'groups' => $result]);
        }
    }

    public function getListByOwner(Request $request)
    {
        if ($request->ajax()) {

            $user = User::findOrFail(Sentry::getUser()->getId());
            $owner_role_id = Role::findByName('group_owner')->id;
            $ur_groups = $user->usersGroupsRoles()->where('role_id', '=', $owner_role_id)->get();
            $result = array();
            foreach ($ur_groups as $ur_group) {
                $group = $ur_group->group;
                $site = $group->site()->first();
                $site = is_null($site) ? 0 : $site;
                $status = $this->status($group);
                $available_rss = ($status == 'Pending' or $status == 'Denied') ? 'Reviewing...' : $group->resource_allocated;
                $expiration = '0000-00-00 00:00:00';
                if (!is_null($group->resource_requested)) {
                    $expiration = ($status == 'Pending' or $status == 'Denied')
                        ? json_decode($group->resource_requested)->expiration
                        : ((strtotime($group->expiration) < 0) ? 'None' : $group->expiration);
                }
                array_push($result, array('group' => $group, 'expiration' => $expiration, 'status' => $status,
                    'site' => $site, 'available_rss' => (is_null($available_rss) ? 'None' : $available_rss)));
            }
            return Response::JSON($result);
        }
    }

    public function getGroupInfo(Request $request, $id)
    {
        if ($request->ajax()) {
            $group = Group::find($id);
            return Response::JSON($group);
        }
    }

    public function getGroupUserById(Request $request, $id)
    {
        if ($request->ajax()) {
            $available_roles = Role::where('type', '=', 'group')->get();
            $group = Group::find($id);
            $ugr_users = $group->usersGroupsRoles()->get()->groupBy('user_id');
            $users = array();
            foreach ($ugr_users as $ugr_user) {
                $user = $ugr_user->first()->user;
                $roles = array();
                foreach ($ugr_user as $u_role) {
                    array_push($roles, $u_role->role);
                }
                array_push($users, ['data' => $user, 'roles' => $roles]);
            }
            return Response::JSON(['group' => $group, 'users' => $users, 'available_roles' => $available_roles]);
        }
    }

    public function status($group)
    {
        if ($group->enabled and $group->approved) {
            return 'Active';
        } elseif ($group->enabled and !$group->approved) {
            return 'Pending';
        } elseif (!$group->enabled and $group->approved) {
            return 'Disabled';
        } else {
            return 'Denied';
        }
    }

    public function getGroupAvailableRoles(Request $request)
    {
        $roles = Role::where('type', '=', 'group')->get();
        return Response::JSON($roles);
    }

    public function getOwners($group_id)
    {
        $role_id = Role::findByName('group_owner')->id;
        $owners = UserGroupRole::where('group_id', '=', $group_id)->where('role_id', '=', $role_id)->get();
        return $owners;
    }

    public function create()
    {
        $sites = Site::all();
        return view('admin.groups.create', ['sites' => $sites]);
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->get('group');
            (empty(json_decode($input['resource_requested'], true))) ? $input['approved'] = '1' : $input['approved'] = '0';
        } else {
            $rules = [
//                'name'            =>  'required|min:5|max:35|alpha_dash|unique:groups',
                'name'            =>  'required|min:5|max:35|alpha_dash',
            ];

            $rules = array_merge($rules, array('lab' => 'numeric', 'vms' => 'numeric', 'vcpu' => 'numeric',
                'ram' => 'numeric', 'storage' => 'numeric', 'expiration' => 'date'));

            $input = $request->all();
            if (isset($input['resource_requested'])) {

                $input['approved'] = '1';
                $input['resource_requested'] = Response::JSON(
                    array('labs' => $input['lab'], 'vms' => $input['vms'], 'vcpus' => $input['vcpus'],
                          'ram' => $input['ram'], 'storage' => $input['storage'], 'expiration' => $input['expiration'])
                );
            }
            $this->validate($request,$rules);
        }
        //$input['expiration'] = '0000-00-00 00:00:00';
        //$input['site_id'] = '1';

        $group = new Group;
        try {
            $group->fill($input)->save();
            $ug_role = new UserGroupRole();
            $ug_role->user_id = Auth::user()->id;
            $ug_role->group_id = $group->id;
            $ug_role->role_id = Role::findByName('group_owner')->id;
            $ug_role->save();

            if ($request->ajax()) {
                return Response::JSON(array('status' => 'Success',
                    'message' => 'New group application is submitted.<br>Please check status in My Groups.',
                    'id' => $group->id, 'name' => $group->name, 'description' => $group->description,
                    'private' => $group->private, 'resource_requested' => $group->resource_requested,
                    'created' => date($group->created_at), 'updated' => date($group->updated_at)
                ));
            } else {
                alert()->success('Group ' . $group->name . ' created.', 'Create New Group')->persistent('OK');
                return redirect()->route('groups.index');
                    //->with('flash_message', 'Site' . $group->name . ' added!');
            }
        } catch (QueryException $e) {
            $sites = Site::all();
            if ($request->ajax()) {
                return Response::JSON(array('status' => 'Failed', 'message' => $e->errorInfo[2]));
            } else {
                alert()->error('Group ' . $group->name . ' creation error! ' . $e->errorInfo[2], 'Create New Group')->persistent('OK');
                return view('admin.groups.create', ['sites' => $sites])->withErrors([$e->errorInfo[2]]);
            }
        }
    }

    public function update(Request $request)
    {
        $input = $request->get('group');
        $message = '';
        $grp = $this->user->usersGroupsRoles()->whereHas('Role', function($query) {
            $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
        })->where('group_id', '=', $input['id'])->get();
        if (count($grp) == 0){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        try {
            $group = Group::findOrFail($input['id']);
        } catch (ModelNotFoundException $e) {
            return Response::JSON(['status' => 'Failed', 'message' => 'Group ' . $input['name'] . ' cannot be found!']);
        }
        $user = User::find(Auth::user()->id);
        $isSiteAdmin = $user->siteAdmins()->where('site_id', '=', $group->site->id)->get();
        $isOwner = $user->usersGroupsRoles()->where('group_id', '=', $group->getAttribute('id'))->get();
        $allowUpdate = (!empty($isOwner) and (in_array($isOwner->first()->role_id, [5, 6, 9]))) ? true : false;

        if (empty($isSiteAdmin) and $allowUpdate) {
            return Response::JSON(['status' => 'Failed', 'message' => 'You have no permission to update group ' . $input['name'] . '!']);
        }

        if (isset($input['private'])) $group->private = intval($input['private']);
        if (isset($input['description'])) $group->description = $input['description'];

        try {
            $group->save();
        } catch (QueryException $e) {
            return Response::JSON(['status' => 'Failed', 'message' => $e->errorInfo[2]]);
        }
        return Response::JSON(['status' => 'Success', 'message' => 'Group ' . $input['name'] . ' updated.']);

    }

//    public function update(Request $request)
//    {
//        $input = $request->get('group');
//        $message = '';
//
//        try {
//            $group = Group::findOrFail($input['id']);
//        } catch (ModelNotFoundException $e) {
//            return Response::JSON(['status' => 'Failed', 'message' => 'Group ' . $input['name'] . ' cannot be found!']);
//        }
//        $user = User::find(Sentry::getUser()->getId());
//        $isSiteAdmin = $user->sites()->where('site_id', '=', $group->site->id)->get();
//        $isOwner = $user->usersGroupsRoles()->where('group_id', '=', $group->getAttribute('id'))->get();
//        $allowUpdate = (!empty($isOwner) and (in_array($isOwner->first()->role_id, [5,6,9]))) ? true : false;
//
//        if (empty($isSiteAdmin) and $allowUpdate) {
//            return Response::JSON(['status' => 'Failed', 'message' => 'You have no permission to update group ' . $input['name'] . '!']);
//        }
//
//        if (isset($input['private'])) $group->private = intval($input['private']);
//        if (isset($input['description'])) $group->description = $input['description'];
//
//        if (isset($input['resource_requested']) and !empty(json_decode($input['resource_requested'], true))) {
//            $group->approved = '0';
//            $group->resource_requested = $input['resource_requested'];
//            $group->expiration = $input['expiration'];
//            $group->site_id = $input['site_id'];
//            $message .= '<br>Resource Request Submitted.';
//        }
//
//        try {
//            $group->save();
//        } catch (QueryException $e) {
//            return Response::JSON(['status' => 'Failed', 'message' => $e->errorInfo[2]]);
//        }
//
//        /**
//         *  The member update function need to be modified.
//         *  Currently, I just delete the current members and insert members in the input list to UserGroupRole table.
//         *  We need a smart way to compare the current members and their roles to the member data in the input list,
//         *  and only insert or delete the updated members.
//         *
//         */
//
//        if (isset($input['members'])) {
//            $curr_members = $group->usersGroupsRoles()->where('group_id', '=', $group->getAttribute('id'))->get();
//            foreach ($curr_members as $curr_member) {
//                if ($curr_member->role->name != 'group_owner') {
//                    $group->usersGroupsRoles()->where('user_id', '=', $curr_member->user_id)->delete();
//                }
//            }
//            $members = $input['members'];
//            foreach ($members as $member) {
//                foreach ($member['roles'] as $role) {
//                    $ugr = new UserGroupRole();
//                    $ugr->fill(['group_id' => $group->getAttribute('id'), 'user_id' => $member['id'], 'role_id' => $role])->save();
//                }
//            }
//            $message .= '<br>Member updated.';
//        }
//
//        if (isset($input['batch_emails'])) {
//            $c = $this->batchEnroll($input['batch_emails'], $group->id);
//            if ($c > 0 ) {
//                $message .= '<br>Batch enroll ' . $c . ' members.';
//            }
//        }
//
//        return Response::JSON(['status' => 'Success', 'message' => 'Group ' . $input['name'] . ' updated. ' . $message,
//            'group' => $group, 'group_status' => $this->status($group)]);
//    }

    public function addMembers(Request $request)
    {
        $input = $request->all();
        $grp = $this->user->usersGroupsRoles()->whereHas('Role', function($query) {
            $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
        })->where('group_id', '=', $input['group_id'])->get();
        if (count($grp) == 0){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        $result = "";
        if (isset($input['members'])) {
            foreach ($input['members'] as $member) {
                $ugr = new UserGroupRole();
                $ugr->fill(['group_id' => $input['group_id'], 'user_id' => $member, 'role_id' => $input['role_id']])->save();
            }
            $result = ['status' => 'Success', 'message' => count($input['members']) . ' new members added'];
        }
        return Response::JSON($result);
    }

    public function removeMembers(Request $request)
    {
        $input = $request->all();
        $grp = $this->user->usersGroupsRoles()->whereHas('Role', function($query) {
            $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
        })->where('group_id', '=', $input['group_id'])->get();
        if (count($grp) == 0){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        $result = "";
        $invalid = "";
        if (isset($input['members'])) {
            foreach ($input['members'] as $member) {
                $subgroups = Subgroup::where('group_id', '=', $input['group_id'])->get();
                foreach ($subgroups as $subgroup) {
                    if (!is_null($u_email=$subgroup->users()->where('id', '=', $member)->first()['email'])) {
                        $invalid .= $u_email . '<br>';
                        break;
                    }
                }
            }
            if (strlen($invalid) > 0) {
                $result = ['status' => 'Failed', 'message' => $invalid . ' cannot be removed '];
            } else {
                foreach ($input['members'] as $member) {
                    $ugr = UserGroupRole::where([['group_id', '=', $input['group_id']], ['user_id', '=', $member]]);
                    $ugr->delete();
                }
                $result = ['status' => 'Success', 'message' => count($input['members']) . ' members removed '];
            }
        }
        return Response::JSON($result);
    }

    public function changeRoles(Request $request)
    {
        $input = $request->all();
        $grp = $this->user->usersGroupsRoles()->whereHas('Role', function($query) {
            $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
        })->where('group_id', '=', $input['group_id'])->get();
        if (count($grp) == 0){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        $ugrs = UserGroupRole::where([['group_id', '=', $input['group_id']], ['user_id', '=', $input['user_id']]]);
        $ugrs->delete();
        //        foreach ($ugrs as $ugr) {
//            if ($ugr->role()->first()->name != 'group_owner') {
//                $ugr->first()->delete();
//            }
//        }
        foreach ($input['roles'] as $role) {
            $ugr = new UserGroupRole();
            $ugr->fill(['group_id' => $input['group_id'], 'user_id' => $input['user_id'], 'role_id' => $role])->save();
        }
        $result = ['status' => 'Success', 'message' => ' role has been changed '];
        return Response::JSON($result);
    }

    public function batchEnrollMembers(Request $request)
    {
        $input = $request->all();
        $grp = $this->user->usersGroupsRoles()->whereHas('Role', function($query) {
            $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
        })->where('group_id', '=', $input['group_id'])->get();
        if (count($grp) == 0){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }

        $result = "";
        if (isset($input['batch_emails'])) {
            $c = $this->batchEnroll($input['batch_emails'], $input['group_id']);
            if ($c > 0 ) {
                $result = ['status' => 'Success', 'message' => 'Batch enroll ' . $c . ' members '];
            } else {
                $result = ['status' => 'Failed', 'message' => 'No members enrolled!'];
            }
        }
        return Response::JSON($result);
    }

    public function delete(Request $request)
    {
        $gId = $request->get('group_id');
        $gName = $request->get('group_name');
        $grp = $this->user->usersGroupsRoles()->whereHas('Role', function($query) {
            $query->whereIn('name', ['group_owner']);
        })->where('group_id', '=', $gId)->get();
        if (count($grp) == 0){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        try {
            $group = Group::findOrFail($gId);
        } catch (ModelNotFoundException $e) {
            return Response::JSON(['status' => 'Failed', 'message' => 'Group ' . $gName . ' cannot be found!']);
        }

        $user = User::find(Auth::user()->id);
        $isSiteAdmin = $user->siteAdmins()->where('site_id', '=', $group->site->id)->get();
        $isOwner = $user->usersGroupsRoles()->where('group_id', '=', $group->id)->get();
        if (empty($isSiteAdmin) and empty($isOwner)) {
            return Response::JSON(['status' => 'Failed', 'message' => 'You have no permission to delete group ' . $gName . '!']);
        }

//        if ($this->status($group) == 'Active' or $this->status($group) == 'Disabled' or $this->status($group) == 'Pending') {
        $subgroups = Subgroup::where('group_id', '=', $gId)->get();

        if (count($subgroups) <= 0) {
            try {
                $group->usersGroupsRoles()->delete();
                $group->delete();
            } catch (QueryException $e) {
                return Response::JSON(['status' => 'Failed', 'message' => 'Group ' . $gName . ' is currently locked!']);
            }
            return Response::JSON(['status' => 'Success', 'message' => 'Group ' . $gName . ' deleted!']);
        } else {
            return Response::json(['status' => 'Failed', 'message' => 'Please remove assigned team lists from "Team & Lab Assignment" in the "Lab Management" first.']);
        }
    }

    public function batchEnroll($emailstr, $group_id)
    {
       // $role = Role::findByName('student')->get()->first();
        $role = Role::where('name', '=', 'student')->get()->first();
        $emailstring = str_replace('\n', '', $emailstr);
        $emails = explode(";", $emailstring);
        $new_users = 0;
        $register_service = app()->make('App\Http\Controllers\Auth\RegisterController');
        foreach ($emails as $email) {
            $email = trim($email);
            if ($email != "") {
                try {
                    $user = User::where('email', '=', $email)->firstOrFail();
                    try {
                        $is_existed = $user->usersGroupsRoles()->where('group_id', '=', $group_id)->firstOrFail();
                    } catch (ModelNotFoundException $e) {
                        $ugr = new UserGroupRole();
                        $ugr->fill(['user_id' => $user->id, 'group_id' => $group_id, 'role_id' => $role->getAttribute('id')])->save();
                        $new_users++;
                    }
                } catch (ModelNotFoundException $e){
                    // send registration invitation email
                    $randompass = substr(str_shuffle(MD5(microtime())), 0, 10);
                    $fakeuser = array('first_name'=>'first_name', 'last_name'=>'last_name', 'email'=>$email, 'institute'=>'ASU',
                        'password'=>$randompass, 'password_confirmation'=>$randompass, 'country'=>'USA');
//                    $newuser = App::make('register_service')->register2($fakeuser);
                    $newuser = $register_service->registerforbatch($fakeuser);

                    // mark new user
                    $n_user = User::where('email', '=', $email)->get()->first();
                    $n_user->fill(['group_based_registration_invitation' => '1'])->save();

                    $ugr = new UserGroupRole();
                    $ugr->fill(['user_id' => $n_user->id, 'group_id' => $group_id, 'role_id' => $role->getAttribute('id')])->save();
                    $new_users++;
                }
            }
        }
        return $new_users;
    }

    public function getOwnGroupList_byRole(Request $request)
    {
        if ($request->ajax()) {
            $arr = array();
            $arr["groups"] = array();
            $sql = "SELECT groups.name, groups.id, groups.description, groups.private, groups.active_duration_tiem ".
                "FROM users_groups ".
                "JOIN groups ON users_groups.group_id=groups.id ".
                "JOIN users ON users_groups.user_id=users.id ".
                "WHERE groups.class is NULL AND users_groups.role_id=15 AND users.email='" . $this->user . "'";
            $result = DB::select($sql);
            foreach($result as $row) {
                $applicant = array("group_name" => $row->name, "group_id" => $row->id, "group_desc" => $row->description, "group_private" => $row->private, "group_expire" => $row->active_duration_tiem);
                array_push($arr["groups"], $applicant);
            }
            return Response::json($arr);
        }
    }

    public function getOwnGroupList()
    {
        if (Request()->ajax()) {
            // return json
            $arr = array();
            $arr["groups"] = array();
            $sql = "SELECT groups.name, groups.id, groups.description, groups.private FROM groups JOIN users ON groups.owner_id=users.id WHERE groups.class is NULL AND users.email='" . $this->user . "'";
            //$result = $conn->query($sql);
            $result = DB::select($sql);
            //while ($row = $result->fetch_assoc()) {
            foreach($result as $row) {
                $applicant = array("group_name" => $row->name, "group_id" => $row->id, "group_desc" => $row->description, "group_private" => $row->private);
                array_push($arr["groups"], $applicant);
            }
            return Response::json($arr);
        }
    }

    function getGroupMembers(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $arr = array();
            $arr["members"] = array();
            $rows = DB::select('SELECT users.id, users.email, users.institute, users.org_id, permission.description ' .
                'FROM users_groups '.
                'JOIN users ON users_groups.user_id=users.id ' .
                'JOIN groups ON users_groups.group_id=groups.id ' .
                'JOIN permission ON users_groups.role_id=permission.id ' .
                'WHERE groups.name=?' , array($input["group_name"]));
            foreach ($rows as $row) {
                $member = array("id" => $row->id, "email" => $row->email, "institute" => $row->institute, "org_id" => $row->org_id, "role" => $row->description);
                array_push($arr["members"], $member);
            }
            return Response::json($arr);
        }
    }

}