<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/7/18
 * Time: 12:49 AM
 */

namespace App\Http\Controllers;

use App\Site, App\User, App\Group, App\Role, App\UserProfile;
use App\UserSiteRole;
use App\DataTables\SitesDataTable;
use App\DataTables\SiteGroupsDataTable;
use App\DataTables\SiteUsersDataTable;
use Auth;
use Response;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Yajra\DataTables\DataTables;

class SiteController extends Controller
{
    protected $user;
    protected $timezone;

    public function __construct() {
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
     * @param \App\DataTables\SitesDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function index(SitesDataTable $dataTable)
    {
        if (!$this->user->hasRole('system_admin')) {
            alert()->warning('You have no permission to access this page!', 'Permission Denied')->persistent('OK');
            return redirect()->back();
        }
        return $dataTable->render('admin.sites.index');
    }

    public function mySite(SitesDataTable $dataTable)
    {
        if (!$this->user->hasRole('site_admin')) {
            alert()->warning('You have no permission to access this page!', 'Permission Denied')->persistent('OK');
            return redirect()->back();
        }
        return $dataTable->with('mysite', true)->render('admin.mysites.index');
    }

    public function siteGroups(SiteGroupsDataTable $dataTable, $id)
    {
        if (!$this->user->hasRole('site_admin')) {
            alert()->warning('You have no permission to access this page!', 'Permission Denied')->persistent('OK');
            return redirect()->back();
        }

        $usr = Auth::user()->usersSitesRoles()->whereHas('Role', function($query) {
            $query->whereIn('name', ['site_admin']);
        })->get();

        $sites = new collection();
        $s_ids = array();
        foreach($usr as $site) {
            $g = Site::find($site->site_id);
            $sites->push( (object) ['id' => $site->site_id, 'name' => $g->getAttribute('name') ]);
            array_push($s_ids, $g->getAttribute('id'));
        }

        if (in_array($id, $s_ids) or ($id == 0)) {
            return $dataTable->with('id', $id)->render('admin.mysites.groups', compact('id', 'sites'));
        } else {
            alert()->warning('You have no permission to access this page!', 'Permission Denied')->persistent('OK');
            return redirect()->back();
        }
    }

    public function siteUsers(SiteUsersDataTable $dataTable, $id)
    {
        if (!$this->user->hasRole('site_admin')) {
            alert()->warning('You have no permission to access this page!', 'Permission Denied')->persistent('OK');
            return redirect()->back();
        }

        $usr = Auth::user()->usersSitesRoles()->whereHas('Role', function($query) {
            $query->whereIn('name', ['site_admin']);
        })->get();

        $sites = new collection();
        $s_ids = array();
        foreach($usr as $site) {
            $g = Site::find($site->site_id);
            $sites->push( (object) ['id' => $site->site_id, 'name' => $g->getAttribute('name') ]);
            array_push($s_ids, $g->getAttribute('id'));
        }

        if (in_array($id, $s_ids) or ($id == 0)) {
            return $dataTable->with('id', $id)->render('admin.mysites.users', compact('id', 'sites'));
        } else {
            alert()->warning('You have no permission to access this page!', 'Permission Denied')->persistent('OK');
            return redirect()->back();
        }
    }

    function getAvailableRoles() {
        return Role::where('type', '=', 'site')->get();
    }

    public function getSiteAll(Request $request) {
        if ($request->ajax()) {

            $result = array();
            $sites = Site::all(); //Get all sites
            foreach($sites as $site) {
                $admins = str_replace(array('[',']','"'),'', $site->admins()->pluck('email'));
                array_push($result,
                    array('id' => $site->id, 'name' => $site->name, 'description' => $site->description, 'resources' => $site->resources,
                        'admins' => $admins, 'created' => date($site->created_at), 'updated' => date($site->updated_at))
                );
            }
            return Response::json($result);
        }
    }

    public function getSiteList(Request $request) {
        if ($request->ajax()) {
            $result = array();
            $id = Sentry::getUser()->getId();
            $user = User::findOrFail($id);

            $sites = $user->sites()->get();
            foreach($sites as $site) {
                array_push($result, array('id' => $site['id'], 'name' => $site['name']));
            }

            return Response::json($result);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        // $permissions = Permission::all();//Get all permissions

        return view('admin.sites.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $newSite = $request->get('site');
            $name = $newSite['name'];
            $desc = $newSite['desc'];
            $rss = $newSite['resources'];
            $admins = $request->get('admins');
        } else {
            $name = $request['name'];
            $desc = $request['desc'];
            $rss = $request['resources'];
            $admins = $request['admins'];
        }

        // $admin_users_id = array();
        $admin_users = array();
        foreach (explode(';', $admins) as $admin) {
            try {
                if (strlen(trim($admin)) > 0) {
                    $user = User::where('email', '=', trim($admin))->firstOrFail();
                    // array_push($admin_users, $user);
                    // array_push($admin_users_id, $user->getAttribute('id'));
                    array_push($admin_users, $user->getAttribute('id'));
                }
            }
            catch(ModelNotFoundException $e) {
                if ($request->ajax()) {
                    return Response::JSON(array('status' => 'Failed', 'message' => 'User ' . $admin . ' not found!'));
                } else {
                    alert()->warning('User ' . $admin . ' not found!')->persistent('OK');
                    return redirect()->route('admin.sites.index');
                }
            }
        }

        $site = new Site();
        $site->name = $name;
        $site->description = $desc;
        $site->resources = $rss;

        try {
            $site->save();
            foreach ($admin_users as $admin_user) {
                $new_user = User::find($admin_user);
                $usg = new UserSiteRole();
                $usg->fill(['user_id' => $admin_user, 'site_id' => $site->id, 'role_id' => Role::findByName('site_admin')->id])->save();
                $new_user->assignRole('site_admin');
            }

            if ($request->ajax()) {
                return Response::JSON(array('status' => 'Success', 'message' => 'Site ' . $site->name . ' added!'));
//                    'id' => $site->id, 'name' => $site->name, 'description' => $site->description, 'resources' => $site->resources,
//                    'created' => date($site->created_at), 'updated' => date($site->updated_at)
//                ));
            } else {
                alert()->success('Site ' . $site->name . ' added!')->persistent('OK');
                return redirect()->route('admin.sites.index');
            }
        } catch (QueryException $e) {
            if ($request->ajax()) {
                return Response::JSON(array('status' => 'Failed', 'message' => $e->errorInfo[2]));
            } else {
                alert()->error($e->errorInfo[2])->persistent('OK');
                return redirect()->route('admin.sites.index');
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $site = Site::findOrFail($id);

        $site_admins = implode('; ', $site->admins()->pluck('email')->toArray());
        return view('admin.sites.edit', compact('site','site_admins'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function update(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->get('site');
            $admins = $request->get('admins');
        } else {
            $input = $request->all();
            $admins = $input['admins'];
        }

        $site = Site::find($request->get('id'));

        $admin_users = array();
        foreach (explode(';', $admins) as $admin) {
            try {
                if (strlen(trim($admin)) > 0) {
                    $user = User::where('email', '=', trim($admin))->firstOrFail();
                    array_push($admin_users, $user->getAttribute('id'));
                }
            }
            catch(ModelNotFoundException $e) {
                if ($request->ajax()) {
                    return Response::JSON(array('status' => 'Failed', 'message' => 'User ' . $admin . ' not found!'));
                } else {
                    alert()->warning('User ' . $admin . ' not found!')->persistent('OK');
                    return redirect()->back();
                }
            }
        }

        $ssr = $site->usersSitesRoles()->whereHas('Role', function($query) {
            $query->whereIn('name', ['site_admin']);
        });
        foreach ($ssr->get() as $sr) {
            if ($sr->user()->first()->hasRole('site_admin')) $sr->user()->first()->removeRole('site_admin');
        }
        $ssr->delete();
        $site->fill($input)->save();

        foreach ($admin_users as $admin_user) {
            $new_user = User::find($admin_user);
            $new_admin = new UserSiteRole();
            $new_admin->fill(['user_id' => $new_user->id, 'site_id' => $site->id, 'role_id' => Role::findByName('site_admin')->id])->save();
            $new_user->assignRole('site_admin');
        }

        if ($request->ajax()) {
            return Response::JSON(array('status' => 'Success', 'message' => 'Site ' . $site->name . ' updated!'));
        } else {
            alert()->success('Site ' . $site->name . ' updated!')->persistent('OK');
            return redirect()->route('admin.sites.index');
        }
    }

    public function mySiteSetting(Request $request)
    {
        if (!$this->user->hasRole('site_admin')) {
            alert()->warning('You have no permission to access this page!', 'Permission Denied')->persistent('OK');
            return redirect()->back();
        }

        $input = $request->get('site');

        $site = Site::find($request->get('id'));
        $site->fill($input)->save();
        return Response::JSON(array('status' => 'Success', 'message' => 'Site ' . $site->name . ' updated!'));
    }

    public function allocateResource(Request $request)
    {
        if (!$this->user->hasRole('site_admin')) {
            alert()->warning('You have no permission to access this page!', 'Permission Denied')->persistent('OK');
            return redirect()->back();
        }

        $input = $request->get('group');

        $input['expiration'] = Carbon::createFromFormat('m/d/Y', $input['expiration'])->setTimezone('UTC')->toDateTimeString();

        $group = Group::find($request->get('id'));
        try {
            $group->fill($input)->save();
        } catch (QueryException $e) {
            return Response::JSON(array('status' => 'Failed', 'message' => $e->errorInfo[2]));
        }
        return Response::JSON(array('status' => 'Success', 'message' => 'Resource for ' . $group->name . ' allocated!'));
    }

    public function addUsers(Request $request)
    {
        $input = $request->all();
        $result = "";
        if (isset($input['members'])) {
            foreach ($input['members'] as $member) {
                $ugr = new UserSiteRole();
                $ugr->fill(['site_id' => $input['site_id'], 'user_id' => $member, 'role_id' => $input['role_id']])->save();
                if (Role::findByName('super_user')->id == $input['role_id']) {
                    User::find($member)->assignRole('super_user');
                }
            }
            $result = ['status' => 'Success', 'message' => count($input['members']) . ' new users added.'];
        }
        return Response::JSON($result);
    }

    public function removeUsers(Request $request)
    {
        $input = $request->all();
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
                    $ugr = UserSiteRole::where([['group_id', '=', $input['group_id']], ['user_id', '=', $member]]);
                    $ugr->delete();
                }
                $result = ['status' => 'Success', 'message' => count($input['members']) . ' members removed!'];
            }
        }
        return Response::JSON($result);
    }

    public function changeRoles(Request $request)
    {
        $input = $request->all();

        $ugrs = UserSiteRole::where([['site_id', '=', $input['site_id']], ['user_id', '=', $input['user_id']]]);
        $ugrs->delete();

//        $user=User::find($input['user_id']);
//        if ($user->hasRole('super_user')) {
//            $user->removeRole('super_user');
//        }

        foreach ($input['roles'] as $role) {
            $ugr = new UserSiteRole();
            $ugr->fill(['site_id' => $input['site_id'], 'user_id' => $input['user_id'], 'role_id' => $role])->save();
//            if (Role::findByName('super_user')->id == $input['role_id']) {
//               $user->assignRole('super_user');
//            }
        }
        $result = ['status' => 'Success', 'message' => ' role has been changed.'];
        return Response::JSON($result);
    }

    public function batchEnrollUsers(Request $request)
    {
        $input = $request->all();
        $result = "";
        if (isset($input['batch_emails'])) {
            $c = $this->batchEnroll($input['batch_emails'], $input['site_id']);
            if ($c > 0 ) {
                $result = ['status' => 'Success', 'message' => 'Batch enroll ' . $c . ' members '];
            } else {
                $result = ['status' => 'Failed', 'message' => 'No members enrolled!'];
            }
        }
        return Response::JSON($result);
    }

    public function batchEnroll($emailstr, $site_id)
    {
        // $role = Role::findByName('student')->get()->first();
        $role = Role::where('name', '=', 'site_user')->get()->first();
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
                        $is_existed = $user->usersSitesRoles()->where('site_id', '=', $site_id)->firstOrFail();
                    } catch (ModelNotFoundException $e) {
                        $ugr = new UserSiteRole();
                        $ugr->fill(['user_id' => $user->id, 'site_id' => $site_id, 'role_id' => $role->getAttribute('id')])->save();
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

                    $ugr = new UserSiteRole();
                    $ugr->fill(['user_id' => $n_user->id, 'site_id' => $site_id, 'role_id' => $role->getAttribute('id')])->save();
                    $new_users++;
                }
            }
        }
        return $new_users;
    }

