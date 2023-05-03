<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/7/18
 * Time: 12:49 AM
 */

namespace App\Http\Controllers;

use App\DataTables\GradingLabsDataTable;
use App\DataTables\GradingLabsAllTaskDataTable;
use App\DataTables\ViewGradeingLabsDataTable;
use App\DeployLab;
use App\LabContent;
use App\LabTask;
use App\Subgroup;
use App\UserGroupRole;
use Auth;
use App\Lab;
use App\User;
use App\Group;
use App\Site;
use App\Role;
use App\DataTables\MylabsDataTable;
use Sentry;
use Response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class GradingController extends Controller
{
    protected $user;

    public function __construct() {
//        $this->middleware(['auth', 'isAdmin']);//isAdmin middleware lets only users with a //specific permission permission to access these resources

        $this->user = Auth::user();
    }

    /**
     * Display a listing of the resource.
     * @param \App\DataTables\MylabsDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function index(ViewGradeingLabsDataTable $dataTable) {
//        $user = Auth::user();
//
//        $grps = $user->usersGroupsRoles()->whereHas('Role', function($query) {
//            $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
//        })->groupBy('group_id')->get();
//
//        $groups = new collection();
//        $g_ids = array();
//        foreach($grps as $group) {
//            $g = Group::find($group->group_id);
//            $site_name = Site::find($g->getAttribute('site_id'))->getAttribute('name');
//            $groups->push( (object) ['id' => $group->group_id, 'name' => $g->getAttribute('name'), 'site' => $site_name ]);
//            array_push($g_ids, $g->getAttribute('id'));
//        }
//
//        if (in_array($id, $g_ids) or ($id == 0)) {
//            return $dataTable->with('id', $id)->render('admin.labs.grading', compact('id', 'groups'));
//        } else {
//            alert()->warning('You have no permission to access this page!', 'Permission Denied')->persistent('OK');
//            return redirect()->back();
//        }
        return $dataTable->render('admin.labs.grading');
//    public function index() {
//        $roles = Role::all();//Get all roles
//
//        return view('admin.roles.index')->with('roles', $roles);
    }

    public function show(GradingLabsDataTable $dataTable,ViewGradeingLabsDataTable $dataTable1,$id,$labid) {
        $user = Auth::user();
        if($id!=0){
            $grp = $user->usersGroupsRoles()->whereHas('Role', function($query) {
                $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
            })->where('group_id', '=', $id)->get();
            if (count($grp) == 0){
//                alert()->warning('You don\'t have permission to do that!');
//                return redirect()->back();
                $role=1;
                $grp = $user->usersGroupsRoles()->whereHas('Role', function($query) {
                    $query->whereIn('name', ['student']);
                })->where('group_id', '=', $id)->get();
                if (count($grp) == 0){
                alert()->warning('You don\'t have permission to do that!');
                return redirect()->back();
                }
            }else{
                $role=0;
            }
        }else{
            $role=0;
        }
        if($role==0){
            $grps = $user->usersGroupsRoles()->whereHas('Role', function($query) {
                $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
            })->groupBy('group_id')->get();
            if(count($grps) == 0){
                alert()->warning('You have no permission to access this page!', 'Permission Denied')->persistent('OK');
                return redirect()->back();
            }

        }elseif($role==1){
            $grps = $user->usersGroupsRoles()->whereHas('Role', function($query) {
                $query->whereIn('name', ['student']);
            })->groupBy('group_id')->get();
        }


        $tasks=new collection();
        $task_ids= array();
        $labtasks=LabTask::where('labid','=',$labid)->get();
        foreach($labtasks as $labtask){
            if(!in_array($labtask->id,$task_ids)){
                $tasks->push( (object) ['id' => $labtask->id, 'name' => $labtask->name ]);
                array_push($task_ids, $labtask->id);
            }
        }
//

        $labs=new collection();
        $lab_ids= array();
        $subgroups= Subgroup::where('group_id','=',$id)->get();
        foreach($subgroups as $subgroup){
            $deploylabs = DeployLab::where('subgroup_id','=',$subgroup->id)->get();
            foreach($deploylabs as $deploylab){
                $labcontents=$deploylab->mainlabcontents()->get();
                foreach($labcontents as $labcontent){
                    if(!in_array($labcontent->id, $lab_ids)){
                            $labs->push( (object) ['id' => $labcontent->id, 'name' => $labcontent->name ]);
                            array_push($lab_ids, $labcontent->id);
                    }

                }
            }
        }
        $groups = new collection();

        $g_ids = array();

        foreach($grps as $group) {
            $g = Group::find($group->group_id);
            //$site_name = Site::find($g->getAttribute('site_id'))->getAttribute('name');
            $groups->push( (object) ['id' => $group->group_id, 'name' => $g->getAttribute('name') ]);
            array_push($g_ids, $g->getAttribute('id'));
        }

        if ((in_array($id, $g_ids) or ($id == 0)) and ($role==0)) {
            return $dataTable->with('id', $id)->with('labid',$labid)->render('admin.labs.grading1', compact('id', 'groups','labs','labid','tasks', 'role'));
        } elseif((in_array($id, $g_ids) or ($id == 0)) and ($role==1)){
            return $dataTable1->with('id', $id)->with('labid',$labid)->render('admin.labs.grading1', compact('id', 'groups','labs','labid','tasks', 'role'));
        } else{
            alert()->warning('You have no permission to access this page!', 'Permission Denied')->persistent('OK');
            return redirect()->back();
        }
//        return $dataTable->render('admin.labs.grading');
//    public function index() {
//        $roles = Role::all();//Get all roles
//
//        return view('admin.roles.index')->with('roles', $roles);
    }

    public function showall(GradingLabsAllTaskDataTable $dataTable,ViewGradeingLabsDataTable $dataTable1,$id,$labid) {
        $user = Auth::user();
        if($id!=0){
            $grp = $user->usersGroupsRoles()->whereHas('Role', function($query) {
                $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
            })->where('group_id', '=', $id)->get();
            if (count($grp) == 0){
//                alert()->warning('You don\'t have permission to do that!');
//                return redirect()->back();
                $role=1;
                $grp = $user->usersGroupsRoles()->whereHas('Role', function($query) {
                    $query->whereIn('name', ['student']);
                })->where('group_id', '=', $id)->get();
                if (count($grp) == 0){
                    alert()->warning('You don\'t have permission to do that!');
                    return redirect()->back();
                }
            }else{
                $role=0;
            }
        }else{
            $role=0;
        }
        if($role==0){
            $grps = $user->usersGroupsRoles()->whereHas('Role', function($query) {
                $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
            })->groupBy('group_id')->get();
            if(count($grps) == 0){
                alert()->warning('You have no permission to access this page!', 'Permission Denied')->persistent('OK');
                return redirect()->back();
            }

        }elseif($role==1){
            $grps = $user->usersGroupsRoles()->whereHas('Role', function($query) {
                $query->whereIn('name', ['student']);
            })->groupBy('group_id')->get();
        }

        if($role==0){
            $tasks=new collection();
            $tasks->prepend((object) ['id' => 6, 'name' => 'Show Grades by Task' ]);
        }

        $labs=new collection();
        $lab_ids= array();
        $subgroups= Subgroup::where('group_id','=',$id)->get();
        foreach($subgroups as $subgroup){
            $deploylabs = DeployLab::where('subgroup_id','=',$subgroup->id)->get();
            foreach($deploylabs as $deploylab){
                $labcontents=$deploylab->labcontents()->get();
                foreach($labcontents as $labcontent){
                    if(!in_array($labcontent->id, $lab_ids)){
                        $labs->push( (object) ['id' => $labcontent->id, 'name' => $labcontent->name ]);
                        array_push($lab_ids, $labcontent->id);
                    }

                }
            }
        }
        $groups = new collection();

        $g_ids = array();

        foreach($grps as $group) {
            $g = Group::find($group->group_id);
            //$site_name = Site::find($g->getAttribute('site_id'))->getAttribute('name');
            $groups->push( (object) ['id' => $group->group_id, 'name' => $g->getAttribute('name') ]);
            array_push($g_ids, $g->getAttribute('id'));
        }

        if ((in_array($id, $g_ids) or ($id == 0)) and ($role==0)) {
            return $dataTable->with('id', $id)->with('labid',$labid)->render('admin.labs.grading2', compact('id', 'groups','labs','labid','tasks', 'role'));
        } elseif((in_array($id, $g_ids) or ($id == 0)) and ($role==1)){
            return $dataTable1->with('id', $id)->with('labid',$labid)->render('admin.labs.grading2', compact('id', 'groups','labs','labid','tasks', 'role'));
        } else{
            alert()->warning('You have no permission to access this page!', 'Permission Denied')->persistent('OK');
            return redirect()->back();
        }
//        return $dataTable->render('admin.labs.grading');
//    public function index() {
//        $roles = Role::all();//Get all roles
//
//        return view('admin.roles.index')->with('roles', $roles);
    }



}

