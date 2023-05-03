<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/7/18
 * Time: 12:49 AM
 */

namespace App\Http\Controllers;

use App\Lab;
use App\DeployLab;
use App\LabContent;
use App\Subgroup;
use App\User;
use App\Group;
use App\Role;
use App\UserSubtaskSubmission;
use App\LabcontentFile;
use App\DataTables\MylabsDataTable;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Http\Request;
use Response;
use Carbon\Carbon;
use Request;
use Input;
use mysqli;
use DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class LabSubmissionController extends Controller implements HasMedia
{
    use HasMediaTrait;
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
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        if (Request::ajax()) {

//            if (Session::token() !== Input::get('_token')) {
//                return Response::json(array('msg' => 'Unauthorized attempt to create project.'));
//            }
            //if (!in_array($this->user, $this->allow_create_project)) {
            $taskid = Input::get('taskid');
            $labid = Input::get('labid');
            $subtaskid = Input::get('subtaskid');
            $title = Input::get('title');
            $answer = Input::get('answer');
            $desc = Input::get('desc');
            $type = Input::get('type');
            $source = Input::get('source');
//            $answer = str_replace('"', "'", $answer);


                // group role
                try {
                    $id= DB::table('users_subtasks_submission')->insertGetId(
                        array('lab_id'=>$labid,
                            'task_id'=>$taskid,
                            'subtask_id'=>$subtaskid,
                            'user_id'=>$this->user,
                            'title'=>$title,
                            'submission'=>$answer,
                            'desc'=>$desc,
                            'type'=>$type,
                            'source'=>$source
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
                // close connection
//                mysqli_close($conn);
            }



        }


    public function groupsubmit()
    {
        if (Request::ajax()) {

//            if (Session::token() !== Input::get('_token')) {
//                return Response::json(array('msg' => 'Unauthorized attempt to create project.'));
//            }
            //if (!in_array($this->user, $this->allow_create_project)) {
            $taskid = Input::get('taskid');
//            $labid = Input::get('labid');
            $labid=DB::table('lab_task')->find($taskid)->labid;
            $subtaskid = Input::get('subtaskid');
            $title = Input::get('title');
            $answer = Input::get('answer');
            $desc = Input::get('desc');
            $type = Input::get('type');
            $source = Input::get('source');
            $subgroupid = Input::get('subgroupid');
//            $answer = str_replace('"', "'", $answer);
            $started = DB::table('users_tasks')->where('task_id', '=',$taskid)->where('user_id','=',$this->user)->first();

            $groupid = Subgroup::find($subgroupid)->group()->first()->id;
            // group role
            if ($type !=3){
                try {
                    if($started==null){
                        DB::table('users_tasks')->insert(array(
                            'task_id'=> $taskid,
                            'user_id'=>$this->user,
                            'started'=> 1,
                            'group_id'=>$groupid
                        ));
                    }
                    $id= DB::table('users_subtasks_submission')->insertGetId(
                        array('lab_id'=>$labid,
                            'task_id'=>$taskid,
                            'subtask_id'=>$subtaskid,
                            'user_id'=>$this->user,
                            'title'=>$title,
                            'submission'=>$answer,
                            'desc'=>$desc,
                            'type'=>$type,
                            'source'=>$source,
                            'subgroup_id'=>$subgroupid,
                            'group_id'=>$groupid
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
            }else{
                $submitted = DB::table('users_subtasks_submission')->where('subgroup_id', '=',$subgroupid)->where('task_id', '=',$taskid)->where('user_id','=',$this->user)->where('type','=',3)->first();
                if($submitted==null){
                    try {
                        if($started==null){
                            DB::table('users_tasks')->insert(array(
                                'task_id'=> $taskid,
                                'user_id'=>$this->user,
                                'started'=> 1,
                                'group_id'=>$groupid
                            ));
                        }
                        $id= DB::table('users_subtasks_submission')->insertGetId(
                            array('lab_id'=>$labid,
                                'task_id'=>$taskid,
                                'subtask_id'=>$subtaskid,
                                'user_id'=>$this->user,
                                'title'=>$title,
                                'submission'=>$answer,
                                'desc'=>$desc,
                                'type'=>$type,
                                'source'=>$source,
                                'subgroup_id'=>$subgroupid,
                                'group_id'=>$groupid
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
                }else{
                    try {
                        $id= DB::table('users_subtasks_submission')->where('subgroup_id', '=',$subgroupid)->where('task_id', '=',$taskid)->where('user_id','=',$this->user)->where('type','=',3)->
                        update(
                            array(
                               'desc'=>$desc,
                            )
                        );
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

            // close connection
//                mysqli_close($conn);
        }



    }

    public function updatetext(){
        if (Request::ajax()) {
            $taskid = Input::get('taskid');
            $desc = Input::get('desc');
            $subgroupid = Input::get('subgroupid');
            try {
                $id = DB::table('users_subtasks_submission')->where('subgroup_id', '=', $subgroupid)->where('task_id', '=', $taskid)->where('user_id', '=', $this->user)->where('type', '=', 3)->
                update(
                    array(
                        'desc' => $desc,
                    )
                );
                $response = array(
                    'status' => 'Success',
                    'id' => $id
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

    public function feedbacksubmit()
    {
        if (Request::ajax()) {


            //if (!in_array($this->user, $this->allow_create_project)) {

            $labid = Input::get('labid');
            $labcontentid = Input::get('labcontentid');
            $title = Input::get('title');
            $answer = Input::get('answer');
            $desc = Input::get('desc');
            $subgroupid = Input::get('subgroupid');
//            $answer = str_replace('"', "'", $answer);
            $groupid = Subgroup::find($subgroupid)->group()->first()->id;
            if ($title=='') {
                $response = array(
                    'status' => 'Fail',
                    'msg'=>'Please input both subject and description before submit!'
                );
                return Response::json($response);
            }
            if ($desc=='') {
                $response = array(
                    'status' => 'Fail',
                    'msg'=>'Please input both subject and description before submit!'
                );
                return Response::json($response);
            }
            // group role
            try {

                $id= DB::table('users_feedbacks')->insertGetId(
                    array('lab_id'=>$labid,
                        'userid'=>$this->user,
                        'title'=>$title,
                        'submission'=>$answer,
                        'desc'=>$desc,
                        'subgroup_id'=>$subgroupid,
                        'group_id'=>$groupid,
                        'labcontentid'=>$labcontentid,
                    )
                );


                $response = array(
                    'status' => 'Success',
                    'id'=>$id,
                    'msg'=>'Thank you! We\'ll get back to you ASAP!'

                );
                return Response::json($response);
            } catch (BadResponseException $e) {
                $response = array(
                    'status' => 'Fail'

                );
                return Response::json($response);
            }
            // close connection
//                mysqli_close($conn);
        }
    }

    public function uploadfile()
    {


        $file = null;
        if (Request::ajax()) {

//            if (Session::token() !== Input::get('_token')) {
//                return Response::json(array('msg' => 'Unauthorized attempt to create project.'));
//            }
            //if (!in_array($this->user, $this->allow_create_project)) {
            $input = Request::all();

//            $answer = str_replace('"', "'", $answer);


            // group role
            try {
                $id= DB::table('users_subtasks_submission')->insertGetId(
                    array('lab_id'=> $input['labid'],
                        'task_id'=> $input['taskid'],
                        'subtask_id'=> $input['subtaskid'],
                        'user_id'=>$this->user,


                        'desc'=> $input['desc'],
                        'type'=>2

                    )
                );
                $submission = UserSubtaskSubmission::find($id);

                $submission->clearMediaCollection('submissions');
                $submission->addMediaFromRequest('file')->addCustomHeaders(['ACL' => 'public-read'])
                    ->toMediaCollection('submissions', 'ceph-submission');

                $file = $submission->getMedia('submissions')->first()->getUrl();
                //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                //$result = $conn->query($sql);
                //$result=DB::insert($sql);
                DB::table('users_subtasks_submission')->where('id',$id)->
                update(
                    array(

                        'submission'=>$file

                    )
                );
                $response = array(
                    'status' => 'Success',
                    'id'=>$id,
                    'url'=>$file

                );
                return Response::json($response);
            } catch (BadResponseException $e) {
                $response = array(
                    'status' => 'Fail'

                );
                return Response::json($response);
            }
            // close connection
//                mysqli_close($conn);
        }



    }

    public function uploadpdfforlab()
    {


        $file = null;
        if (Request::ajax()) {

//            if (Session::token() !== Input::get('_token')) {
//                return Response::json(array('msg' => 'Unauthorized attempt to create project.'));
//            }
            //if (!in_array($this->user, $this->allow_create_project)) {
            $input = Request::all();

//            $answer = str_replace('"', "'", $answer);


            // group role
            $labcontentid=$input['labid'];
            try {
                $id= DB::table('labcontent_files')->insertGetId(
                    array('labcontent_id'=> $input['labid'],
                        'user_id'=>$this->user,


                        'desc'=> 'pdf'


                    )
                );
                $submission = LabcontentFile::find($id);

                $submission->clearMediaCollection('submissions');
                $submission->addMediaFromRequest('file')->addCustomHeaders(['ACL' => 'public-read'])
                    ->toMediaCollection('submissions', 'ceph-submission');

                $file = $submission->getMedia('submissions')->first()->getUrl();
                //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                //$result = $conn->query($sql);
                //$result=DB::insert($sql);
                DB::table('labcontent_files')->where('id',$id)->
                update(
                    array(

                        'fileurl'=>$file

                    )
                );
                DB::table('labcontent')->where('id',$labcontentid)->
                update(
                    array(

                        'pdfurl'=>$file,
                        'pdfflag'=>1

                    )
                );
                $response = array(
                    'status' => 'Success',
                    'id'=>$id,
                    'url'=>$file

                );
                return Response::json($response);
            } catch (BadResponseException $e) {
                $response = array(
                    'status' => 'Fail'

                );
                return Response::json($response);
            }
            // close connection
//                mysqli_close($conn);
        }



    }

    public function uploadfileforlab()
    {


        $file = null;
        if (Request::ajax()) {

//            if (Session::token() !== Input::get('_token')) {
//                return Response::json(array('msg' => 'Unauthorized attempt to create project.'));
//            }
            //if (!in_array($this->user, $this->allow_create_project)) {
            $input = Request::all();

//            $answer = str_replace('"', "'", $answer);


            // group role
            try {
                $id= DB::table('labcontent_files')->insertGetId(
                    array('labcontent_id'=> $input['labid'],
                        'user_id'=>$this->user,


                        'desc'=> $input['desc'],


                    )
                );
                $submission = LabcontentFile::find($id);

                $submission->clearMediaCollection('submissions');
                $submission->addMediaFromRequest('file')->addCustomHeaders(['ACL' => 'public-read'])
                    ->toMediaCollection('submissions', 'ceph-submission');

                $file = $submission->getMedia('submissions')->first()->getUrl();
                //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                //$result = $conn->query($sql);
                //$result=DB::insert($sql);
                DB::table('labcontent_files')->where('id',$id)->
                update(
                    array(

                        'fileurl'=>$file

                    )
                );
                $response = array(
                    'status' => 'Success',
                    'id'=>$id,
                    'url'=>$file

                );
                return Response::json($response);
            } catch (BadResponseException $e) {
                $response = array(
                    'status' => 'Fail'

                );
                return Response::json($response);
            }
            // close connection
//                mysqli_close($conn);
        }



    }

    public function groupuploadfile()
    {


        $file = null;
        if (Request::ajax()) {

//            if (Session::token() !== Input::get('_token')) {
//                return Response::json(array('msg' => 'Unauthorized attempt to create project.'));
//            }
            //if (!in_array($this->user, $this->allow_create_project)) {
            $input = Request::all();

//            $answer = str_replace('"', "'", $answer);

            $started = DB::table('users_tasks')->where('task_id', '=',$input['taskid'])->where('user_id','=',$this->user)->first();
            $groupid = Subgroup::find($input['subgroupid'])->group()->first()->id;
            $user = Auth::user();

                $grp = $user->usersGroupsRoles()->whereHas('Role', function($query) {
                    $query->whereIn('name', ['instructor', 'TA', 'group_owner','student']);
                })->where('group_id', '=', $groupid)->get();
                if (count($grp) == 0){
                    alert()->warning('You don\'t have permission to do that!');
                    return redirect()->back();
                }

            // group role
            try {
                if($started==null){
                    DB::table('users_tasks')->insert(array(
                        'task_id'=> $input['taskid'],
                        'user_id'=>$this->user,
                        'started'=> 1,
                        'group_id'=>$groupid
                    ));
                }

                $id= DB::table('users_subtasks_submission')->insertGetId(
                    array('lab_id'=> $input['labid'],
                        'task_id'=> $input['taskid'],
                        'subtask_id'=> $input['subtaskid'],
                        'user_id'=>$this->user,


                        'desc'=> $input['desc'],
                        'type'=>2,
                        'subgroup_id'=>$input['subgroupid'],
                        'group_id'=>$groupid

                    )
                );
                $filename=pathinfo($input['file']->getClientOriginalName());

                $submission = UserSubtaskSubmission::find($id);
                $email=Auth::user()->email;
                $shifted=explode('@', $email);
                $username = array_shift($shifted);

                $submission->clearMediaCollection('submissions');
                if(empty($filename['extension'])){
                    $submission->addMediaFromRequest('file')->usingFileName($filename['filename'].'-'.$username)->addCustomHeaders(['ACL' => 'public-read'])
                        ->toMediaCollection('submissions', 'ceph-submission');
                }else{
                    $submission->addMediaFromRequest('file')->usingFileName($filename['filename'].'-'.$username.'.'.$filename['extension'])->addCustomHeaders(['ACL' => 'public-read'])
                        ->toMediaCollection('submissions', 'ceph-submission');
                }


                $file = $submission->getMedia('submissions')->first()->getUrl();
                //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                //$result = $conn->query($sql);
                //$result=DB::insert($sql);
                DB::table('users_subtasks_submission')->where('id',$id)->
                update(
                    array(

                        'submission'=>$file

                    )
                );
                $response = array(
                    'status' => 'Success',
                    'id'=>$id,
                    'url'=>$file

                );
                return Response::json($response);
            } catch (BadResponseException $e) {
                $response = array(
                    'status' => 'Fail'

                );
                return Response::json($response);
            }
            // close connection
//                mysqli_close($conn);
        }



    }

    public function uploadsurvey()
    {



        if (Request::ajax()) {

//            if (Session::token() !== Input::get('_token')) {
//                return Response::json(array('msg' => 'Unauthorized attempt to create project.'));
//            }
            //if (!in_array($this->user, $this->allow_create_project)) {
            $input = Request::all();

//            $answer = str_replace('"', "'", $answer);

            $submission=json_encode($input);
            // group role
            try {
                $id= DB::table('users_subtasks_submission')->insertGetId(
                    array('lab_id'=> $input['labid'],
                        'task_id'=> $input['taskid'],
                        'subtask_id'=> 1,
                        'user_id'=>$this->user,
                        'submission'=>$submission,
                        'desc'=> $input['q14'],
                        'type'=>3

                    )
                );
                DB::table('users_tasks')->insertGetId(
                    array('user_id'=>$this->user,
                        'task_id'=>$input['taskid'],
                        'finished'=>1
                    )
                );
                $response = array(
                    'status' => 'Success',
                    'id'=>$id,


                );
                return Response::json($response);
            } catch (BadResponseException $e) {
                $response = array(
                    'status' => 'Fail'

                );
                return Response::json($response);
            }
            // close connection
//                mysqli_close($conn);
        }



    }



    public function updatesubmission()
    {
        if (Request::ajax()) {

//            if (Session::token() !== Input::get('_token')) {
//                return Response::json(array('msg' => 'Unauthorized attempt to create project.'));
//            }
            //if (!in_array($this->user, $this->allow_create_project)) {
            $submissionid=Input::get('submissionid');
            $title = Input::get('title');
            $answer = Input::get('answer');
            $desc = Input::get('desc');
            $source =Input::get('source');
//            $answer = str_replace('"', "'", $answer);


            // group role
            try {
                if($source===null){
                    $id= DB::table('users_subtasks_submission')->where('id',$submissionid)->
                    update(
                        array(
                            'title'=>$title,
                            'submission'=>$answer,
                            'desc'=>$desc,
                        )
                    );
                }else{
                    $id= DB::table('users_subtasks_submission')->where('id',$submissionid)->
                    update(
                        array(
                            'title'=>$title,
                            'submission'=>$answer,
                            'desc'=>$desc,
                            'source'=>$source
                        )
                    );
                }


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
            // close connection
//                mysqli_close($conn);
        }



    }

    public function show($submissionid)
    {
        if (Request::ajax()) {
            try {
                $submission = DB::table('users_subtasks_submission')->where('id', $submissionid)->first();


                //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                //$result = $conn->query($sql);
                //$result=DB::insert($sql);
                $response = array(
                    'status' => 'Success',
                    'dataURL' => $submission->submission,
                    'title' => $submission->title,
                    'desc' => $submission->desc,


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

    public function getsubmissionbytask()
    {
        if (Request::ajax()) {
            $taskid = Input::get('taskid');


            try {
                $submissions = DB::table('users_subtasks_submission')->where('task_id', $taskid)->whereNotNull('type')->where('user_id',$this->user)->get();


                //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                //$result = $conn->query($sql);
                //$result=DB::insert($sql);
                $response = json_decode(json_encode($submissions), True);

                return Response::json($response);
            } catch (BadResponseException $e) {
                $response = array(
                    'status' => 'Fail'

                );
                return Response::json($response);
            }
        }
    }

    public function getsubmissionbytaskanduser()
    {
        if (Request::ajax()) {
            $taskid = Input::get('taskid');
            $userid = Input::get('userid');


            try {
                $submissions = DB::table('users_subtasks_submission')->where('task_id', $taskid)->whereNotNull('type')->where('user_id',$userid)->get();


                //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                //$result = $conn->query($sql);
                //$result=DB::insert($sql);
                $response = json_decode(json_encode($submissions), True);

                return Response::json($response);
            } catch (BadResponseException $e) {
                $response = array(
                    'status' => 'Fail'

                );
                return Response::json($response);
            }
        }
    }

    public function getsubmissionbytaskandsubgroup()
    {
        if (Request::ajax()) {
            $taskid = Input::get('taskid');
            $subgroupid = Input::get('subgroupid');


            try {
                $submissions = DB::table('users_subtasks_submission')->where('task_id', $taskid)->whereNotNull('type')->where('subgroup_id',$subgroupid)->get();


                //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                //$result = $conn->query($sql);
                //$result=DB::insert($sql);
                foreach($submissions as $submission){
                $submission->username=User::find($submission->user_id)->email;
                }
                $response = json_decode(json_encode($submissions), True);

                return Response::json($response);
            } catch (BadResponseException $e) {
                $response = array(
                    'status' => 'Fail'

                );
                return Response::json($response);
            }
        }
    }

    public function getfilebylab()
    {
        if (Request::ajax()) {
            $labcontent_id = Input::get('labcontent_id');



            try {
                $submissions = DB::table('labcontent_files')->where('labcontent_id', $labcontent_id)->get();


                //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                //$result = $conn->query($sql);
                //$result=DB::insert($sql);
                foreach($submissions as $submission){
                    $submission->username=User::find($submission->user_id)->email;
                }
                $response = json_decode(json_encode($submissions), True);

                return Response::json($response);
            } catch (BadResponseException $e) {
                $response = array(
                    'status' => 'Fail'

                );
                return Response::json($response);
            }
        }
    }

    public function destroy()
    {
        if (Request::ajax()) {
            $submissionid = Input::get('submissionid');
//            if (Session::token() !== Input::get('_token')) {
//                return Response::json(array('msg' => 'Unauthorized attempt to create project.'));
//            }
            //if (!in_array($this->user, $this->allow_create_project)) {

//            $answer = str_replace('"', "'", $answer);


            // group role
            try {
                $result= DB::table('users_subtasks_submission')->where('id',$submissionid)->delete();
                $submission = UserSubtaskSubmission::find($submissionid);

                $submission->clearMediaCollection('submissions');

                //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                //$result = $conn->query($sql);
                //$result=DB::insert($sql);
                $response = array(
                    'status' => 'Success',


                );
                return Response::json($response);
            } catch (BadResponseException $e) {
                $response = array(
                    'status' => 'Fail'

                );
                return Response::json($response);
            }
            // close connection
//                mysqli_close($conn);
        }



    }

    public function deletepdfforlab()
    {
        if (Request::ajax()) {
            $fileid = Input::get('fileid');
            $labid = Input::get('labid');
//            if (Session::token() !== Input::get('_token')) {
//                return Response::json(array('msg' => 'Unauthorized attempt to create project.'));
//            }
            //if (!in_array($this->user, $this->allow_create_project)) {

//            $answer = str_replace('"', "'", $answer);


            // group role
            try {
                $submission = LabcontentFile::find($fileid);

                $submission->clearMediaCollection('submissions');
                $result= DB::table('labcontent_files')->where('id',$fileid)->delete();
                $result= DB::table('labcontent')->where('id',$labid)->
                update(
                    array(

                        'pdfurl'=>'',
                        'pdfflag'=>0

                    )
                );


                //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                //$result = $conn->query($sql);
                //$result=DB::insert($sql);
                $response = array(
                    'status' => 'Success',


                );
                return Response::json($response);
            } catch (BadResponseException $e) {
                $response = array(
                    'status' => 'Fail'

                );
                return Response::json($response);
            }
            // close connection
//                mysqli_close($conn);
        }



    }

    public function deletefileforlab()
    {
        if (Request::ajax()) {
            $fileid = Input::get('fileid');
//            if (Session::token() !== Input::get('_token')) {
//                return Response::json(array('msg' => 'Unauthorized attempt to create project.'));
//            }
            //if (!in_array($this->user, $this->allow_create_project)) {

//            $answer = str_replace('"', "'", $answer);


            // group role
            try {
                $submission = LabcontentFile::find($fileid);

                $submission->clearMediaCollection('submissions');
                $result= DB::table('labcontent_files')->where('id',$fileid)->delete();


                //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                //$result = $conn->query($sql);
                //$result=DB::insert($sql);
                $response = array(
                    'status' => 'Success',


                );
                return Response::json($response);
            } catch (BadResponseException $e) {
                $response = array(
                    'status' => 'Fail'

                );
                return Response::json($response);
            }
            // close connection
//                mysqli_close($conn);
        }



    }

    public function checktaskfinished(){
        if (Request::ajax()) {
            $taskid = Input::get('taskid');


            try {
                $response = DB::table('users_tasks')->where('task_id', $taskid)->where('user_id',$this->user)->get();


                //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                //$result = $conn->query($sql);
                //$result=DB::insert($sql);
                if(!DB::table('users_tasks')->where('task_id', $taskid)->where('user_id',$this->user)->exists()){
                    $response=array(0=>array("finished"=>0));

                }
                return Response::json($response);
            } catch (BadResponseException $e) {
                $response = array(
                    'status' => 'Fail'

                );
                return Response::json($response);
            }
        }
    }

    public function checktaskgraded(){
        if (Request::ajax()) {
            $taskid = Input::get('taskid');


            try {
                $response = DB::table('users_tasks')->where('task_id', $taskid)->where('user_id',$this->user)->get();


                //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                //$result = $conn->query($sql);
                //$result=DB::insert($sql);
                if(!DB::table('users_tasks')->where('task_id', $taskid)->where('user_id',$this->user)->exists()){
                    $response=array(0=>array("grade"=>0));

                }
                return Response::json($response);
            } catch (BadResponseException $e) {
                $response = array(
                    'status' => 'Fail'

                );
                return Response::json($response);
            }
        }
    }

    public function finishtask(){
        if (Request::ajax()) {
            $taskid = Input::get('taskid');
            $deadline = "2020-11-30 00:00:00.0 -0700";
            if(time()<=strtotime($deadline)){
                try {
                    $data = DB::table('users_tasks')->where('task_id',$taskid)->where('user_id',$this->user)->first();
                    if ($data === null) {
                        $result= DB::table('users_tasks')->insertGetId(
                            array('user_id'=>$this->user,
                                'task_id'=>$taskid,
                                'finished'=>1,
                                'update_time' => date('Y-m-d H:i:s'),
                            )
                        );
                    }elseif($data->finished===1){
                        $result="You have already completed this task, can't resubmit.";

                    }else{
                        $result= DB::table('users_tasks')->where('task_id',$taskid)->where('user_id',$this->user)->
                        update(
                            array(
                                'finished'=>1,
                                'update_time' => date('Y-m-d H:i:s'),
                            )
                        );
                    }



                    //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                    //$result = $conn->query($sql);
                    //$result=DB::insert($sql);

                    return Response::json($result);
                } catch (BadResponseException $e) {
                    $response = array(
                        'status' => 'Fail'

                    );
                    return Response::json($response);
                }
            }else if ($this->user===18){
                try {
                    $data = DB::table('users_tasks')->where('task_id',$taskid)->where('user_id',$this->user)->first();
                    if ($data === null) {
                        $result= DB::table('users_tasks')->insertGetId(
                            array('user_id'=>$this->user,
                                'task_id'=>$taskid,
                                'finished'=>1,
                                'update_time' => date('Y-m-d H:i:s'),
                            )
                        );
                    }elseif($data->finished===1){
                        $result="You have already completed this task, can't resubmit.";

                    }else{
                        $result= DB::table('users_tasks')->where('task_id',$taskid)->where('user_id',$this->user)->
                        update(
                            array(
                                'finished'=>1,
                                'update_time' => date('Y-m-d H:i:s'),
                            )
                        );
                    }



                    //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                    //$result = $conn->query($sql);
                    //$result=DB::insert($sql);

                    return Response::json($result);
                } catch (BadResponseException $e) {
                    $response = array(
                        'status' => 'Fail'

                    );
                    return Response::json($response);
                }
            }else{

            }
//
        }
    }

    public function finishlab(){
        if (Request::ajax()) {
            $labid = Input::get('labid');
            $deadline = "2018-3-17 00:00:00.0 -0700";
            if(time()<=strtotime($deadline)){
                try {
                    $data = DB::table('users_tasks')->where('task_id',$labid)->where('user_id',$this->user)->first();
                    if ($data === null) {
                        $result= DB::table('users_tasks')->insertGetId(
                            array('user_id'=>$this->user,
                                'task_id'=>$labid,
                                'finished'=>1
                            )
                        );
                    }elseif($data->finished===1){
                        $result="You have already completed this task, can't resubmit.";

                    }else{
                        $result= DB::table('users_tasks')->where('task_id',$labid)->where('user_id',$this->user)->
                        update(
                            array(
                                'finished'=>1
                            )
                        );
                    }



                    //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                    //$result = $conn->query($sql);
                    //$result=DB::insert($sql);

                    return Response::json($result);
                } catch (BadResponseException $e) {
                    $response = array(
                        'status' => 'Fail'

                    );
                    return Response::json($response);
                }
            }else if ($this->user===18) {
                try {
                    $data = DB::table('users_tasks')->where('task_id', $labid)->where('user_id', $this->user)->first();
                    if ($data === null) {
                        $result = DB::table('users_tasks')->insertGetId(
                            array('user_id' => $this->user,
                                'task_id' => $labid,
                                'finished' => 1
                            )
                        );
                    } elseif ($data->finished === 1) {
                        $result = "You have already completed this task, can't resubmit.";

                    } else {
                        $result = DB::table('users_tasks')->where('task_id', $labid)->where('user_id', $this->user)->
                        update(
                            array(
                                'finished' => 1
                            )
                        );
                    }


                    //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                    //$result = $conn->query($sql);
                    //$result=DB::insert($sql);

                    return Response::json($result);
                } catch (BadResponseException $e) {
                    $response = array(
                        'status' => 'Fail'

                    );
                    return Response::json($response);
                }
            }
        }
    }



public function gradetask(){
    if (Request::ajax()) {
        $taskid = Input::get('taskid');
        $userid = Input::get('userid');
        $grade= Input::get('grade');
        $feedback= Input::get('feedback');
        $submissionfeedbacks= Input::get('submissionfeedback');
        $user = Auth::user();

            $grp = $user->usersGroupsRoles()->whereHas('Role', function($query) {
                $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
            })->get();
            if (count($grp) == 0){
                alert()->warning('You don\'t have permission to do that!');
                return redirect()->back();
            }


            try {

                    $original=DB::table('users_tasks')->select('grade')->where('task_id',$taskid)->where('user_id',$userid)->get();
                    $result= DB::table('users_tasks')->where('task_id',$taskid)->where('user_id',$userid)->
                    update(
                        array(
                            'grade'=>$grade,
                            'feedback'=>$feedback,
                            'graded'=>1
                        )
                    );

                foreach ($submissionfeedbacks as $submissionid => $submissionfeedback ){
                    $result= DB::table('users_subtasks_submission')->where('id',$submissionid)->
                    update(
                        array(
                            'feedback'=>$submissionfeedback
                        )
                    );
                }
                $taskname=DB::table('lab_task')->where('id',$taskid)->first()->name;

                //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                //$result = $conn->query($sql);
                //$result=DB::insert($sql);
                $message= array($taskname,$original[0]->grade);

                return Response::json($message);
            } catch (BadResponseException $e) {
                $response = array(
                    'status' => 'Fail'

                );
                return Response::json($response);
            }


//
    }
}

public function getgrade()
{

    if (Request::ajax()) {
        $taskid = Input::get('taskid');
        $userid = Input::get('userid');
        $groupid= Input::get('groupid');
        $user = Auth::user();
        if($userid!=$user->id){
            $grp = $user->usersGroupsRoles()->whereHas('Role', function($query) {
                $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
            })->where('group_id', '=', $groupid)->get();
            if (count($grp) == 0){
                alert()->warning('You don\'t have permission to do that!');
                return redirect()->back();
            }
        }

        try {


            $result = DB::table('users_tasks')->where('task_id', $taskid)->where('user_id', $userid)->first();
            $result1 = DB::table('grading_policy')->where('lab_task_id','=',$taskid)->
            where('group_id','=',$groupid)->first();
            if($result==null){
                $result=new \stdClass();
                $result->grade=0;
                $result->feedback='Not graded yet.';
            }
            if($result1==null){
                $result1=new \stdClass();
                $result1->fullgrade=0;
            }
            $result->fullgrade =$result1->fullgrade;

            //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
            //$result = $conn->query($sql);
            //$result=DB::insert($sql);

            return Response::json($result);
        } catch (BadResponseException $e) {
            $response = array(
                'status' => 'Fail'

            );
            return Response::json($response);
        }


//
    }
}

    public function getgradenouserid()
    {
        if (Request::ajax()) {
            $taskid = Input::get('taskid');



            try {


                $result = DB::table('users_tasks')->where('task_id', $taskid)->where('user_id', $this->user)->first();


                //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                //$result = $conn->query($sql);
                //$result=DB::insert($sql);

                return Response::json($result);
            } catch (BadResponseException $e) {
                $response = array(
                    'status' => 'Fail'

                );
                return Response::json($response);
            }


//
        }
    }

    public function gettaskfullgrade(){
        if (Request::ajax()) {
            $taskid = Input::get('taskid');
            $groupid= Input::get('groupid');
            $user = Auth::user();

                $grp = $user->usersGroupsRoles()->whereHas('Role', function($query) {
                    $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
                })->where('group_id', '=', $groupid)->get();
                if (count($grp) == 0){
                    alert()->warning('You don\'t have permission to do that!');
                    return redirect()->back();
                }


            try {


                $result = DB::table('grading_policy')->where('lab_task_id','=',$taskid)->
                where('group_id','=',$groupid)->first();




                //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                //$result = $conn->query($sql);
                //$result=DB::insert($sql);

                return Response::json($result);
            } catch (BadResponseException $e) {
                $response = array(
                    'status' => 'Fail'

                );
                return Response::json($response);
            }


//
        }
    }




    public function gradingpolicy(){
        if (Request::ajax()) {
            $labname =Input::get('labname');
            $labpoint = Input::get('labpoint');
            $taskpoint = Input::get('taskpoint');
            $taskpoint2 = Input::get('taskpoint2');
            $taskviewable=Input::get('taskviewable');
            $taskviewable2=Input::get('taskviewable2');
            $taskpoint3 = Input::get('taskpoint3');
            $taskviewable3=Input::get('taskviewable3');
            if($taskviewable=='true'){
                $taskviewable=1;
            }else{
                $taskviewable=0;
            }
            if($taskviewable2=='true'){
                $taskviewable2=1;
            }else{
                $taskviewable2=0;
            }
            if($taskviewable3=='true'){
                $taskviewable3=1;
            }else{
                $taskviewable3=0;
            }

            try {


                DB::table('lab')->where('name',$labname)->
                update(
                    array(
                        'fullpoints'=>$labpoint,
                    )
                );
                DB::table('lab_task')->where('id',101)->
                update(
                    array(
                        'fullpoints'=>$taskpoint,
                        'viewable'=>$taskviewable,
                    )
                );
                $result= DB::table('lab_task')->where('id',102)->
                update(
                    array(
                        'fullpoints'=>$taskpoint2,
                        'viewable'=>$taskviewable2,
                    )
                );
                $result= DB::table('lab_task')->where('id',103)->
                update(
                    array(
                        'fullpoints'=>$taskpoint3,
                        'viewable'=>$taskviewable3,
                    )
                );



                //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                //$result = $conn->query($sql);
                //$result=DB::insert($sql);

                return Response::json($result);
            } catch (BadResponseException $e) {
                $response = array(
                    'status' => 'Fail'

                );
                return Response::json($response);
            }


//
        }
    }

    public function gradingpolicyforlab(){
        $user = Auth::user();
        if (Request::ajax()) {
            $labid =Input::get('labid');
            $labpoint = Input::get('labpoint');
            $groupid = Input::get('groupid');
            $tasks = Input::get('tasks');
            $grp = $user->usersGroupsRoles()->whereHas('Role', function($query) {
                $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
            })->where('group_id', '=', $groupid)->get();
            if (count($grp) == 0){
                alert()->warning('You don\'t have permission to do that!');
                return redirect()->back();
            }
            try {
                foreach($tasks as $task){
                    $exsit=DB::table('grading_policy')->where('labcontent_id','=',$labid)->
                    where('lab_task_id','=',$task['id'])->where('group_id','=',$groupid)->first();
                    if($exsit!=null){
                        $result=DB::table('grading_policy')->where('labcontent_id','=',$labid)->
                        where('lab_task_id','=',$task['id'])->where('group_id','=',$groupid)->
                        update(
                            array(
                                'fullgrade'=>$task['fullgrade'],
                            )
                        );
                    }else{
                        $result=DB::table('grading_policy')->insert(
                            array(
                                'labcontent_id'=>$labid,
                                'group_id'=>$groupid,
                                'lab_task_id'=>$task['id'],
                                'fullgrade'=>$task['fullgrade'],
                            )
                        );
                    }

                }






                //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                //$result = $conn->query($sql);
                //$result=DB::insert($sql);

                return Response::json($result);
            } catch (BadResponseException $e) {
                $response = array(
                    'status' => 'Fail'

                );
                return Response::json($response);
            }


//
        }
    }

    function getlabgradingpolicyforlab(){
        if (Request::ajax()) {
            $labid =Input::get('labid');
            $groupid =Input::get('groupid');
            $user = Auth::user();

                $grp = $user->usersGroupsRoles()->whereHas('Role', function($query) {
                    $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
                })->where('group_id', '=', $groupid)->get();
                if (count($grp) == 0){
                    alert()->warning('You don\'t have permission to do that!');
                    return redirect()->back();
                }


            try {



                $tasks=DB::table('grading_policy')->where('labcontent_id','=',$labid)->where('group_id','=',$groupid)->get();
                $result=array(array());
                $fullpoints=0;
                foreach($tasks as $task){
                    $result[$task->lab_task_id][0]=$task->fullgrade;
                    $fullpoints=$fullpoints+$task->fullgrade;
                }




                //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                //$result = $conn->query($sql);
                //$result=DB::insert($sql);

                return Response::json([ 'tasksfullpoints' =>  $result ,  'labfullpoints' => $fullpoints ]);
            } catch (BadResponseException $e) {
                $response = array(
                    'status' => 'Fail'

                );
                return Response::json($response);
            }


//
        }
    }
    function getlabgradingpolicy(){
        if (Request::ajax()) {
            $labname =Input::get('labname');


            try {


                $lab=DB::table('lab')->where('name',$labname)->first();
                $tasks=DB::table('lab_task')->where('labid',$lab->id)->get();
                $result=array(array());
                foreach($tasks as $task){
                    $result[$task->seq][1]=$task->fullpoints;
                    $result[$task->seq][0]=$task->viewable;
                }




                //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                //$result = $conn->query($sql);
                //$result=DB::insert($sql);

                return Response::json([ 'tasksfullpoints' =>  $result ,  'labfullpoints' => $lab->fullpoints ]);
            } catch (BadResponseException $e) {
                $response = array(
                    'status' => 'Fail'

                );
                return Response::json($response);
            }


//
        }
    }

    function getlabcontentbylabenv(){
        if (Request::ajax()) {
            $labid =Input::get('labenv');
            $email = Auth::user()->email;
            $userid = Auth::user()->id;
            try {
                $labcontent = DeployLab::find($labid)->labcontents()->firstOrFail();
                $labcontentid=$labcontent->id;
                $labcontentname=$labcontent->name;
                $submissions = DB::table('lab_task')->where('labid', $labcontentid)->orderBy('id','asc')->get();


                //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                //$result = $conn->query($sql);
                //$result=DB::insert($sql);
                $result = json_decode(json_encode($submissions), True);
                $taskids=array();
                for ($x = 0; $x < sizeof($result); $x++) {
                    $taskids[$x][0]=$result[$x]['id'];
                    $taskids[$x][1]=$result[$x]['name'];
                }
                return Response::json([ 'userid' =>  $userid ,  'email' => $email, 'taskids' => $taskids , 'labname' => $labcontentname]);
            } catch (ModelNotFoundException $e) {
                $response = array(
                    'status' => 'Fail'

                );
                return Response::json($response);
            }
        }
    }

    function getlabcontentbylabid(){
        if (Request::ajax()) {
            $labid =Input::get('labid');
            $email = Input::get('email');
            $userid = Input::get('userid');
            try {

                $submissions = DB::table('lab_task')->where('labid', $labid)->orderBy('id','asc')->get();


                //$sql = "INSERT INTO users_subtasks_submission (lab_id,task_id,subtask_id,user_id,title,`desc`,submission) VALUES(".$labid.",".$taskid.",".$subtaskid.",".$this->user.",'".$title."','".$desc."','".$answer."')";
                //$result = $conn->query($sql);
                //$result=DB::insert($sql);
                $result = json_decode(json_encode($submissions), True);
                $taskids=array();
                for ($x = 0; $x < sizeof($result); $x++) {
                    $taskids[$x][0]=$result[$x]['id'];
                    $taskids[$x][1]=$result[$x]['name'];
                }
                return Response::json([ 'userid' =>  $userid ,  'email' => $email, 'taskids' => $taskids ]);
            } catch (ModelNotFoundException $e) {
                $response = array(
                    'status' => 'Fail'

                );
                return Response::json($response);
            }
        }
    }
}
