<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 9/21/17
 * Time: 10:41 AM
 */

namespace App\Http\Controllers;

use App, App\LabTask,App\LabContent,App\Labs, App\DeployLab;
use App\DataTables\LabContentDataTable;
use App\DataTables\LabRepoDataTable;
use Auth, Sentry;
use App\UserGroupRole;
use Input;
use Xavrsl\Cas\Facades\Cas;
use Regulus\ActivityLog\Models\Activity;
use Illuminate\Http\Request;
use Response, Redirect, DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Yajra\DataTables\DataTables;
use App\Traits\CheckUserStatusTrait;

class LabContentController extends Controller
{
    use CheckUserStatusTrait;

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
     * @param \App\DataTables\GroupsDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function index(LabContentDataTable $dataTable)
    {
        return $dataTable->render('admin.labs.labcontentindex');
        //        $users = User::all();
//        return view('users.index')->with('users', $users);
    }

    public function repoindex(LabRepoDataTable $dataTable,$flag)
    {

            return $dataTable->with('flag', $flag)->render('admin.labs.labrepoindex', compact('id', 'flag'));

    }

    public function show($id)
    {
        return Group::find($id);
    }

    function getTasksTable($id) {
        $labcontent = LabContent::findOrFail($id);
        $ownerid=$labcontent->owner->id;
        $userid=Auth::user()->id;
        if($ownerid!=$userid){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        $tasks = LabTask::where('labid','=',$id)->select('id', 'name','submission');
        return DataTables::of($tasks)//->addColumn('checkbox', function ($row) { return $row->id; });
//            ->editColumn('submission_details_url', function ($row) {
//                return url('labcontents/submissions-table/' . $row->id);
//                //                return '<a href="#" onclick="group_details_table($(this))" data-id="'. $row->id . '"><i class="fa fa-toggle-right "></i></a>';
//            })
        ->addColumn('state', function($row) {
            $html_str = "";
            if (in_array($row->submission, ['["true","true","true"]','["true","false","false"]','["true","true","false"]','["true","false","true"]'])) {
                $html_str = $html_str.'Screenshots';
            }
            if (in_array($row->submission, ['["true","true","true"]','["true","true","false"]'])) {
                $html_str = $html_str.',Files';
            }
            if (in_array($row->submission, ['["false","true","false"]','["false","true","true"]'])) {
                $html_str = $html_str.'Files';
            }
            if (in_array($row->submission, ['["true","true","true"]','["false","true","true"]','["true","false","true"]'])) {
                $html_str = $html_str.',Texts';
            }
            if (in_array($row->submission, ['["false","false","true"]'])) {
                $html_str = $html_str.'Texts';
            }
//                else if (in_array($row->status, ['PROJECT_COMPLETE', 'NONE'])) {
//                    $html_str .= '<div><i class="fa fa-spinner fa-spin" rel="tooltip-right" title="' . $row->status . '" style="font-size:24px;"></i></div>';
//                }
            return $html_str;
        })->make(true);
    }

//    function getSubmissionsTable($id) {
//
//        $subtasks = LabSubtask::where('taskid','=',$id)->select('id', 'name', 'description','type');
//        return DataTables::of($subtasks)->make(true);//->addColumn('checkbox', function ($row) { return $row->id; });
////        ->editColumn('submission_details_url', function ($row) {
////            return url('groups/members-table/' . $row->id);
////            //                return '<a href="#" onclick="group_details_table($(this))" data-id="'. $row->id . '"><i class="fa fa-toggle-right "></i></a>';
////        })
//
//    }

    function getMembersJson($id) {
        $ugrs = UserGroupRole::where('group_id', '=', $id);
        $gusers = $ugrs->select('users.id as id', 'users.name as name', 'email', 'institute', 'org_id',
            DB::raw("group_concat(roles.name) as role"))
            ->join('users', 'users.id', '=', 'users_groups_roles.user_id')
            ->join('roles', 'roles.id', 'users_groups_roles.role_id')
            ->where('group_id', '=', $id)
            ->groupBy('users.email')->get();
        return Response::json($gusers->toArray());
    }

    public function getlabcontentbyid(Request $request)
    {
        if ($request->ajax()) {
            $labid = Input::get('labid');
            $user = Auth::user();
            $accessflag=0;
            try {
                $labcontent = LabContent::findOrFail($labid);
            } catch (ModelNotFoundException $e) {
                return redirect()->route('404');
            }
            $deploylabs=$labcontent->deploylabs()->get();
            foreach ($deploylabs as $deploylab){
                $subgroupid=DeployLab::find($deploylab->id)->subgroup_id;
                $subgrouplist=$user->subgroups()->where("subgroup_id", '=',$subgroupid)->get();
                if(count($subgrouplist)!= 0){
                    $accessflag=1;
                }
            }
//            if($accessflag==0){
//                alert()->warning('You don\'t have permission to do that!');
//                return redirect()->back();
//            }

            try {
                $submissions = DB::table('labcontent')->where('id', $labid)->get();


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

    public function getlabtaskbyid(Request $request)
    {
        if ($request->ajax()) {
            $taskid = Input::get('taskid');
            $user = Auth::user();
            $accessflag=0;
//            try {
//                $labtask = LabTask::findOrFail($taskid);
//            } catch (ModelNotFoundException $e) {
//                return redirect()->route('404');
//            }
//            $labid=$labtask->lab;
//            $deploylabs=LabContent::find($labid)->deploylabs()->get();
//            foreach ($deploylabs as $deploylab){
//                $subgroupid=DeployLab::find($deploylab->id)->subgroup_id;
//                $subgrouplist=$user->subgroups()->where("subgroup_id", '=',$subgroupid)->get();
//                if(count($subgrouplist)!= 0){
//                    $accessflag=1;
//                }
//            }
//            if($accessflag==0){
//                alert()->warning('You don\'t have permission to do that!');
//                return redirect()->back();
//            }
            try {
                $submissions = DB::table('lab_task')->where('id', $taskid)->get();


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

    public function getlabtaskbylab(Request $request)
    {
        if ($request->ajax()) {
            $labid = Input::get('labid');
            $user = Auth::user();
            $accessflag=0;
            try {
                $labcontent = LabContent::findOrFail($labid);
            } catch (ModelNotFoundException $e) {
                return redirect()->route('404');
            }
            $deploylabs=$labcontent->deploylabs()->get();
            foreach ($deploylabs as $deploylab){
                $subgroupid=DeployLab::find($deploylab->id)->subgroup_id;
                $subgrouplist=$user->subgroups()->where("subgroup_id", '=',$subgroupid)->get();
                if(count($subgrouplist)!= 0){
                    $accessflag=1;
                }
            }
            if($accessflag==0){
                alert()->warning('You don\'t have permission to do that!');
                return redirect()->back();
            }

            try {
                $submissions = DB::table('lab_task')->where('labid', $labid)->get();


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

    public function getlabinfobyid(Request $request)
    {
        if ($request->ajax()) {
            $labid = Input::get('labid');
            $labcontentid = Input::get('labcontentid');
            $user = Auth::user();
            try {
                $projectid = DeployLab::findOrFail($labid);
            } catch (ModelNotFoundException $e) {
                return redirect()->route('404');
            }
            $subgroupid=DeployLab::find($labid)->subgroup_id;
            $subgrouplist=$user->subgroups()->where("subgroup_id", '=',$subgroupid)->get();
            if(count($subgrouplist)== 0){
                alert()->warning('You don\'t have permission to do that!');
                return redirect()->back();
            }

            try {
                $submissions = DB::table('deploylab_labcontent')->where('deploylab_id','=', $labid)->where('labcontent_id','=',$labcontentid)->get();


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

    public function saveContent(Request $request)
    {
        $input = $request->all();
        $labcontent = new LabContent();
        $difficulty=json_encode($input['difficulty']);
        try {
            $labcontent->fill(['name' => $input['labname'], 'description' => $input['labdesc'],'lab_cat_id'=>$input['labcatid'], 'tags' => $input['tags'], 'objects' => $input['objects'], 'experttime' => $input['experttime'],'time' => $input['time'], 'taskcount' => $input['taskcount'], 'owner_id' => $this->userid, 'difficulty' => $difficulty, 'os'=>$input['os'],'preparations'=>$input['preparations']])->save();

        } catch (QueryException $e) {
            //alert()->error('Temp already exist!')->persistent('OK');
            return Response::JSON(['status'=>'Failed']);
        }
        //alert()->success('Lab Environment ' . $labcontent->getAttribute('id') . ' created!')->persistent('OK');
        return Response::JSON(['status'=>'Success','id'=>$labcontent->id]);
    }

    public function updateContent(Request $request)
    {
        $input = $request->all();
        $labcontent = LabContent::findOrFail($input['id']);
        $ownerid=$labcontent->owner->id;
        $userid=Auth::user()->id;
        if($ownerid!=$userid){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        $difficulty=json_encode($input['difficulty']);
        if($input['pdfflag']=='false'){
            DB::table('labcontent')->where('id',$input['id'])->
            update(
                array(


                    'pdfflag'=>0

                )
            );
        }else{
            DB::table('labcontent')->where('id',$input['id'])->
            update(
                array(


                    'pdfflag'=>1

                )
            );
        }
        try {
            $labcontent->fill(['name' => $input['labname'], 'description' => $input['labdesc'], 'lab_cat_id'=>$input['labcatid'], 'tags' => $input['tags'], 'objects' => $input['objects'], 'experttime' => $input['experttime'],'time' => $input['time'], 'taskcount' => $input['taskcount'], 'owner_id' => $this->userid, 'difficulty' => $difficulty, 'os'=>$input['os'],'preparations'=>$input['preparations']])->save();
        } catch (QueryException $e) {
            //alert()->error('Temp already exist!')->persistent('OK');
            return Response::JSON(['status'=>'Failed']);
        }
        //alert()->success('Lab Environment ' . $labcontent->getAttribute('id') . ' created!')->persistent('OK');
        return Response::JSON(['status'=>'Success']);
    }

    public function deleteContent(Request $request)
    {
        $input = $request->all();
        $labcontentused=LabContent::findOrFail($input['id'])->deploylabs()->first();
        $labcontent = LabContent::findOrFail($input['id']);
        $ownerid=$labcontent->owner->id;
        $userid=Auth::user()->id;
        if($ownerid!=$userid){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        if($labcontentused){
            return Response::JSON(['status'=>'eFailed']);
        }else{
            try {
                $labcontent->labTask()->delete();
                $labcontent->delete();

            } catch (QueryException $e) {
                //alert()->error('Temp already exist!')->persistent('OK');
                return Response::JSON(['status'=>'Failed']);
            }
            //alert()->success('Lab Environment ' . $labcontent->getAttribute('id') . ' created!')->persistent('OK');
            return Response::JSON(['status'=>'Success']);
        }
    }

    public function saveTask(Request $request)
    {
        $input = $request->all();
        $labtask = new LabTask();
        $submission=json_encode($input['submission']);
        $labcontent = LabContent::findOrFail($input['labid']);
        $ownerid=$labcontent->owner->id;
        $userid=Auth::user()->id;
        if($ownerid!=$userid){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        try {
            $labtask->fill(['name' => $input['taskname'], 'content'=>$input['description'],'owner_id' => $this->userid, 'submission' => $submission, 'labid'=>$input['labid']])->save();
        } catch (QueryException $e) {
            //alert()->error('Temp already exist!')->persistent('OK');
            return Response::JSON(['status'=>'Failed']);
        }
        //alert()->success('Lab Environment ' . $labcontent->getAttribute('id') . ' created!')->persistent('OK');
        return Response::JSON(['status'=>'Success']);
    }

    public function updateTask(Request $request)
    {
        $input = $request->all();
        $labcontent = LabContent::findOrFail($input['labid']);
        $ownerid=$labcontent->owner->id;
        $userid=Auth::user()->id;
        if($ownerid!=$userid){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        $labtask = LabTask::findOrFail($input['id']);
        $submission=json_encode($input['submission']);
        try {
            $labtask->fill(['name' => $input['taskname'], 'content'=>$input['description'],'owner_id' => $this->userid, 'submission' => $submission, 'labid'=>$input['labid']])->save();
        } catch (QueryException $e) {
            //alert()->error('Temp already exist!')->persistent('OK');
            return Response::JSON(['status'=>'Failed']);
        }
        //alert()->success('Lab Environment ' . $labcontent->getAttribute('id') . ' created!')->persistent('OK');
        return Response::JSON(['status'=>'Success']);
    }

    public function deleteTask(Request $request)
    {
        $input = $request->all();
        $labtask = LabTask::findOrFail($input['id']);
        $labid= $labtask->labid;
        $labcontent = LabContent::findOrFail($labid);
        $ownerid=$labcontent->owner->id;
        $userid=Auth::user()->id;
        if($ownerid!=$userid){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }


        try {
            $labtask->delete();
        } catch (QueryException $e) {
            //alert()->error('Temp already exist!')->persistent('OK');
            return Response::JSON(['status'=>'Failed']);
        }
        //alert()->success('Lab Environment ' . $labcontent->getAttribute('id') . ' created!')->persistent('OK');
        return Response::JSON(['status'=>'Success']);
    }

    public function status($group)
    {
        if ($group->enabled and $group->approved) {
            return 'Active';
        } elseif ($group->enabled and !$group->approved) {
            return 'Pending';
        } elseif (!$group->enabled and $group->approved) {
            return 'Disabled';
        } else {
            return 'Denied';
        }
    }

    public function getGroupAvailableRoles(Request $request)
    {
        $roles = Role::where('type', '=', 'group')->get();
        return Response::JSON($roles);
    }

    public function getOwners($group_id)
    {
        $role_id = Role::findByName('group_owner')->id;
        $owners = UserGroupRole::where('group_id', '=', $group_id)->where('role_id', '=', $role_id)->get();
        return $owners;
    }

    public function create()
    {
        $sites = Site::all();
        return view('admin.groups.create', ['sites' => $sites]);
    }

    public function store(Request $request)
    {
        $rules = [
            'name'            =>  'required|min:5|max:35|alpha_dash|unique:groups',
        ];

        if ($request->ajax()) {
            $input = $request->get('group');
            (empty(json_decode($input['resource_requested'], true))) ? $input['approved'] = '1' : $input['approved'] = '0';
        } else {
            $rules = array_merge($rules, array('lab' => 'numeric', 'vms' => 'numeric', 'vcpu' => 'numeric',
                'ram' => 'numeric', 'storage' => 'numeric', 'expiration' => 'date'));

            $input = $request->all();
            if (isset($input['resource_requested'])) {

                $input['approved'] = '1';
                $input['resource_requested'] = Response::JSON(
                    array('labs' => $input['lab'], 'vms' => $input['vms'], 'vcpus' => $input['vcpus'],
                          'ram' => $input['ram'], 'storage' => $input['storage'], 'expiration' => $input['expiration'])
                );
            }
        }
        //$input['expiration'] = '0000-00-00 00:00:00';
        $input['site_id'] = '1';

        $this->validate($request,$rules);

        $group = new Group;
        try {
            $group->fill($input)->save();
            $ug_role = new UserGroupRole();
            $ug_role->user_id = Auth::user()->id; //Sentry::getUser()->getId();
            $ug_role->group_id = $group->id;
            $ug_role->role_id = Role::findByName('group_owner')->id;
            $ug_role->save();

            if ($request->ajax()) {
                return Response::JSON(array('status' => 'Success',
                    'message' => 'New group application is submitted.<br>Please check status in My Groups.',
                    'id' => $group->id, 'name' => $group->name, 'description' => $group->description,
                    'private' => $group->private, 'resource_requested' => $group->resource_requested,
                    'created' => date($group->created_at), 'updated' => date($group->updated_at)
                ));
            } else {
                alert()->success('Group ' . $group->name . ' created.', 'Create New Group')->persistent('OK');
                return redirect()->route('groups.index');
                    //->with('flash_message', 'Site' . $group->name . ' added!');
            }
        } catch (QueryException $e) {
            $sites = Site::all();
            if ($request->ajax()) {
                return Response::JSON(array('status' => 'Failed', 'message' => $e->errorInfo[2]));
            } else {
                alert()->error('Group ' . $group->name . ' creation error! ' . $e->errorInfo[2], 'Create New Group')->persistent('OK');
                return view('admin.groups.create', ['sites' => $sites])->withErrors([$e->errorInfo[2]]);
            }
        }
    }

    public function update(Request $request)
    {
        $input = $request->get('group');
        $message = '';

        try {
            $group = Group::findOrFail($input['id']);
        } catch (ModelNotFoundException $e) {
            return Response::JSON(['status' => 'Failed', 'message' => 'Group ' . $input['name'] . ' cannot be found!']);
        }
        $user = User::find(Sentry::getUser()->getId());
        $isSiteAdmin = $user->sites()->where('site_id', '=', $group->site->id)->get();
        $isOwner = $user->usersGroupsRoles()->where('group_id', '=', $group->getAttribute('id'))->get();
        $allowUpdate = (!empty($isOwner) and (in_array($isOwner->first()->role_id, [5,6,9]))) ? true : false;

        if (empty($isSiteAdmin) and $allowUpdate) {
            return Response::JSON(['status' => 'Failed', 'message' => 'You have no permission to update group ' . $input['name'] . '!']);
        }

        if (isset($input['private'])) $group->private = intval($input['private']);
        if (isset($input['description'])) $group->description = $input['description'];

        if (isset($input['resource_requested']) and !empty(json_decode($input['resource_requested'], true))) {
            $group->approved = '0';
            $group->resource_requested = $input['resource_requested'];
            $group->expiration = $input['expiration'];
            $group->site_id = $input['site_id'];
            $message .= '<br>Resource Request Submitted.';
        }

        try {
            $group->save();
        } catch (QueryException $e) {
            return Response::JSON(['status' => 'Failed', 'message' => $e->errorInfo[2]]);
        }

        /**
         *  The member update function need to be modified.
         *  Currently, I just delete the current members and insert members in the input list to UserGroupRole table.
         *  We need a smart way to compare the current members and their roles to the member data in the input list,
         *  and only insert or delete the updated members.
         *
         */

        if (isset($input['members'])) {
            $curr_members = $group->usersGroupsRoles()->where('group_id', '=', $group->getAttribute('id'))->get();
            foreach ($curr_members as $curr_member) {
                if ($curr_member->role->name != 'group_owner') {
                    $group->usersGroupsRoles()->where('user_id', '=', $curr_member->user_id)->delete();
                }
            }
            $members = $input['members'];
            foreach ($members as $member) {
                foreach ($member['roles'] as $role) {
                    $ugr = new UserGroupRole();
                    $ugr->fill(['group_id' => $group->getAttribute('id'), 'user_id' => $member['id'], 'role_id' => $role])->save();
                }
            }
            $message .= '<br>Member updated.';
        }

        if (isset($input['batch_emails'])) {
            $c = $this->batchEnroll($input['batch_emails'], $group->id);
            if ($c > 0 ) {
                $message .= '<br>Batch enroll ' . $c . ' members.';
            }
        }

        return Response::JSON(['status' => 'Success', 'message' => 'Group ' . $input['name'] . ' updated. ' . $message,
            'group' => $group, 'group_status' => $this->status($group)]);
    }

    public function delete(Request $request)
    {
        $gId = $request->get('group_id');
        $gName = $request->get('group_name');

        try {
            $group = Group::findOrFail($gId);
        } catch (ModelNotFoundException $e) {
            return Response::JSON(['status' => 'Failed', 'message' => 'Group ' . $gName . ' cannot be found!']);
        }

        $user = User::find(Sentry::getUser()->getId());
        $isSiteAdmin = $user->sites()->where('site_id', '=', $group->site->id)->get();
        $isOwner = $user->usersGroupsRoles()->where('group_id', '=', $group->id)->get();
        if (empty($isSiteAdmin) and empty($isOwner)) {
            return Response::JSON(['status' => 'Failed', 'message' => 'You have no permission to delete group ' . $gName . '!']);
        }

        if ($this->status($group) === 'Active' or $this->status($group) === 'Disabled') {
            // need to check associated resources
        }

        try {
            $group->usersGroupsRoles()->delete();
            $group->delete();
        } catch (QueryException $e) {
            return Response::JSON(['status' => 'Failed', 'message' => 'Group ' . $gName . ' is currently locked!']);
        }
        return Response::JSON(['status' => 'Success', 'message' => 'Group ' . $gName . ' deleted!']);
    }

    public function batchEnroll($emailstr, $group_id)
    {
       // $role = Role::findByName('student')->get()->first();
        $role = Role::where('name', '=', 'student')->get()->first();
        $emailstring = str_replace('\n', '', $emailstr);
        $emails = explode(";", $emailstring);
        $new_users = 0;
        foreach ($emails as $email) {
            $email = trim($email);
            if ($email != "") {
                try {
                    $user = User::where('email', '=', $email)->firstOrFail();
                    try {
                        $is_existed = $user->usersGroupsRoles()->where('group_id', '=', $group_id)->firstOrFail();
                    } catch (ModelNotFoundException $e) {
                        $ugr = new UserGroupRole();
                        $ugr->fill(['user_id' => $user->id, 'group_id' => $group_id, 'role_id' => $role->getAttribute('id')])->save();
                        $new_users++;
                    }
                } catch (ModelNotFoundException $e){
                    // send registration invitation email
                    $randompass = substr(str_shuffle(MD5(microtime())), 0, 10);
                    $fakeuser = array('first_name'=>'first_name', 'last_name'=>'last_name', 'email'=>$email, 'institute'=>'ASU',
                        'password'=>$randompass, 'password_confirmation'=>$randompass, 'country'=>'USA');
                    $newuser = App::make('register_service')->register2($fakeuser);

                    // mark new user
                    $n_user = User::where('email', '=', $email)->get()->first();
                    $n_user->fill(['group_based_registration_invitation' => '1'])->save();

                    $ugr = new UserGroupRole();
                    $ugr->fill(['user_id' => $n_user->id, 'group_id' => $group_id, 'role_id' => $role->getAttribute('id')])->save();
                    $new_users++;
                }
            }
        }
        return $new_users;
    }

    public function getOwnGroupList_byRole(Request $request)
    {
        if ($request->ajax()) {
            $arr = array();
            $arr["groups"] = array();
            $sql = "SELECT groups.name, groups.id, groups.description, groups.private, groups.active_duration_tiem ".
                "FROM users_groups ".
                "JOIN groups ON users_groups.group_id=groups.id ".
                "JOIN users ON users_groups.user_id=users.id ".
                "WHERE groups.class is NULL AND users_groups.role_id=15 AND users.email='" . $this->user . "'";
            $result = DB::select($sql);
            foreach($result as $row) {
                $applicant = array("group_name" => $row->name, "group_id" => $row->id, "group_desc" => $row->description, "group_private" => $row->private, "group_expire" => $row->active_duration_tiem);
                array_push($arr["groups"], $applicant);
            }
            return Response::json($arr);
        }
    }

    public function getOwnGroupList()
    {
        if (Request()->ajax()) {
            // return json
            $arr = array();
            $arr["groups"] = array();
            $sql = "SELECT groups.name, groups.id, groups.description, groups.private FROM groups JOIN users ON groups.owner_id=users.id WHERE groups.class is NULL AND users.email='" . $this->user . "'";
            //$result = $conn->query($sql);
            $result = DB::select($sql);
            //while ($row = $result->fetch_assoc()) {
            foreach($result as $row) {
                $applicant = array("group_name" => $row->name, "group_id" => $row->id, "group_desc" => $row->description, "group_private" => $row->private);
                array_push($arr["groups"], $applicant);
            }
            return Response::json($arr);
        }
    }

    function getGroupMembers(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $arr = array();
            $arr["members"] = array();
            $rows = DB::select('SELECT users.id, users.email, users.institute, users.org_id, permission.description ' .
                'FROM users_groups '.
                'JOIN users ON users_groups.user_id=users.id ' .
                'JOIN groups ON users_groups.group_id=groups.id ' .
                'JOIN permission ON users_groups.role_id=permission.id ' .
                'WHERE groups.name=?' , array($input["group_name"]));
            foreach ($rows as $row) {
                $member = array("id" => $row->id, "email" => $row->email, "institute" => $row->institute, "org_id" => $row->org_id, "role" => $row->description);
                array_push($arr["members"], $member);
            }
            return Response::json($arr);
        }
    }

}