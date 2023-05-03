<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/7/18
 * Time: 12:49 AM
 */

namespace App\Http\Controllers;

use App\Lab;
use App\SubgroupTempProjectUuid;
use App\User;
use App\Group;
use App\Role;
use App\DataTables\MylabsDataTable;
use Auth;
use DB;
use Response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class LabEnvController extends Controller
{
    protected $user;

    public function __construct() {
//        $this->middleware(['auth', 'isAdmin']);//isAdmin middleware lets only users with a //specific permission permission to access these resources

        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                redirect('/');
            }

            $this->user = Auth::user()->id;
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     * @param \App\DataTables\SitesDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function index($labid,$task) {
        $lab = Lab::find($labid);
        $name=$lab->getAttribute('name');
        $taskcount = $lab->getAttribute('taskcount');
        $done = array(0);
        $done = array_pad($done,$taskcount+1,0);

        $submissions = User::find($this->user)->userSubtaskSubmission()->where('lab_id','=',$labid)->get();
        foreach($submissions as $submission){
            $done[$submission->task_id]=1;
        }
        //$projectid='0f5feb18e6324d93848f853bc6215bee';
        $projectid=null;
        $subgroupid=null;
        $subgroups=User::find($this->user)->subgroups()->get();
        foreach($subgroups as $subgroup){
            $projects=$subgroup->subgrouptempproject()->where('template_id','=','604')->get();
            foreach($projects as $project){
                $projectname =$project->project_name;
                $uuid=DB::table('project_uuid')->where('project_name',$projectname)->first();
                $projectid =$uuid->uuid;

            }
            if(substr($subgroup->name,0,11)=='CSE468Group'){
                $subgroupid=$subgroup->id;
            }
        }

        if($labid==613){
            return view('admin.labs.env', ['lab'=>$labid,'labname'=> $name, 'task'=>$task,'dones'=>$done,'projectid'=>$projectid, 'taskcount'=>$taskcount]);

        }elseif($labid==614){
            return view('admin.labs.newenv', ['lab'=>$labid,'labname'=> $name, 'task'=>$task,'dones'=>$done,'projectid'=>$projectid, 'taskcount'=>$taskcount]);
        }elseif($labid==615){
            return view('admin.labs.env3', ['lab'=>$labid,'labname'=> $name, 'task'=>$task,'dones'=>$done,'projectid'=>$projectid, 'taskcount'=>$taskcount]);

        }elseif($labid==617){
            return view('admin.labs.env5', ['lab'=>$labid,'labname'=> $name, 'task'=>$task,'dones'=>$done,'projectid'=>$projectid, 'subgroupid'=>$subgroupid, 'userid'=>User::find($this->user)->id, 'taskcount'=>$taskcount]);

        }
        elseif($labid==616){
            return view('admin.labs.env4', ['lab'=>$labid,'labname'=> $name, 'task'=>$task,'dones'=>$done,'projectid'=>$projectid, 'taskcount'=>$taskcount]);

        }
        elseif($labid==618){
            return view('admin.labs.env6', ['lab'=>$labid,'labname'=> $name, 'task'=>$task,'dones'=>$done,'projectid'=>$projectid, 'taskcount'=>$taskcount]);

        }
//    public function index() {
//        $roles = Role::all();//Get all roles
//
//        return view('admin.roles.index')->with('roles', $roles);
    }

    public function cns20002() {
        return view('admin.labs.cns20002');

    }

    public function cns20001() {
        return view('admin.labs.cns20001');

    }
    public function cns10002() {
        return view('admin.labs.cns10002');

    }
    public function cns10001() {
        return view('admin.labs.cns10001');

    }
    public function cns10003() {
        return view('admin.labs.cns10003');

    }
    public function cns00003() {
        return view('admin.labs.cns00003');

    }
    public function cns00001() {
        return view('admin.labs.cns00001');

    }
    public function sys00008() {
        return view('admin.labs.sys00008');

    }
    public function sys00009() {
        return view('admin.labs.sys00009');

    }
    public function conceptmap() {
        return view('admin.labs.conceptmap');

    }




}