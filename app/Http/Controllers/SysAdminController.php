<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/15/17
 * Time: 12:00 PM
 */
namespace App\Http\Controllers;

use App;
use Auth;
use Xavrsl\Cas\Facades\Cas;
use Illuminate\Http\Request;
use Regulus\ActivityLog\Models\Activity;
use Response, Redirect, Config, DB, File;
use App\DataTables\InstancesDataTable;
use Yajra\DataTables\DataTables;
use App\Traits\CheckUserStatusTrait;
use Illuminate\Support\Collection;
use App\Group,App\Site;
use App\DeployLab;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Carbon\Carbon;
use Illuminate\Database\QueryException;


class SysAdminController extends Controller
{
    use CheckUserStatusTrait;
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                redirect('/');
            }

            $this->user = Auth::user();
            return $next($request);
        });
    }

    //public function getSystemConfigData()
    public function getCloudConfig()
    {
        $response = array();
        // $response['admin'] = $this->user;
        $response['auth_url'] = config('openstack.auth_url');
        $response['region'] = config('openstack.region');
        $response['users_admin_name'] = config('openstack.users_admin_name');
        $response['users_admin_id'] = config('openstack.users_admin_id');
        //$response['admin_password'] = config('openstack.admin_password');
        $response['user_domain_name'] = config('openstack.user_domain_name');
        $response['user_domain_id'] = config('openstack.user_domain_id');
        $response['user_role_id'] = config('openstack.user_role_id');
        $response['dummy_project_id'] = config('openstack.dummy_project_id');

        //return Response::JSON($response);
        return view('admin.sysadmin.cloud-config')->with('config', $response);
    }

    public function postSystemConfigData(Request $request)
    {
        $result = array();

        if (Config::get('openstack')) {
            //return Response::JSON(['status' => 'Failed', 'message' => 'The configuration file openstack.php is not exist in the system.']);
            return Response::JSON(['status' => 'Failed', 'message' => 'The configuration file openstack.php exists, but you don\'t have permission to access the file!']);
        }

        Config::has('openstack.auth_url') ? Config::set('openstack.auth_url', $request->get('auth_url')) : array_push($result, 'auth_url');
        Config::has('openstack.auth_url') ? Config::set('openstack.region', $request->get('region')) : array_push($result, 'region');
        Config::has('openstack.auth_url') ? Config::set('openstack.users_admin_name', $request->get('users_admin_name')) : array_push($result, 'users_admin_name');
        Config::has('openstack.auth_url') ? Config::set('openstack.users_admin_id', $request->get('users_admin_id')) : array_push($result, 'users_admin_id');
        Config::has('openstack.auth_url') ? Config::set('openstack.admin_password', $request->get('admin_password')) : array_push($result, 'admin_password');
        Config::has('openstack.auth_url') ? Config::set('openstack.user_domain_name', $request->get('user_domain_name')) : array_push($result, 'user_domain_name');
        Config::has('openstack.auth_url') ? Config::set('openstack.user_domain_id', $request->get('user_domain_id')) : array_push($result, 'user_domain_id');
        Config::has('openstack.auth_url') ? Config::set('openstack.user_role_id', $request->get('user_role_id')) : array_push($result, 'user_role_id');
        Config::has('openstack.auth_url') ? Config::set('openstack.dummy_project_id', $request->get('dummy_project_id')) : array_push($result, 'dummy_project_id');

        /*
         * Need to solve the file write permission issue
         */
        $fp = fopen(base_path() . '/config/openstack.php', 'w');

        if (!$fp) {
            return Response::JSON(['status' => 'Failed', 'message' => 'The configuration file openstack.php is exist, but you don\'t have permission to access the file!']);
        } else {

            fwrite($fp, "<?php /* Modified from Workspace Sys Admin @ " . date('m/d/Y h:i:s a', time()) .
                "return [\n" .
                "'auth_url' => '" . var_export(config('openstack.auth_url'), true) . "',\n" .
                "'region' => '" . var_export(config('openstack.region'), true) . "',\n" .
                "'users_admin_id' => '" . var_export(config('openstack.users_admin_id'), true) . "',\n" .
                "'users_admin_name' => '" . var_export(config('openstack.users_admin_name'), true) . "',\n" .
                "'user_domain_name' => '" . var_export(config('openstack.user_domain_name'), true) . "',\n" .
                "'user_domain_id' => '" . var_export(config('openstack.user_domain_id'), true) . "',\n" .
                "'admin_password' => '" . var_export(config('openstack.admin_password'), true) . "',\n" .
                "'dummy_project_id' => '" . var_export(config('openstack.dummy_project_id'), true) . "',\n" .
                "'user_role_id' => '" . var_export(config('openstack.user_role_id'), true) . "',\n" .
                "'adminAuthOptions' => [\n" .
                "'authUrl' => '" . var_export(config('openstack.authUrl'), true) . "',\n" .
                "'region' => '" . var_export(config('openstack.region'), true) . "',\n" .
                "'user' => [\n" .
                "'id' => '" . var_export(config('openstack.users_admin_id'), true) . "',\n" .
                "'password' => '" . var_export(config('openstack.admin_password'), true) . "',\n" .
                "],\n" .
                "'scope' => [\n" .
                "'project' => ['id' => '" . var_export(config('openstack.dummy_project_id'), true) . "']\n" .
                "]\n" .
                "]\n" .
                "];\n");

            fclose($fp);

            return Response::JSON(['status' => 'Success']);
        }
    }

    public function getInstancesforId($id)
    {


//        $grps = DB::table('groups')->select('*')->groupBy('id')->get();
//
//        $groups = new collection();
//
//        foreach($grps as $group) {
//            $g = Group::find($group->id);
//
//            $site_name = Site::find($g->getAttribute('site_id'))->getAttribute('name');
//            $groups->push( (object) ['id' => $group->id, 'name' => $g->getAttribute('name'), 'site' => $site_name ]);
//
//            //$site_name = Site::find($g->getAttribute('site_id'))->getAttribute('name');
//
//        }
        if($id==0){
            $rows = DB::table('instances_federated')->select('instances_federated.id','instances_federated.uuid', 'instances_federated.project_id', 'power_state', 'vm_state',
                'hostname', 'display_name', 'display_description', 'memory_mb', 'vcpus', 'project_name')
                ->leftJoin('deploylabs', 'instances_federated.project_id', '=', 'deploylabs.project_id')
//                ->join('instances_status', 'instances_status.instanceid', '=', 'instances_federated.id')
                ->whereNotIn('vm_state', ['error', 'deleted','building'])->get();
        }else{
            $rows = DB::table('instances_federated')->select('instances_federated.id','instances_federated.uuid', 'instances_federated.project_id', 'power_state', 'vm_state',
                'hostname', 'display_name', 'display_description', 'memory_mb', 'vcpus', 'project_name')
                ->leftJoin('deploylabs', 'instances_federated.project_id', '=', 'deploylabs.project_id')
                ->leftJoin('subgroups','deploylabs.subgroup_id','=','subgroups.id')
//                ->join('instances_status', 'instances_status.instanceid', '=', 'instances_federated.id')
                ->whereNotIn('vm_state', ['error', 'deleted','building'])->where('subgroups.group_id','=',$id)->get();
        }

        foreach ($rows as $row) {

            $subgroup = DeployLab::where('project_name', '=', $row->project_name)->first()->subgroup()->first();

            $inactive_members_str="";
            $active_members_str="";
            $members_str="";


            if ($subgroup) {
                $users = $subgroup->users()->get();
                $groupid=$subgroup->group()->first()->id;
                foreach ($users as $user) {
                    if($user->email=="mkalquba@asu.edu"){
                        $test = 1;
                    }
                    $userstatus=$this->getUserStatus($user->id);
                    if(($user->usersGroupsRoles()->where('group_id','=',$groupid)->whereHas('Role', function($query) {
                            $query->whereIn('name', ['student']);
                        })->count()>0)){
                        $logout_t = Carbon::parse($user->last_logout);
                        $active_t = Carbon::parse($user->last_activity);
                        $current = Carbon::now();
                        $members_str.=$user->email."<br>";

                        if($userstatus== "Offline"){
                            $totalDuration = $current->diffInSeconds($active_t);
                            //$time=gmdate('H', $totalDuration);
                            //$members_str.=$user->email." has logged out for ".$time." hours.<br>";
                            $time=number_format($totalDuration/3600,2,'.','');
                            $inactive_members_str.=$user->email." has logged out for ".$time." hours.<br>";
                            try {
                                DB::table('instances_status')->insert([
                                    ['uuid' => $row->uuid, 'inactivefor'=>$totalDuration,'instanceid'=>$row->id]
                                ]);
                            } catch (QueryException $e) {
                                DB::table('instances_status')->where('instanceid',$row->id)->
                                update(
                                    array(
                                        'inactivefor'=>$totalDuration

                                    )
                                );
                            }
                        }elseif($userstatus== "Timeout"){
                            $totalDuration = $current->diffInSeconds($active_t);
                            $time=number_format($totalDuration/3600,2,'.','');
                            //$members_str.=$user->email." has time out for ".$time." hours.<br>";
                            $inactive_members_str.=$user->email." has time out for ".$time." hours.<br>";
                            try {
                                DB::table('instances_status')->insert([
                                    ['uuid' => $row->uuid, 'inactivefor'=>$totalDuration,'instanceid'=>$row->id]
                                ]);
                            } catch (QueryException $e) {
                                DB::table('instances_status')->where('instanceid',$row->id)->
                                update(
                                    array(
                                        'inactivefor'=>$totalDuration

                                    )
                                );
                            }

                        }elseif($userstatus== "Online"){
                            $active_members_str.=$user->email." is active<br>";
                            try {
                                DB::table('instances_status')->insert([
                                    ['uuid' => $row->uuid, 'inactivefor'=>0,'instanceid'=>$row->id]
                                ]);
                            } catch (QueryException $e) {
                                DB::table('instances_status')->where('instanceid',$row->id)->
                                update(
                                    array(
                                        'inactivefor'=>0

                                    )
                                );
                            }

                        }elseif($userstatus== "None"){
                            $inactive_members_str.=$user->email." is inactive<br>";
                        }
                    }
//                    if(($user->usersGroupsRoles()->where('group_id','=',$groupid)->whereHas('Role', function($query) {
//                                $query->whereIn('name', ['student']);
//                            })->count()>0)&&($userstatus == "Online")){
//
//                        $active_members_str.=$user->email." is active<br>";
//                    }elseif(($user->usersGroupsRoles()->where('group_id','=',$groupid)->whereHas('Role', function($query) {
//                                $query->whereIn('name', ['student']);
//                            })->count()>0)&&!($userstatus== "Online")){
//                        $logout_t = Carbon::parse($user->last_logout);
//                        $active_t = Carbon::parse($user->last_activity);
//                        $current = Carbon::now();
//                        if($userstatus== "Offline"){
//                            $totalDuration = $current->diffInSeconds($active_t);
//                            $time=gmdate('H', $totalDuration);
//                            $inactive_members_str.=$user->email." has logged out for ".$time." hours.<br>";
//                        }elseif($userstatus== "Timeout"){
//                            $totalDuration = $current->diffInSeconds($active_t);
//                            $time=gmdate('H', $totalDuration);
//                            $inactive_members_str.=$user->email." has time out for ".$time." hours.<br>";
//                        }elseif($userstatus== "None"){
//                            $inactive_members_str.=$user->email." is inactive<br>";
//                        }
//                    }
                }

                    DB::table('instances_status')->where('instanceid',$row->id)->
                    update(
                        array(
                            'inactive_user_list'=>$inactive_members_str,
                            'active_user_list'=>$active_members_str,
                            'user_list'=>$members_str

                        )
                    );


            }




        }


        return Redirect::to('sysadmin/instances/'.$id.'/table');




    }

    public function refreshInstances()
    {

//        $grps = DB::table('groups')->select('*')->groupBy('id')->get();
//
//        $groups = new collection();
//
//        foreach($grps as $group) {
//            $g = Group::find($group->id);
//
//            $site_name = Site::find($g->getAttribute('site_id'))->getAttribute('name');
//            $groups->push( (object) ['id' => $group->id, 'name' => $g->getAttribute('name'), 'site' => $site_name ]);
//
//            //$site_name = Site::find($g->getAttribute('site_id'))->getAttribute('name');
//
//        }

            $rows = DB::table('instances_federated')->select('instances_federated.id','instances_federated.uuid', 'instances_federated.project_id', 'power_state', 'vm_state',
                'hostname', 'display_name', 'display_description', 'memory_mb', 'vcpus', 'project_name')
                ->leftJoin('deploylabs', 'instances_federated.project_id', '=', 'deploylabs.project_id')
//                ->join('instances_status', 'instances_status.instanceid', '=', 'instances_federated.id')
                ->whereNotIn('vm_state', ['error', 'deleted','building'])->get();



        foreach ($rows as $row) {

            $subgroup = DeployLab::where('project_name', '=', $row->project_name)->first()->subgroup()->first();

            $inactive_members_str="";
            $active_members_str="";
            $members_str="";


            if ($subgroup) {
                $users = $subgroup->users()->get();
                $groupid=$subgroup->group()->first()->id;
                foreach ($users as $user) {
                    $userstatus=$this->getUserStatus($user->id);
                    if(($user->usersGroupsRoles()->where('group_id','=',$groupid)->whereHas('Role', function($query) {
                            $query->whereIn('name', ['student']);
                        })->count()>0)){
                        $logout_t = Carbon::parse($user->last_logout);
                        $active_t = Carbon::parse($user->last_activity);
                        $current = Carbon::now();
                        $members_str.=$user->email."<br>";

                        if($userstatus== "Offline"){
                            $totalDuration = $current->diffInSeconds($active_t);
                            //$time=gmdate('H', $totalDuration);
                            //$members_str.=$user->email." has logged out for ".$time." hours.<br>";
                            $time=number_format($totalDuration/3600,2,'.','');
                            $inactive_members_str.=$user->email." has logged out for ".$time." hours.<br>";
                            try {
                                DB::table('instances_status')->insert([
                                    ['uuid' => $row->uuid, 'inactivefor'=>$totalDuration,'instanceid'=>$row->id]
                                ]);
                            } catch (QueryException $e) {
                                DB::table('instances_status')->where('instanceid',$row->id)->
                                update(
                                    array(
                                        'inactivefor'=>$totalDuration

                                    )
                                );
                            }
                        }elseif($userstatus== "Timeout"){
                            $totalDuration = $current->diffInSeconds($active_t);
                            $time=number_format($totalDuration/3600,2,'.','');
                            //$members_str.=$user->email." has time out for ".$time." hours.<br>";
                            $inactive_members_str.=$user->email." has time out for ".$time." hours.<br>";
                            try {
                                DB::table('instances_status')->insert([
                                    ['uuid' => $row->uuid, 'inactivefor'=>$totalDuration,'instanceid'=>$row->id]
                                ]);
                            } catch (QueryException $e) {
                                DB::table('instances_status')->where('instanceid',$row->id)->
                                update(
                                    array(
                                        'inactivefor'=>$totalDuration

                                    )
                                );
                            }

                        }elseif($userstatus== "Online"){
                            $active_members_str.=$user->email." is active<br>";
                            try {
                                DB::table('instances_status')->insert([
                                    ['uuid' => $row->uuid, 'inactivefor'=>0,'instanceid'=>$row->id]
                                ]);
                            } catch (QueryException $e) {
                                DB::table('instances_status')->where('instanceid',$row->id)->
                                update(
                                    array(
                                        'inactivefor'=>0

                                    )
                                );
                            }

                        }elseif($userstatus== "None"){
                            $inactive_members_str.=$user->email." is inactive<br>";
                        }
                    }
//                    if(($user->usersGroupsRoles()->where('group_id','=',$groupid)->whereHas('Role', function($query) {
//                                $query->whereIn('name', ['student']);
//                            })->count()>0)&&($userstatus == "Online")){
//
//                        $active_members_str.=$user->email." is active<br>";
//                    }elseif(($user->usersGroupsRoles()->where('group_id','=',$groupid)->whereHas('Role', function($query) {
//                                $query->whereIn('name', ['student']);
//                            })->count()>0)&&!($userstatus== "Online")){
//                        $logout_t = Carbon::parse($user->last_logout);
//                        $active_t = Carbon::parse($user->last_activity);
//                        $current = Carbon::now();
//                        if($userstatus== "Offline"){
//                            $totalDuration = $current->diffInSeconds($active_t);
//                            $time=gmdate('H', $totalDuration);
//                            $inactive_members_str.=$user->email." has logged out for ".$time." hours.<br>";
//                        }elseif($userstatus== "Timeout"){
//                            $totalDuration = $current->diffInSeconds($active_t);
//                            $time=gmdate('H', $totalDuration);
//                            $inactive_members_str.=$user->email." has time out for ".$time." hours.<br>";
//                        }elseif($userstatus== "None"){
//                            $inactive_members_str.=$user->email." is inactive<br>";
//                        }
//                    }
                }

                DB::table('instances_status')->where('instanceid',$row->id)->
                update(
                    array(
                        'inactive_user_list'=>$inactive_members_str,
                        'active_user_list'=>$active_members_str,
                        'user_list'=>$members_str

                    )
                );


            }




        }






    }

    public function getInstanceswithId(InstancesDataTable $dataTable, $id)
    {
//        $user = Auth::user();
//
//        $grps = $user->usersGroupsRoles()->whereHas('Role', function($query) {
//            $query->where('group_id','<>',0 );
//        })->groupBy('group_id')->get();
        $grps = DB::table('groups')->select('*')->where('vmflag','=','1')->groupBy('id')->get();

        $groups = new collection();
        $g_ids = array();
        foreach($grps as $group) {
            $g = Group::find($group->id);

            $site_name = Site::find($g->getAttribute('site_id'))->getAttribute('name');
            $groups->push( (object) ['id' => $group->id, 'name' => $g->getAttribute('name'), 'site' => $site_name ]);
            array_push($g_ids, $g->getAttribute('id'));
            //$site_name = Site::find($g->getAttribute('site_id'))->getAttribute('name');

        }

        if (in_array($id, $g_ids)) {
            return $dataTable->with('id', $id)->render('admin.sysadmin.instances', compact('id', 'groups'));
        }elseif($id == 0) {
            return $dataTable->with('id', $id)->render('admin.sysadmin.instances', compact('id', 'groups'));
        }

    }

    public function getInstances()
    {
//        $user = Auth::user();
//
//        $grps = $user->usersGroupsRoles()->whereHas('Role', function($query) {
//            $query->where('group_id','<>',0 );
//        })->groupBy('group_id')->get();
        $grps = DB::table('groups')->select('*')->where('vmflag','=','1')->groupBy('id')->get();


        $groups = new collection();
        $g_ids = array();
        foreach($grps as $group) {
            $g = Group::find($group->id);

            $site_name = Site::find($g->getAttribute('site_id'))->getAttribute('name');
            $groups->push( (object) ['id' => $group->id, 'name' => $g->getAttribute('name'), 'site' => $site_name ]);
            array_push($g_ids, $g->getAttribute('id'));
            //$site_name = Site::find($g->getAttribute('site_id'))->getAttribute('name');

        }


            return view('admin.sysadmin.newinstances', compact('id', 'groups'));


    }

    public function setautosuspend(Request $request)
    {
        $input = $request->all();
//        $grp = $this->user->usersGroupsRoles()->whereHas('Role', function($query) {
//            $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
//        })->where('group_id', '=', $input['groupId'])->get();
//        if (count($grp) == 0){
//            alert()->warning('You don\'t have permission to do that!');
//            return redirect()->back();
//        }
//        if (!isset($input['members'])) {
//            alert()->warning('Please select members!', 'Create Team')->persistent('OK');
//            return redirect()->back()->with('show-create-team-modal', 1);
//        }
//
//        $supers = UserGroupRole::where('group_id', '=', $input['groupId'])->whereHas('Role', function($query) {
//            $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
//        })->groupBy('user_id')->get()->pluck('user_id');

        if ($input['suspend-flag'] == 'disable') {
//            if (preg_match('/\s/', $input['name'])) {
//                alert()->warning('Team name cannot have space!', 'Create Team')->persistent('OK');
//                return redirect()->back()->with('show-create-team-modal', 1);
//            }
            $group = Group::where('id','=',$input['groupId'])->first();
            try {
                DB::table('groups')->where('id',$input['groupId'])->
                update(
                    array(
                        'suspendflag'=>0,
                        'suspendtimelimit'=>0

                    )
                );
            } catch (QueryException $e) {
                alert()->error('Set failed')->persistent('OK');
                return redirect()->back();
            }

            alert()->success('Auto suspending for ' . $group->name . ' unset!')->persistent('OK');
            return redirect()->back();

        } elseif ($input['suspend-flag'] == 'enable'){
            $group = new Group;
            try {
                DB::table('groups')->where('id',$input['groupId'])->
                update(
                    array(
                        'suspendflag'=>1,
                        'suspendtimelimit'=>$input['name']

                    )
                );
            } catch (QueryException $e) {
                alert()->error('Set failed')->persistent('OK');
                return redirect()->back();
            }
            alert()->success('Auto suspending for ' . $group->getAttribute('name') . ' set!')->persistent('OK');
//            alert()->warning($message, 'Create Team')->persistent('OK');
            return redirect()->back()->with('');
        }
    }
}