    public function applicationProcess(Request $request)
    {
        $groupInfo = $request->get('group');
        $site_id = $request->get('site');
        $process = $request->get('process');

        $user = User::find(Sentry::getUser()->getId());
        if (!$user->hasRole('system_admin|site_admin')) {
            return Response::JSON(['status' => 'Failed', 'message' => 'You have no permission to do this.']);
        }

        try {
            $group = Group::findOrFail($groupInfo['id']);
        } catch (ModelNotFoundException $e) {
            return Response::JSON(['status' => 'Failed', 'message' => 'Group ' . $groupInfo['name'] . ' cannot be found!']);
        }

        $isSiteAdmin = $user->siteAdmins()->where('site_id', '=', $site_id)->get();
        if (empty($isSiteAdmin)) {
            return Response::JSON(['status' => 'Failed', 'message' => 'You have no permission to approve/decline group '. $groupInfo['name'] . '!']);
        }

        if ($process == 'Approved') {
            $group->approved = 1;
            $group->approved_at = Carbon::now()->toDateTimeString();
            $group->expiration = Carbon::createFromFormat('m/d/Y', $groupInfo['approved_exp'])->toDateTimeString();
            $group->resource_allocated = $groupInfo['approved_rss'];
            $group->reason = $groupInfo['reason'];
            try {
                $group->save();
            } catch (QueryException $e) {
                return Response::JSON(['status' => 'Failed', 'message' => $e->errorInfo[2]]);
            }
        } else if ($process == 'Declined') {
            $group->enabled = 0;
            $group->reason = $groupInfo['reason'];
            $group->save();
        } else { // Disabled
            // to do
        }
        return Response::JSON(['status' => 'Success', 'message' => 'Group ' . $groupInfo['name'] . ' is ' . $process, 'group' => $group]);
    }
}