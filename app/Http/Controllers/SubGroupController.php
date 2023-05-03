<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/27/17
 * Time: 3:07 PM
 */

namespace App\Http\Controllers;

use App\DeployLab;
use App\UserGroupRole;
use Auth;
use App, App\Group, App\Site, App\User, App\Role, App\Subgroup;
use App\DataTables\SubgroupsDataTable;
use Xavrsl\Cas\Facades\Cas;
use Regulus\ActivityLog\Models\Activity;
use Illuminate\Http\Request;
use Response, Redirect, DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class SubGroupController extends Controller
{
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

    public function index(SubgroupsDataTable $dataTable, $id)
    {
        $user = Auth::user();
        if($id!=0){
            $grp = $this->user->usersGroupsRoles()->whereHas('Role', function($query) {
                $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
            })->where('group_id', '=', $id)->get();
            if (count($grp) == 0){
                alert()->warning('You don\'t have permission to do that!');
                return redirect()->back();
            }
        }

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
            return $dataTable->with('id', $id)->render('admin.subgroups.index', compact('id', 'groups'));
        } else {
            alert()->warning('You have no permission to access this page!', 'Permission Denied')->persistent('OK');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $grp = $this->user->usersGroupsRoles()->whereHas('Role', function($query) {
            $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
        })->where('group_id', '=', $input['groupId'])->get();
        if (count($grp) == 0){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        if (!isset($input['members'])) {
            alert()->warning('Please select members!', 'Create Team')->persistent('OK');
            return redirect()->back()->with('show-create-team-modal', 1);
        }

        $supers = UserGroupRole::where('group_id', '=', $input['groupId'])->whereHas('Role', function($query) {
            $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
        })->groupBy('user_id')->get()->pluck('user_id');

        if ($input['team-type'] == 'group') {
//            if (preg_match('/\s/', $input['name'])) {
//                alert()->warning('Team name cannot have space!', 'Create Team')->persistent('OK');
//                return redirect()->back()->with('show-create-team-modal', 1);
//            }

            $subgroup = new Subgroup();
            try {
                $subgroup->fill(['name' => $input['name'], 'group_id' => $input['groupId'], 'description' => $input['description']])->save();
            } catch (QueryException $e) {
                alert()->error('Team already exist!')->persistent('OK');
                return redirect()->back()->with('show-create-team-modal', 1);
            }
            foreach ($input['members'] as $member) {
                $user = User::find($member);
                $user->subgroups()->attach($subgroup->getAttribute('id'));
            }

            foreach ($supers as $super) {
                if (!in_array($super, $input['members'])) {
                    $user = User::find($super);
                    $user->subgroups()->attach($subgroup->getAttribute('id'));
                }
            }

            alert()->success('Team ' . $subgroup->getAttribute('name') . ' created!')->persistent('OK');
            return redirect()->back();

        } else {
            $success = [];
            $failed = [];
            $message = "";
            foreach ($input['members'] as $member) {
                $subgroup = new Subgroup();
                $user = User::find($member);
                try {
                    $subgroup->fill(['name' => $user->getAttribute('email'), 'group_id' => $input['groupId'], 'description' => 'Individual Team'])->save();
                    $user->subgroups()->attach($subgroup->getAttribute('id'));

                    foreach ($supers as $super) {
                        if ($member != $super) {
                            $user = User::find($super);
                            $user->subgroups()->attach($subgroup->getAttribute('id'));
                        }
                    }
                    array_push($success, $user->getAttribute('email'));
                } catch (QueryException $e) {
                    array_push($failed, $user->getAttribute('email'));
                }
            }

            if (count($success) > 0) {
                $message = 'Individual team for ' . implode(',', $success) . ' created.<br>';
            }
            if (count($failed) > 0) {
                $message .= 'Individual team for ' . implode(',', $failed) . ' already exist!';
            }
//            alert()->warning($message, 'Create Team')->persistent('OK');
            return redirect()->back()->with('');
        }
    }

    public function update(Request $request)
    {
        $input = $request->all();

        $subgroup = Subgroup::find($input['subgroup_id']);
        $groupid=$subgroup->group()->first()->id;
        $grp = $this->user->usersGroupsRoles()->whereHas('Role', function($query) {
            $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
        })->where('group_id', '=', $groupid)->get();
        if (count($grp) == 0){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        $subgroup->fill(['name' => $input['subgroup_name'], 'description' => $input['description']])->save();

        return Response::json(['status' => 'success']);
    }

    public function delete(Request $request)
    {
        $input = $request->all();
        $subgroup = Subgroup::find($input['team']);
        $groupid=$subgroup->group()->first()->id;
        $grp = $this->user->usersGroupsRoles()->whereHas('Role', function($query) {
            $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
        })->where('group_id', '=', $groupid)->get();
        if (count($grp) == 0){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        $subgroup->users()->detach();
        $subgroup->delete();
        return Response::json(['status' => 'Success']);
    }

    public function updateMembers(Request $request)
    {
        $input = $request->all();

        $openstackRes = app()->make('App\Http\Controllers\OpenStackResource');

        $subgroup = Subgroup::find($input['subgroup_id']);
        $groupid=$subgroup->group()->first()->id;
        $grp = $this->user->usersGroupsRoles()->whereHas('Role', function($query) {
            $query->whereIn('name', ['instructor', 'TA', 'group_owner']);
        })->where('group_id', '=', $groupid)->get();
        if (count($grp) == 0){
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        $projects = DeployLab::where('subgroup_id', '=', $input['subgroup_id'])->get();
        $new_members = [];
        foreach ($input['members'] as $member) {
            array_push($new_members, ['email' => $member['email'], 'roleId' => config('openstack.user_role_id')]);
        }

        foreach ($projects as $project) {
            if (($projectId = $project->getAttribute('project_id')) != "") {
                $openstackRes->updateProjectMemberI($projectId, $new_members);
            }
        }

        $subgroup->users()->detach();
        foreach ($input['members'] as $member) {
            $subgroup->users()->attach($member['id']);
        }
    }

    /***************************
     * Old Functions
     *
     ***************************/
    public function getTeamList(Request $request, $group_name)
    {
        if ($request->ajax()) {
            $rows = DB::select('SELECT s.id, s.name, s.description, s.group_id, s.owner_id FROM subgroups AS s'.
                ' JOIN groups ON s.group_id = groups.id WHERE groups.name = ?', array($group_name));
            $teams = array();
            foreach ($rows as $row) {
                $m_rows = DB::select('SELECT users.email FROM users JOIN users_subgroups ON users.id=users_subgroups.user_id ' .
                    'WHERE users_subgroups.subgroup_id = ?', array($row->id));
                $members = array();
                foreach ($m_rows as $m_row) {
                    array_push($members, $m_row->email);
                }
                //$t_rows = DB::select('SELECT t.id, openlab_temp.lab_name FROM temp AS t JOIN subgroup_template_project AS s ON t.id = s.template_id  JOIN openlab_temp ON openlab_temp.id = s.lab_id ' .
                //        'WHERE s.subgroup_id = ?', array($row->id));
                $t_rows = DB::select('SELECT openlab_temp.lab_name FROM subgroup_template_project AS s JOIN openlab_temp ON openlab_temp.id = s.lab_id WHERE s.subgroup_id = ?', array($row->id));
                $templates = array();
                foreach ($t_rows as $t_row) {
                    //$temp = array('temp_id' => $t_row->id, 'temp_name' => $t_row->lab_name);
                    $temp = array('temp_name' => $t_row->lab_name);
                    array_push($templates, $temp);
                }
                $team = array('id' => $row->id, 'name' => $row->name, 'desc' => $row->description,
                    'group_id' => $row->group_id, 'owner_id' => $row->owner_id, 'members' => $members,
                    'templates' => $templates);
                array_push($teams, $team);
            }

            return Response::json($teams);
        }
    }

    public function create_subgroup(Request $request)
    {
        if ($request->ajax()) {
            $group_id = $request->get('group_id');
            $group_name = $request->get('group_name');
            $name = $request->get('subgroup_name');
            $description = $request->get('subgroup_desc');
            $members = $request->get('members');

            $subgroup = new Subgroup();
            try {
                $subgroup->fill(['name' => $name, 'group_id' => $group_id, 'description' => $description])->save();
            } catch (QueryException $e) {
                Activity::log(['contentType' => 'Subgroup',
                    'action' => 'Failed',
                    'description' => 'Create a team failed',
                    'details' => $this->user . 'creates team ' . $name . ' in group ' . $group_name . ' failed.'
                ]);
                return Response::JSON(['status' => 'Failed', 'message' => 'Team ' . $name . ' already exist!']);
            }

            foreach ($members as $member) {
                $user = User::where('email', '=', $member)->get()->first();
                $user->subgroups()->attach($subgroup->id);
            }
            Activity::log(['contentType' => 'Subgroup',
                'action' => 'Create Subgroup',
                'description' => 'Create a team',
                'details' => $this->user . ' creates team ' . $name . ' in group ' . $group_name
            ]);
            return Response::json(['status' => 'Success', 'message' => 'Team ' . $name . ' created!', 'subgroup' => $subgroup]);
        }
    }

    public function updateSubGroup(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();

            $isExist = DB::select("select subgroups.id from subgroups join groups on subgroups.group_id = groups.id ".
                                  "where subgroups.name = ? and groups.name = ?", array($input["subgroup_name"], $input["group_name"]));

            if ($isExist) {
                $response = array('status' => 'Failed', 'message' => 'The team name is already existed in this group!');
                return Response::json($response);
            }

            $result = DB::update("UPDATE subgroups JOIN groups ON subgroups.group_id = groups.id SET " .
                "subgroups.name = ?, subgroups.description = ? WHERE subgroups.id = ? and groups.name = ?",
                array($input["subgroup_name"], $input["subgroup_desc"], $input["subgroup_id"], $input["group_name"]));

            if ($result) {
                $response = array('status' => 'Success', 'message' => '');
                Activity::log(['contentType' => 'Subgroup',
                    'contentId' => \Sentry::getUser()->getId(),
                    'action' => 'Update Subgroup',
                    'description' => 'User updates a team',
                    'details' => $this->user . ' updates the team ' . $input["subgroup_name"] . ' (' .
                        $input["subgroup_id"] . ') in the group ' . $input["group_name"]
                ]);
            } else {
                $response = array('status' => 'Failed', 'message' => 'Update team information failed.');
                Activity::log(['contentType' => 'subgroup',
                    'contentId' => \Sentry::getUser()->getId(),
                    'action' => 'Failed',
                    'description' => 'The subgroup info update failed',
                    'details' => $this->user . ' updates the subgroup ' . $input["subgroup_name"] . ' (' .
                        $input["subgroup_id"] . ') in the group ' . $input["group_name"] . ' failed.'
                ]);
            }

            return Response::json($response);
        }
    }

    public function create_individual_team(Request $request)
    {
        if ($request->ajax()) {
            $group_id = $request->get('group_id');
            $group_name = $request->get('group_name');
            $members = $request->get('members');
            $description = $request->get('description');

            $subgroups = array();
            $failed = [];
            $message = '';

            foreach ($members as $member) {
                $subgroup = new Subgroup();
                $user = User::where('email', '=', $member)->get()->first();
                try {
                    $subgroup->fill(['name' => $user->email, 'group_id' => $group_id, 'description' => $description])->save();
                    $user->subgroups()->attach($subgroup->id);
                    array_push($subgroups, $subgroup);
                } catch (QueryException $e) {
                    array_push($failed, $user->email);
                }
            }

            if (count($failed) > 0) {
                Activity::log(['contentType' => 'Subgroup',
                    'action' => 'Failed',
                    'description' => 'Create individual team for user failed',
                    'details' => 'Create individual subgroups ' . implode(',', $failed) . ' in group ' . $group_name . ' failed.'
                ]);
            }
            $success = array_diff($members, $failed);
            if (count($success) > 0) {
                Activity::log(['contentType' => 'Subgroup',
                    'action' => 'Create Subgroup',
                    'description' => 'Create individual team for a user',
                    'details' => 'Create individual subgroups ' . implode(',',$success) . ' in group ' . $group_name
                ]);
                $message = 'Individual team for ' . implode(',',$success) . ' created. ';
                if (count($failed) > 0) {
                    $message .= '<br>' . 'Team for ' . implode(',', $failed) . ' already exist!';
                }
                $result = ['status' => 'Success', 'message' => $message, 'subgroups' => $subgroups];
            } else {
                $result = ['status' => 'Failed', 'message' =>'Team for ' . implode(',', $failed) . ' already exist!'];
            }
            return Response::json($result);
        }
    }

    public function update_subgroup_member(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();

            $cloudRes = App::make('App\Http\Controllers\CloudResource');

            $fail_users = array();

            // delete subgroup from users_subgroups
            $cur_members = DB::select('select users.email FROM users JOIN users_subgroups ON users.id = users_subgroups.user_id ' .
                'where subgroup_id = ? ', array($input['team_id']));

            $projects = DB::select('select s.project_name from subgroup_template_project as s WHERE s.subgroup_id=?', array($input['team_id']));

            foreach ($projects as $project) {
                foreach ($cur_members as $member) {
                    $projectId=$cloudRes->removeProjectMember($project->project_name, $member->email);
                }
            }
            $sql_del = DB::delete('DELETE FROM users_subgroups WHERE subgroup_id=?', array($input['team_id']));
            foreach ($input['members'] as $user) {
                $role = DB::select('select permission.id from permission where permission.description=?', array($user['role']));
                $result = DB::insert('INSERT INTO users_subgroups (user_id, subgroup_id, role_id) ' .
                    'VALUES (?, ?, ?)', array($user['user_id'], $input['team_id'], $role[0]->id));
                foreach ($projects as $project) {
                    $cloudRes->setProjectMember($projectId, $user['user_name']);
                }
                if (!$result) {
                    array_push($fail_users, $user['user_id']);
                }
            }

            if (count($fail_users) > 0) {
                $response = array('status' => 'Failed', 'message' => 'Part of user cannot be added.', 'fail_users' => $fail_users);
                Activity::log(['contentType' => 'Subgroup',
                    'action' => 'Failed',
                    'description' => 'Update Members for a Team Failed',
                    'details' => 'Update team member: ' . $fail_users . ' for team ' . $input['team_name'] . ' failed.'
                ]);
            } else {
                $response = array('status' => 'Success', 'message' => '', 'fail_users' => null);
                Activity::log(['contentType' => 'subgroup',
                    'action' => 'Update Members',
                    'description' => 'Update Members for a Team',
                    'details' => 'Update team member for team ' . $input['team_name']
                ]);
            }
            return Response::json($response);
        }
    }

    public function getSubgroupTemplateProject(Request $request, $group_name)
    {
        if ($request->ajax()) {
            $labs = array();
            $row_subs = DB::select('SELECT subgroups.id, subgroups.name FROM subgroups JOIN groups ON subgroups.group_id = groups.id ' .
                'WHERE groups.name = ?', array($group_name));
            foreach ($row_subs as $row_sub) {
                $row_labs = DB::select('SELECT s.template_id, openlab_temp.lab_name AS template_name, s.project_name, s.lab_name, s.description, s.assign_at, s.deploy_at, s.status, s.due_at, s.lab_id ' .
                    'FROM subgroup_template_project AS s ' .
                    'JOIN openlab_temp ON s.lab_id = openlab_temp.id ' .
                    'WHERE s.subgroup_id = ?', array($row_sub->id));
                foreach ($row_labs as $row_lab) {
                    $status = $row_lab->status;
//                    if ($status == 'CREATE_IN_PROGRESS') {
//                        $newstatus = $this->cloudRes->getStackStatus('s-'.$row_lab->project_name, $row_lab->project_name);
//                        if ($newstatus != $status) {
//                            $row_update = DB::update('UPDATE subgroup_template_project SET status = ? ' .
//                                'WHERE subgroup_id=? AND template_id=? ', array($newstatus, $row_sub->id, $row_lab->template_id));
//                        }
//                    }
                    $lab = array('subgroup_id' => $row_sub->id, 'subgroup_name' => $row_sub->name,
                        'template_id' => $row_lab->template_id, 'template_name' => $row_lab->template_name,
                        'project_name' => $row_lab->project_name, 'lab_name' => $row_lab->lab_name,
                        'assign_at' => $row_lab->assign_at, 'deploy_at' => $row_lab->deploy_at,
                        'status' => $status, 'desc' => $row_lab->description, 'due_at' => $row_lab->due_at);
                    array_push($labs, $lab);
                }
            }
            return Response::json($labs);
        }
    }

    function  delete_subgroup(Request $request)
    {
        if ($request->ajax()) {
            $subgroup_id = $request->get('subgroup_id');
            $groupname = $request->get('group_name');
            $hasProjects = DB::select("SELECT subgroup_id FROM subgroup_template_project WHERE subgroup_id = ?", array($subgroup_id));
            if ($hasProjects) {
                $response = array('status' => 'Failed', 'message' => 'The subgroup contains assigned labs, please delete the labs first.');
            } else {
                $team = DB::select("SELECT name FROM subgroups WHERE id = ?", array($subgroup_id));
                if ($team) {
                    $subgroup_name = $team[0]->name;
                    $sql1 = DB::delete("DELETE FROM users_subgroups WHERE subgroup_id = ?", array($subgroup_id));
                    $sql2 = DB::delete("DELETE FROM subgroups WHERE id = ?", array($subgroup_id));
                    if ($sql1 and $sql2) {
                        $response = array('status' => 'Success', 'message' => '');
                        Activity::log(['contentType' => 'Subgroup',
                            'action' => 'Delete Subgroup',
                            'description' => 'User delete a subgroup',
                            'details' => 'User ' . $this->user . ' deletes the team ' . $subgroup_name . 'from the group ' . $groupname
                        ]);
                    } else {
                        $response = array('status' => 'Failed', 'message' => 'Delete subgroup failed.');
                        Activity::log(['contentType' => 'Subgroup',
                            'action' => 'Failed',
                            'description' => 'User delete a subgroup failed',
                            'details' => 'User ' . $this->user . ' deletes the team ' . $subgroup_name . ' from the group ' . $groupname . ' failed.'
                        ]);
                    }
                } else {
                    $response = array('status' => 'Failed', 'message' => 'Cannot find the team');
                }
            }
            return Response::json($response);
        }
    }

    function rename_subgroup() {
        if (Request::ajax()) {
            $input = Input::all();
            // profile database
            $servername = "10.2.255.50";
            $username = "root";
            $password = "Cloud\$erver";
            $database = "mobicloud";
            $port = 3307;
            // return json
            $arr = array();
            $arr["members"] = array();
            // connection
            //$conn = new mysqli($servername, $username, $password, $database, $port);
            //if ($conn->connect_error) {
            //    die("Connection failed: " . $conn->connect_error);
            //} else {
            $sql = "UPDATE subgroups SET name='" . $input["subgroup_name"] .
                "' WHERE id=" . $input["subgroup_id"];
            //$result = $conn->query($sql);
            $result = DB::update($sql);
            if ($result) {
                Activity::log(['contentType' => 'Subgroup',
                    'action' => 'Rename Subgroup',
                    'description' => 'User rename a subgroup',
                    'details' => 'User ' . $this->user . ' rename the subgroup ' . $input["subgroup_name"]
                ]);
            } else {
                Activity::log(['contentType' => 'Subgroup',
                    'action' => 'Failed',
                    'description' => 'User rename a subgroup failed',
                    'details' => 'User ' . $this->user . ' rename the subgroup ' . $input["subgroup_name"] . ' failed.'
                ]);
            }
            // close connection
//                mysqli_close($conn);
            //}

            return Response::json($arr);
        }
    }

}