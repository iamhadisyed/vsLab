<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/7/18
 * Time: 12:49 AM
 */

namespace App\Http\Controllers;

use App\Lab;
use App\LabEnv;
use App\SubgroupTempProjectUuid;
use App\User;
use App\Group;
use App\Role;
use App\DataTables\MylabsDataTable;
use Auth;

use DB;
use Response;
use Carbon\Carbon;
use Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class LabEnvDesignController extends Controller
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
    public function index() {



            return view('admin.labs.envdesign');


//    public function index() {
//        $roles = Role::all();//Get all roles
//
//        return view('admin.roles.index')->with('roles', $roles);
    }

    public function show($labenvid) {
        $labenv = LabEnv::find($labenvid);
        if ($labenv==null){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        $ownerid=$labenv->owner->id;
        $userid = Auth::user()->id;
        if ($ownerid!=$userid){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        $name=$labenv->getAttribute('name');


        return view('admin.labs.loadenvdesign',['labenvid'=>$labenvid,'name'=>$name]);


//    public function index() {
//        $roles = Role::all();//Get all roles
//
//        return view('admin.roles.index')->with('roles', $roles);
    }
    public function getTempDesign($tempId)
    {
        if (Request::ajax()) {
            // profile database


            $env=LabEnv::where('id','=',$tempId)->first();
            $temp=$env->getAttribute('template');
            $tempvis=$env->getAttribute('lab_vis_json');

            $lab_temp = array("temp" => $temp, "temp_vis" => $tempvis);

            // close connection
//                mysqli_close($conn);
        }
        return Response::json($lab_temp);

//        if (count($lab_temp) > 0) {
//            $response = array('status' => 'success', 'template' => $lab_temp);
//        } else
//            $response = array('status' => 'Fail', 'template' => null);
//
//        return $response;
    }

    public function updateTemp()
    {
        if (Request::ajax()) {
            $input = Request::all();
            $labenv = LabEnv::find($input['temp_id']);
            if ($labenv==null){
                alert()->warning('You don\'t have permission to do that!');
                return redirect()->back();
            }
            $ownerid=$labenv->owner->id;
            $userid = Auth::user()->id;
            if ($ownerid!=$userid){
                alert()->warning('You don\'t have permission to do that!');
                return redirect()->back();
            }
            try {

                    $id= DB::table('labenv')->where('id',$input['temp_id'])->
                    update(
                        array(
                            'template'=>$input['temp'],
                            'lab_vis_json'=>$input['temp_design'],
                            'resource'=>$input['temp_vmcount'],
                        )
                    );



                //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                //$result = $conn->query($sql);
                //$result=DB::insert($sql);
                $response = array(
                    'status' => 'Success',
                    'id'=>$id

                );
                return Response::json($response);
            } catch (BadResponseException $e) {
                $response = array(
                    'status' => 'Fail'

                );
                return Response::json($response);
            }
            }

    }

    public function getResources()
    {
        if (Request::ajax()) {
            $ret = array('images' => $this->getImages());
            return Response::json($ret);
        }
    }

    public function getImages()
    {

        $images = DB::table('vmimage')->get();
        $image_list = array();
        foreach ($images as $image) {
            if($image->name != 'Quagga-Router-Ubuntu-14.04-Server-64-150406') {
                $im = array(

                    'name' => $image->name,

                    'id' => $image->id,

                );
                array_push($image_list, $im);}
        }
        foreach ($image_list as $key => $row){
            $name[$key]=$row['name'];
        }
        array_multisort($name,SORT_ASC,SORT_STRING,$image_list);
//        if (Request::ajax())
//            return Response::json($image_list);
        return $image_list;
    }

}