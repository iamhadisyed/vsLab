<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/7/18
 * Time: 12:49 AM
 */

namespace App\Http\Controllers;

use Auth, App\Labs, App\User, App\Group, App\Role, App\Subgroup;
use Input;
use Redirect;
use App\DataTables\MylabsDataTable;
use Sentry;
use Response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use DB;
use App\Traits\CheckUserStatusTrait;

class MyLabController extends Controller
{
    use CheckUserStatusTrait;

    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                redirect('/');
            }

            $this->user = Auth::user()->id;
            return $next($request);
        });
    }

    public function index(MylabsDataTable $dataTable, $id)
    {
        try {
            $group = Group::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('404');
        }

        if (count($group->usersGroupsRoles()->where('user_id', '=', $this->user)->get()) == 0) {
            alert()->warning('You have no permission to access this group!', 'Permission Denied')->persistent('OK');
            return redirect()->back();
        }
        $grp = Auth::user()->usersGroupsRoles()->whereHas('Role', function($query) {
            $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
        })->where('group_id', '=', $id)->get();
        if (count($grp) == 0){
            $role=1;
            }else{
            $role=0;
        }

        $g_instructors = $group->usersGroupsRoles()->whereHas('Role', function($query) {
            $query->whereIn('name', ['instructor']);
        })->where('group_id', '=', $id)->get();

        $instructors = new collection();
        foreach ($g_instructors as $g_instructor) {
            $instructors->push( (object) ['id' => $g_instructor->user()->first()->id,
                'name' => $g_instructor->user()->first()->name, 'email' => $g_instructor->user()->first()->email]);
        }

        $g_TAs = $group->usersGroupsRoles()->whereHas('Role', function($query) {
            $query->whereIn('name', ['TA']);
        })->where('group_id', '=', $id)->get();

        $TAs = new collection();
        foreach ($g_TAs as $g_TA) {
            $TAs->push( (object) ['id' => $g_TA->user()->first()->id,
                'name' => $g_TA->user()->first()->name, 'email' => $g_TA->user()->first()->email]);
        }


        $class = (object) ['id' => $id, 'name' => $group->name, 'site' => $group->site()->first()->name,
            'description' => $group->description, 'instructors' => $instructors, 'TAs' => $TAs];

        //refreshtable($id);

        return $dataTable->with('id', $id)->with('role',$role)->render('admin.mylabs.index', compact('id', 'class'));
    }

    public function refreshtable($id){
        $rows = DB::table('deploylabs')->select('deploylabs.subgroup_id',
            'deploylabs.project_id','deploylabs.id')
            ->join('subgroups', 'deploylabs.subgroup_id', '=', 'subgroups.id')
            ->join('users_subgroups', 'users_subgroups.subgroup_id', '=', 'subgroups.id')
            ->where('subgroups.group_id', '=' , $id)
            ->where('users_subgroups.user_id', '=', Auth::user()->id)->get();

        foreach ($rows as $row) {
            $subgroup= SubGroup::find($row->subgroup_id);
            $users = $subgroup->users()->get();
            $members=$subgroup->users()->get();
            $groupid=$subgroup->group()->first()->id;
            $members_str = "";
            foreach ($members as $member) {
                $userstatus=$this->getUserStatus($member->id);
                if($member->usersGroupsRoles()->where('group_id','=',$groupid)->whereHas('Role', function($query) {
                        $query->whereIn('name', ['student']);
                    })->count()>0){
                    $logout_t = Carbon::parse($member->last_logout);
                    $active_t = Carbon::parse($member->last_activity);
                    $current = Carbon::now();
                    $members_str.=$member->email."<br/>";
                    if($userstatus== "Offline"){
                        $totalDuration = $current->diffInSeconds($active_t);
                        DB::table('deploylabs')->where('id',$row->id)->
                        update(
                            array(
                                'inactivefor'=>$totalDuration
                            )
                        );
                    }elseif($userstatus== "Timeout"){
                        $totalDuration = $current->diffInSeconds($active_t);
                        DB::table('deploylabs')->where('id',$row->id)->
                        update(
                            array(
                                'inactivefor'=>$totalDuration
                            )
                        );
                    }elseif($userstatus== "Online"){
                        DB::table('deploylabs')->where('id',$row->id)->
                        update(
                            array(
                                'inactivefor'=>0
                            )
                        );
                    }
                }
            }

            DB::table('deploylabs')->where('id',$row->id)->
            update(
                array(
                    'user_list'=>$members_str

                )
            );



        }
        return Redirect::to('mylabs/'.$id.'/show');
    }

    public function submission($userid, $labid)
    {
        $Submissions = DB::table('users_subtasks_submission')->where('lab_id', $labid)->whereNotNull('type')->where('user_id',$userid)->orderBy('task_id', 'asc')->get();

        return view('admin.mylabs.submission')->with('TAs', $Submissions);
    }


    public function getsubmission($username, $projectid)
    {
            $username =$username.'@asu.edu';
            $userid = User::where('email', '=', $username)->get()->first()->id;
            if($projectid==1){
                $labid=695;
            }elseif($projectid==2){
                $labid=722;
            }


        $Submissions = DB::table('users_subtasks_submission')->where('lab_id', $labid)->whereNotNull('type')->where('user_id',$userid)->orderBy('task_id', 'asc')->get();

        return view('admin.mylabs.submission')->with('TAs', $Submissions);
    }

    public function getsubmission1($projectid)
    {
        $userid=Auth::user()->id;
        if($projectid==1){
            $labid=695;
        }elseif($projectid==2){
            $labid=722;
        }


        $Submissions = DB::table('users_subtasks_submission')->where('lab_id', $labid)->whereNotNull('type')->where('user_id',$userid)->orderBy('task_id', 'asc')->get();

        return view('admin.mylabs.submission')->with('TAs', $Submissions);
    }

    public function getLabAll(Request $request)
    {
        if ($request->ajax()) {

            $result = array();
            $labs = Lab::all(); //Get all sites
            foreach($labs as $lab) {
                array_push($result,
                    array('id' => $lab->id, 'name' => $lab->name, 'description' => $lab->description, 'subgroup' => $lab->subgroup,
                         'start' => date($lab->starttime), 'due' => date($lab->due))
                );
            }
            return Response::json($result);
        }
    }

    public function getLabList(Request $request) {
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


}