<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/7/18
 * Time: 12:49 AM
 */

namespace App\Http\Controllers;

use App\DeployLab;
use App\Labs;
use App\Subgroup;
use Yajra\DataTables\DataTables;
use Auth;
use App\Lab;
use App\LabEnv;
use App\User;
use App\Group;
use App\Role;
use App\DataTables\LabEnvListDataTable;
use Sentry;
use Response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class LabEnvListController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                redirect('/');
            }

            $this->user = Auth::user()->email;
            $this->userid = Auth::user()->id;
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     * @param \App\DataTables\MylabsDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function index(LabEnvListDataTable $dataTable) {
        return $dataTable->render('admin.labs.labenvlist');
//    public function index() {
//        $roles = Role::all();//Get all roles
//
//        return view('admin.roles.index')->with('roles', $roles);
    }

    public function show1($labid) {

        $projectid=DeployLab::find($labid)->project_id;
        return view('admin.labenv.env1', ['projectid'=>$projectid]);
//    public function index() {
//        $roles = Role::all();//Get all roles
//
//        return view('admin.roles.index')->with('roles', $roles);
    }

    public function show2($labid) {
//        if(!is_numeric($labid)){
//            return redirect()->route('404');
//        }
        $user = Auth::user();
        $members_str = "for ";
        activity("LabAccess")->log($labid);

        try {
            $projectid = DeployLab::findOrFail($labid);
        } catch (ModelNotFoundException $e) {
            alert()->warning('The class is not exist!', 'Class Not Found')->persistent('OK');
            return redirect()->route('404');
        }
        $projectid=DeployLab::find($labid)->project_id;
        $subgroupid=DeployLab::find($labid)->subgroup_id;
        $subgrouplist=$user->subgroups()->where("subgroup_id", '=',$subgroupid)->get();
        if(count($subgrouplist)== 0){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        try {
            $labcontent = DeployLab::find($labid)->labcontents()->firstOrFail();
            $labcontentid=$labcontent->id;

        } catch (ModelNotFoundException $e) {
            $labcontentid=0;
        }
        $subgroup= SubGroup::find($subgroupid);

        $members=$subgroup->users()->get();
        $groupid=$subgroup->group()->first()->id;

        foreach ($members as $member) {

            if($member->usersGroupsRoles()->where('group_id','=',$groupid)->whereHas('Role', function($query) {
                    $query->whereIn('name', ['student']);
                })->count()>0){

                $members_str.=$member->email." ";

            }
        }



        $contents=new collection();
        $referencecontents=new collection();
        $content_ids= array();
        $referencecontent_ids= array();
        $labcontents=DeployLab::find($labid)->labcontents()->orderBy('lab_cat_id', 'asc')->get();
        $referencelabcontents=DeployLab::find($labid)->referencelabcontents()->orderBy('lab_cat_id', 'asc')->get();
        foreach($labcontents as $labcontent){
            if(!in_array($labcontent->id,$content_ids)){
                $contents->push( (object) ['id' => $labcontent->id,'labid'=>$labcontent->lab_cat_id, 'name' => $labcontent->name ]);
                array_push($content_ids, $labcontent->id);
            }
        }
        foreach($referencelabcontents as $referencelabcontent){
            if(!in_array($referencelabcontent->id,$referencecontent_ids)){
                $referencecontents->push( (object) ['id' => $referencelabcontent->id, 'labid'=>$referencelabcontent->lab_cat_id, 'name' => $referencelabcontent->name ]);
                array_push($referencecontent_ids, $referencelabcontent->id);
            }
        }
//        $labcontentid=DeployLab::find($labid)->labcontents()->first()->id;
        return view('admin.labenv.env', ['projectid'=>$projectid,'labcontentid'=>$labcontentid,'userid'=>$this->userid,'username'=>$members_str,'subgroupid'=>$subgroupid,'labid'=>$labid, 'contents'=>$contents, 'references'=>$referencecontents]);
//    public function index() {
//        $roles = Role::all();//Get all roles
//
//        return view('admin.roles.index')->with('roles', $roles);
    }

    public function show3($labid) {
//        if(!is_numeric($labid)){
//            return redirect()->route('404');
//        }
        $user = Auth::user();

        activity("LabAccess")->log($labid);

        try {
            $projectid = DeployLab::findOrFail($labid);
        } catch (ModelNotFoundException $e) {
            alert()->warning('The class is not exist!', 'Class Not Found')->persistent('OK');
            return redirect()->route('404');
        }
        $projectid=DeployLab::find($labid)->project_id;
        $subgroupid=DeployLab::find($labid)->subgroup_id;
        $subgrouplist=$user->subgroups()->where("subgroup_id", '=',$subgroupid)->get();
        if(count($subgrouplist)== 0){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        try {
            $labcontent = DeployLab::find($labid)->labcontents()->firstOrFail();
            $labcontentid=$labcontent->id;

        } catch (ModelNotFoundException $e) {
            $labcontentid=0;
        }









        $contents=new collection();
        $referencecontents=new collection();
        $content_ids= array();
        $referencecontent_ids= array();
        $labcontents=DeployLab::find($labid)->labcontents()->orderBy('lab_cat_id', 'asc')->get();
        $referencelabcontents=DeployLab::find($labid)->referencelabcontents()->orderBy('lab_cat_id', 'asc')->get();
        foreach($labcontents as $labcontent){
            if(!in_array($labcontent->id,$content_ids)){
                $contents->push( (object) ['id' => $labcontent->id,'labid'=>$labcontent->lab_cat_id, 'name' => $labcontent->name ]);
                array_push($content_ids, $labcontent->id);
            }
        }
        foreach($referencelabcontents as $referencelabcontent){
            if(!in_array($referencelabcontent->id,$referencecontent_ids)){
                $referencecontents->push( (object) ['id' => $referencelabcontent->id, 'labid'=>$referencelabcontent->lab_cat_id, 'name' => $referencelabcontent->name ]);
                array_push($referencecontent_ids, $referencelabcontent->id);
            }
        }
//        $labcontentid=DeployLab::find($labid)->labcontents()->first()->id;
        return view('admin.labenv.env2', ['projectid'=>$projectid,'labcontentid'=>$labcontentid,'userid'=>$this->userid,'subgroupid'=>$subgroupid,'labid'=>$labid, 'contents'=>$contents, 'references'=>$referencecontents]);
//    public function index() {
//        $roles = Role::all();//Get all roles
//
//        return view('admin.roles.index')->with('roles', $roles);
    }

    public function getLabAll(Request $request) {
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

    public function labStatus(Request $request)
    {
        $input = $request->all();
        $cloudRes = app()->make('App\Http\Controllers\CloudResourceApi');
        $projectname=$input['project'];
        $user = Auth::user();
//        foreach ($input['projects'] as $project) {
        $lab = LabEnv::where('project_name', '=', $projectname)->first();
        $ownerid=$lab->owner->id;
        if ($ownerid!=$user->id){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        $status = "";
        //foreach ($input['projects'] as $project) {

        $stack = $cloudRes->getStackV2($projectname);
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

    public function deploy(Request $request)
    {
        $input = $request->all();
        $project = $input['project'];
        $user = Auth::user();
//        foreach ($input['projects'] as $project) {
        $lab = LabEnv::where('project_name', '=', $project['name'])->first();
        $ownerid=$lab->owner->id;
        if ($ownerid!=$user->id){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        $openstackRes = app()->make('App\Http\Controllers\OpenStackResource');
        $cloudRes = app()->make('App\Http\Controllers\CloudResourceApi');

//        foreach ($input['projects'] as $project) {


        if ($lab->status === 'NONE') {
            $member = $lab->owner()->first()->email;

            $lab->fill(['status' => 'CREATE_PROJECT'])->save();
            $tenant = $openstackRes->createProjectforLabTestEnv($project, $member);
            if (is_null($tenant)) {
                $lab->fill(['status' => 'PROJECT_FAILED'])->save();
                return Response::json(['status' => 'Failed', 'message' => 'Create Project ' . $project . ' Failed!']);
            } else {
                $lab->fill(['status' => 'PROJECT_COMPLETE', 'project_id' => $tenant->id])->save();
            }
            $openstackRes->grantRoleToProjectUser($tenant->id, $this->user, config('openstack.heat_stack_owner_role_id'));
        }

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
//        foreach ($input['projects'] as $project) {
        $lab = LabEnv::where('project_name', '=', $project['name'])->first();
        $ownerid=$lab->owner->id;
        if ($ownerid!=$user->id){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        $cloudRes = app()->make('App\Http\Controllers\CloudResourceApi');

        $lab->fill(['status' => 'Releasing'])->save();
        $cloudRes->deleteStackV2($project['name']);
//        }
        return Response::json(['status' => 'Success']);
    }

    public function delete(Request $request)
    {
        $input = $request->all();
        $project = $input['project'];
        $status = "";
        $user = Auth::user();
//        foreach ($input['projects'] as $project) {
        $lab1 = LabEnv::where('project_name', '=', $project['name'])->first();
        $ownerid=$lab1->owner->id;
        if ($ownerid!=$user->id){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        $openstackRes = app()->make('App\Http\Controllers\OpenStackResource');
        $cloudRes = app()->make('App\Http\Controllers\CloudResourceApi');

        $labs = LabEnv::where('project_id', '=', $project['id']);
        $lab = $labs->first();
        $lab->fill(['status' => 'Deleting'])->save();

        $stack = $cloudRes->getStackV2($project);
        if (is_null($stack)) {
            if ($openstackRes->deleteProjectI($project['id'])) {
                $labs->delete();
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

    public function saveTemp(Request $request)
    {
        $input = $request->all();
        $labenv = new LabEnv();
        $lab = new Labs();

        try {

            $labenv->fill(['name' => $input['temp_name'], 'description' => $input['temp_des'], 'template' => $input['temp'], 'lab_vis_json' => $input['temp_design'], 'publicflag' => 0, 'owner_id' => $this->userid, 'resource' => $input['temp_vmcount'], 'status'=>'NONE','project_name'=>'DesignTemp' . "-" . uniqid()])->save();
            $labenv =LabEnv::select('id')->where('name','=',$input['temp_name'])->first()->id;
            $lab->fill(['name' => $input['temp_name'], 'description' => $input['temp_des'],'labenv_id' =>$labenv,'labcontent_id' =>0,'owner_id' => $this->userid])->save();
        } catch (QueryException $e) {
            alert()->error('Temp already exist!')->persistent('OK');
        }
//        alert()->success('Lab Environment ' . $labenv->getAttribute('name') . ' created!')->persistent('OK');
        return Response::JSON(['status'=>'Success']);
    }

    public function deleteTemp(Request $request)
    {
        $input = $request->all();
        $ownerid=LabEnv::findOrFail($input['id'])->owner->id;
        $userid = Auth::user()->id;
        if ($ownerid!=$userid){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        $deploylab=DeployLab::where('lab_id','=',$input['id'])->first();

        if($deploylab){
            $groupname=Group::find(Subgroup::find($deploylab->subgroup_id)->group_id)->name;
            return Response::JSON(['status'=>'eFailed','groupname'=>$groupname]);
        }else{
            try {
                           LabEnv::findOrFail($input['id'])->delete();

            } catch (QueryException $e) {
                //alert()->error('Temp already exist!')->persistent('OK');
                return Response::JSON(['status'=>'Failed']);
            }
            //alert()->success('Lab Environment ' . $labcontent->getAttribute('id') . ' created!')->persistent('OK');
            return Response::JSON(['status'=>'Success']);
        }

    }

    function getDeployedLabTable() {
        $labs = LabEnv::select('project_id', 'name', 'description')->where('status','=','CREATE_COMPLETE')->where('owner_id','=',Auth::user()->id)->get();
        return DataTables::of($labs)->make(true);
        //->addColumn('status', function($row) { return $this->getUserStatus($row->getAttribute('id')); })->make(true);
    }
}