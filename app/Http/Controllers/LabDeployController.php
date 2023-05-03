<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 12/16/18
 * Time: 11:42 AM
 */

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use App, App\Site, App\Group, App\LabEnv, App\DeployLab, App\SubgroupLabHistory;
use App\Subgroup, App\Labs, App\LabContent;
use App\DataTables\DeployLabsDataTable;
use Illuminate\Http\Request;
use Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Yajra\DataTables\DataTables;
use DB;

class LabDeployController extends Controller
{
    protected $user;
    protected $user_id;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                redirect('/');
            }

            $this->user = Auth::user()->email;
            $this->user_id = Auth::user()->id;
            return $next($request);
        });
    }

    public function index(DeployLabsDataTable $dataTable, $id)
    {
        $user = Auth::user();

        $grps = $user->usersGroupsRoles()->whereHas('Role', function($query) {
            $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
        })->groupBy('group_id')->get();

        $groups = new collection();
        $g_ids = array();
        foreach($grps as $group) {
            $g = Group::find($group->group_id);
            $site_name = Site::find($g->getAttribute('site_id'))->getAttribute('name');
            $groups->push( (object) ['id' => $group->group_id, 'name' => $g->getAttribute('name'), 'site' => $site_name ]);
            array_push($g_ids, $g->getAttribute('id'));
        }

        if (in_array($id, $g_ids) or ($id == 0)) {
            return $dataTable->with('id', $id)->render('admin.deploylabs.index', compact('id', 'groups'));
        } else {
            alert()->warning('You have no permission to access this page!', 'Permission Denied')->persistent('OK');
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        $input = $request->all();
        $user = Auth::user();

        $success = [];
        $failed = [];
        foreach ($input['projects'] as $project) {
            $groupid=DeployLab::where('project_name', '=', $project)->first()->subgroup->group->id;
            $grp = $user->usersGroupsRoles()->whereHas('Role', function($query) {
                $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
            })->where('group_id', '=', $groupid)->get();
            if (count($grp) == 0){
                alert()->warning('You don\'t have permission to do that!');
                return redirect()->back();
            }

            $lab = DeployLab::where('project_name', '=', $project)->first();
            try {
                $lab->fill(['description' => $input['description']])->save();
                array_push($success, $project);
            } catch (QueryException $e) {
                array_push($failed, $project);
            }

            foreach ($input['contents'] as $content) {
                if (is_null($lab->labcontents())) continue;
                $lab->labcontents()->updateExistingPivot($content['content_id'],
                    ['start_at' => Carbon::createFromFormat('D M d Y H:i:s e+', $content['start_at'])->setTimezone('UTC')->toDateTimeString(),
                     'due_at' => Carbon::createFromFormat('D M d Y H:i:s e+', $content['due_at'])->setTimezone('UTC')->toDateTimeString()]);
            }
        }

        if (count($failed) > 0) {
            $result = ['status' => 'failed', 'projects' => $failed ];
        } else {
            $result = ['status' => 'success'];
        }
        return Response::json($result);
    }

    public function deploy(Request $request)
    {
        $input = $request->all();
        $project = $input['project'];
        $user = Auth::user();
        $openstackRes = app()->make('App\Http\Controllers\OpenStackResource');
        $cloudRes = app()->make('App\Http\Controllers\CloudResourceApi');
        $groupid=DeployLab::where('project_name', '=', $project['name'])->first()->subgroup->group->id;
        $grp = $user->usersGroupsRoles()->whereHas('Role', function($query) {
            $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
        })->where('group_id', '=', $groupid)->get();
        if (count($grp) == 0){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
//        foreach ($input['projects'] as $project) {
            $lab = DeployLab::where('project_name', '=', $project['name'])->first();

            if ($lab->status === 'NONE') {
                $members = $lab->subgroup()->first()->users->pluck('email');

                $lab->fill(['status' => 'CREATE_PROJECT'])->save();
                $tenant = $openstackRes->createProjectI($project, $members);
                if (is_null($tenant)) {
                    $lab->fill(['status' => 'PROJECT_FAILED'])->save();
                    return Response::json(['status' => 'Failed', 'message' => 'Create Project ' . $project . ' Failed!']);
                } else {
                    $lab->fill(['status' => 'PROJECT_COMPLETE', 'project_id' => $tenant->id])->save();
                }
                $openstackRes->grantRoleToProjectUser($tenant->id, $this->user, config('openstack.heat_stack_owner_role_id'));
            }

//        $labEnv_template = LabEnv::find(Labs::find($project['lab'])->labenv_id)->getAttribute('template');
        $labEnv_template = LabEnv::find($project['lab'])->getAttribute('template');

//            $stack = $cloudRes->getStackV2($project['name']);
//            if (!is_null($stack)) {
//                $lab->fill(['status' => 'DELETE_COMPLETE'])->save();
//            }

            $lab->fill(['status' => 'Deploying', 'deploy_at' => Carbon::now()])->save();
            $stack = $cloudRes->createStackFromIV2("s-" . $project['name'], $project['name'], $labEnv_template);
//        }
        return Response::json(['status' => 'Success', 'message' => 'Stack is Creating!', 'stack' => $stack]);
    }

    public function releaseResource(Request $request)
    {
        $input = $request->all();
        $project = $input['project'];
        $user = Auth::user();
        $groupid=DeployLab::where('project_name', '=', $project['name'])->first()->subgroup->group->id;
        $grp = $user->usersGroupsRoles()->whereHas('Role', function($query) {
            $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
        })->where('group_id', '=', $groupid)->get();
        $cloudRes = app()->make('App\Http\Controllers\CloudResourceApi');

//        foreach ($input['projects'] as $project) {
            $lab = DeployLab::where('project_name', '=', $project['name'])->first();
            $lab->fill(['status' => 'Releasing'])->save();
            $cloudRes->deleteStackV2($project['name']);
//        }
        return Response::json(['status' => 'Success']);
    }

    public function labStatus(Request $request)
    {
        $input = $request->all();
        $cloudRes = app()->make('App\Http\Controllers\CloudResourceApi');
        $project = $input['project'];
        $status = "";
        //foreach ($input['projects'] as $project) {
        $lab = DeployLab::where('project_name', '=', $project)->first();
        $user = Auth::user();
        $groupid=DeployLab::where('project_name', '=', $project)->first()->subgroup->group->id;
        $grp = $user->usersGroupsRoles()->whereHas('Role', function($query) {
            $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
        })->where('group_id', '=', $groupid)->get();
        if (count($grp) == 0){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
            $stack = $cloudRes->getStackV2($project);
            if (is_null($stack)) {
                $lab_status = $lab->getAttribute('status');
                if ($lab->getAttribute('project_id') == "") {
                    $lab->fill(['status' => 'NONE'])->save();
                    $status = 'NONE';
                } else if (in_array($lab_status,['DELETE_IN_PROGRESS', 'DELETE_COMPLETE', 'PROJECT_COMPLETE', 'Releasing'])) {
                    $lab->fill(['status' => 'PROJECT_COMPLETE'])->save();
                    $status = 'PROJECT_COMPLETE';
                }
            } else {
                $status = $stack->getStatus();
                $lab->fill(['status' => $status])->save();
            }
        //}
        return Response::json($status);
    }

    public function delete(Request $request)
    {
        $input = $request->all();
        $project = $input['project'];
        $status = "";
        $user = Auth::user();
        $groupid=DeployLab::where('project_name', '=', $project['name'])->first()->subgroup->group->id;
        $grp = $user->usersGroupsRoles()->whereHas('Role', function($query) {
            $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
        })->where('group_id', '=', $groupid)->get();
        $openstackRes = app()->make('App\Http\Controllers\OpenStackResource');
        $cloudRes = app()->make('App\Http\Controllers\CloudResourceApi');

        $lab = DeployLab::where('project_name', '=', $project['name'])->first();
        $lab->fill(['status' => 'Deleting'])->save();

        $lab->labcontents()->detach();

        $stack = $cloudRes->getStackV2($project);
        if (is_null($stack)) {
            if ($project['id'] == "") {
                $lab->delete();
                $status = 'PROJECT_DELETED';
            }
            else if ($openstackRes->deleteProjectI($project['id'])) {
                $lab->delete();
                $status = 'PROJECT_DELETED';
            } else {
                $status = 'PROJECT_DELETE_FAILED';
                $lab->fill(['status' => $status])->save();
            }
        } else {
            $status = $stack->getStatus();
            $lab->fill(['status' => $status])->save();
        }

        return Response::json($status);
    }

    public function getLabsTable($id)
    {
        $user = Auth::user();
        if($id!=0){
            $grp = $user->usersGroupsRoles()->whereHas('Role', function($query) {
                $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
            })->where('group_id', '=', $id)->get();
            if (count($grp) == 0){
                alert()->warning('You don\'t have permission to do that!');
                return redirect()->back();
            }
        }
        $labs = LabEnv::select('labenv.id', 'labenv.name', 'labenv.description', 'labenv.publicflag as public', 'users.name as created_by', 'labenv.updated_at')
            ->where(function($q) {
                $q->where('labenv.owner_id', '=', $this->user_id);
                $q->orWhere('labenv.publicflag', '=', '1');
            })
//            ->where('labenv.owner_id', '=', $this->user_id)->orWhere('labenv.publicflag', '=', '1')
            ->join('users', 'users.id', '=', 'owner_id');

        return DataTables::of($labs)->addColumn('checkbox', function ($row) { return $row->id; })
            ->editColumn('public', function($row) { return ($row->public) ? 'Public' : 'Private'; })
            ->make(true);
    }

    public function getContentsTale($id)
    {
        $user = Auth::user();
        if($id!=0){
            $grp = $user->usersGroupsRoles()->whereHas('Role', function($query) {
                $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
            })->where('group_id', '=', $id)->get();
            if (count($grp) == 0){
                alert()->warning('You don\'t have permission to do that!');
                return redirect()->back();
            }
        }
        $labs = LabContent::select('labcontent.id', 'labcontent.lab_cat_id as category', 'labcontent.name',
            'labcontent.publicflag as public', 'users.name as created_by', 'labcontent.updated_at')
            ->where(function($q) {
                $q->where('labcontent.owner_id', '=', $this->user_id);
                $q->orWhere('labcontent.publicflag', '=', '1');
            })
//            ->where('labcontent.owner_id', '=', $this->user_id)->orWhere('labcontent.publicflag', '=', '1')
            ->join('users', 'users.id', '=', 'owner_id');

        return DataTables::of($labs)->addColumn('checkbox', function ($row) { return $row->id; })
            ->editColumn('public', function($row) { return ($row->public) ? 'Public' : 'Private'; })
            ->make(true);
    }

    public function assignLabsToTeams(Request $request)
    {
        $input = $request->all();
        $gname = Group::find($input['groupId'])->name;
        $labs = $input['labs'];
        $message = "";
        $user = Auth::user();

            $grp = $user->usersGroupsRoles()->whereHas('Role', function($query) {
                $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
            })->where('group_id', '=', $input['groupId'])->get();
            if (count($grp) == 0){
                alert()->warning('You don\'t have permission to do that!');
                return redirect()->back();
            }

        foreach ($input['labs'] as $lab) {
            $success = [];
            $failed = [];
            $Deploying = LabEnv::find($lab);
            // $labContent = $Deploying->labcontent_id;
            foreach ($input['teams'] as $team) {
                $assign = new DeployLab();
                try {
                    $assign->fill(['subgroup_id' => $team, 'lab_id' => $lab, 'assign_at' => Carbon::now(),
                        'project_name' => $gname . "-" . uniqid(), 'status' => 'NONE'])->save();

//                    if ($labContent != 0) {
//                        $DeployContent = DeployLab::find($assign->id)->labcontents();
//                        $DeployContent->sync($labContent, false);
//                    }

                    array_push($success, Subgroup::find($team)->name);
                } catch (QueryException $e) {
                    array_push($failed, Subgroup::find($team)->name);
                }
            }

            if (count($success) > 0) {
                $message .= 'Lab ' . $Deploying->name . ' is assigned to ' . implode(',', $success) . '.\n';
            }
            if (count($failed) > 0) {
                $message .= 'Lab ' . $Deploying->name . ' was already assigned to ' . implode(',', $failed) . '!\n';
            }
        }

        alert()->warning($message, 'Assign Labs')->persistent('OK');
        return redirect()->back();
    }

    public function assignLabContent(Request $request)
    {
        $input = $request->all();
//        if (count($input['contents']) > 1) {
//            alert()->warning('Please select one lab content only.', 'Assign Lab Contents')->persistent('OK');
//            return redirect()->back();
//        }
        $user = Auth::user();

        $grp = $user->usersGroupsRoles()->whereHas('Role', function($query) {
            $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
        })->where('group_id', '=', $input['groupId'])->get();
        if (count($grp) == 0){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }

        foreach ($input['labs'] as $project) {
            $lab = DeployLab::where('project_name', '=', $project)->first()->labcontents();
            $lab->sync($input['contents'], false);
            $subgroup_id=DeployLab::where('project_name', '=', $project)->first()->subgroup_id;

            foreach($lab->get() as $singlelab){
                $exsited=SubgroupLabHistory::where('subgroup_id','=',$subgroup_id )->where('labcontent_id','=',$singlelab->id)->first();
                if($exsited==null){
                    $labhistory = new SubgroupLabHistory();
                    $labhistory->fill(['user_id' => $this->user_id,'subgroup_id' => $subgroup_id, 'labcontent_id' => $singlelab->id,
                        'grades' => 0])->save();
                }
            }
        }
        alert()->success('Lab contents assigned.', 'Assign Lab Contents')->persistent('OK');
        return redirect()->back();
    }

    public function updateduedate(Request $request)
    {
        $input = $request->all();
        $user = Auth::user();

        $failed = [];
        foreach ($input['contents'] as $content) {
            $groupid=DeployLab::where('id', '=', $content['content_id'])->first()->subgroup->group->id;
            $grp = $user->usersGroupsRoles()->whereHas('Role', function($query) {
                $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
            })->where('group_id', '=', $groupid)->get();
            if (count($grp) == 0){
                alert()->warning('You don\'t have permission to do that!');
                return redirect()->back();
            }
            $lab = DeployLab::where('id', '=', $content['content_id'])->first();
            if (is_null($lab->labcontents())) continue;
            DB::table('deploylab_labcontent')->where('deploylab_id','=',$content['content_id'])->where('referencelabflag','=',0)->update(
                array(
                    'start_at' => Carbon::createFromFormat('D M d Y H:i:s e+', $content['start_at'])->setTimezone('UTC')->toDateTimeString(),
                    'due_at' => Carbon::createFromFormat('D M d Y H:i:s e+', $content['due_at'])->setTimezone('UTC')->toDateTimeString()
                )
            );
//            $lab->labcontents()->update(
//                ['start_at' => Carbon::createFromFormat('D M d Y H:i:s e+', $content['start_at'])->setTimezone('UTC')->toDateTimeString(),
//                    'due_at' => Carbon::createFromFormat('D M d Y H:i:s e+', $content['due_at'])->setTimezone('UTC')->toDateTimeString()]);
        }




        if (count($failed) > 0) {
            $result = ['status' => 'failed', 'projects' => $failed ];
        } else {
            $result = ['status' => 'success'];
        }
        return Response::json($result);
    }

    public function reopenTasks(Request $request)
    {
        $input = $request->all();
        $project = $input['project'];
        $tasks = [];
        $users= [];
        $user = Auth::user();
        $groupid=DeployLab::where('id', '=', $project['id'])->first()->subgroup->group->id;
        $grp = $user->usersGroupsRoles()->whereHas('Role', function($query) {
            $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
        })->where('group_id', '=', $groupid)->get();
        if (count($grp) == 0){
            alert()->warning('You don\'t have permission to do that!');
            Response::json(['status' => 'Failed']);
        }
        $labcontents=DB::table('deploylab_labcontent')->where('deploylab_id','=',$project['id'])->where('referencelabflag','=',0)->get();
        foreach($labcontents as $labcontent){
            $labtasks=DB::table('lab_task')->where('labid','=',$labcontent->labcontent_id)->get();
            foreach($labtasks as $labtask){
                array_push($tasks, $labtask->id);
            }
        }
        $subgroupid=DeployLab::where('id', '=', $project['id'])->first()->subgroup->id;
        $users=DB::table('users_subgroups')->where('subgroup_id', '=', $subgroupid)->where('role_id','=','')->get();
        foreach($users as $user){
            foreach($tasks as $task){
//                if(DB::table('users_groups_roles')->where('user_id','=', $user->user_id)->where('group_id','=', $groupid)->first()->role_id == 7){
                    DB::table('users_tasks')->where('user_id','=', $user->user_id)->where('task_id','=', $task)->where('group_id','=',$groupid)->update(
                        array('finished' => 0)
                    );
//                }

            }
        }
        return Response::json(['status' => 'Success']);
    }

}