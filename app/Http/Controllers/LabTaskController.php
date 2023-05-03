<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/7/18
 * Time: 12:49 AM
 */

namespace App\Http\Controllers;

use App\Lab;
use App\User;
use App\Group;
use App\Role;
use App\DataTables\MylabsDataTable;
use Illuminate\Support\Facades\Auth;
use Response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class LabTaskController extends Controller
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
     * @param \App\DataTables\MylabsDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function show($labid,$task) {
        $lab = Lab::find($labid);
        $name=$lab->getAttribute('name');
        $taskcount = $lab->getAttribute('taskcount');
        $done = array(0);
        $done = array_pad($done,$taskcount+1,0);

        $submissions = User::find($this->user)->userSubtaskSubmission()->where('lab_id','=',$labid)->get();
        foreach($submissions as $submission){
            $done[$submission->task_id]=1;
        }

        return view('admin.labs.task', ['lab'=>$labid, 'labname'=> $name,'task'=>$task, 'done'=>$done[$task]]);
//    public function index() {
//        $roles = Role::all();//Get all roles
//
//        return view('admin.roles.index')->with('roles', $roles);
    }





}