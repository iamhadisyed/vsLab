<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 9/18/15
 * Time: 4:01 PM
 */
namespace App\Http\Controllers;

use Redirect, Response, DB, App, mysqli;
use Xavrsl\Cas\Facades\Cas;
use Illuminate\Http\Request;
use Regulus\ActivityLog\Models\Activity;

class LabsController extends Controller{

    protected $user;
    protected $password;
    protected $cloudRes;
    protected $role;

    public function __construct()
    {
        if (Cas::isAuthenticated()) {
            $this->user = Cas::getCurrentUser();
        } else {
            return Redirect::to('/');
        }
    }

    function assignTemplateToTeams(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $success = array();
            $failed = array();
            $lab = DB::select('select name from lab_temp where id = ?', array($input['temp_id']));
            foreach ($input['subgroups'] as $team ) {
                $result = DB::Insert('INSERT INTO subgroup_template_project (subgroup_id, template_id, project_name, assign_at, lab_id) ' .
                    'VALUES (?, ?, ?, ?, ?)', array($team['team_id'], $input['temp_id'], $input['groupname'] . "-" . uniqid(), $input['assign_at'], $input['lab_id']));
                if ($result) {
                    array_push($success, $team['team_name']);
                } else {
                    array_push($failed, $team['team_name']);
                }
            }
            if (count($success) > 0) {
                Activity::log(['contentType' => 'Labs',
                    'action' => 'Assign Lab Template',
                    'description' => 'Assign a lab template',
                    'details' => $this->user . ' assign lab template ' . $lab[0]->name . ' to team ' . implode(",", $success) .
                        ' in the group ' . $input['group_name']
                ]);
            }
            if (count($failed) > 0) {
                Activity::log(['contentType' => 'Labs',
                    'action' => 'Assign Lab Template',
                    'description' => 'Assign a lab template failed',
                    'details' => $this->user . ' assign lab template ' . $lab[0]->name . ' to team ' . implode(",", $failed) .
                        ' in the group ' . $input['group_name'] . ' failed.'
                ]);
            }
            $response = array('success' => $success, 'failed' => $failed);
            return Response::json($response);
        }
    }

//    function assignTemplateButton()
//    {
//        if (Request::ajax()) {
//            $input = Input::all();
//            // profile database
//            $servername = "10.2.255.50";
//            $username = "root";
//            $password = "Cloud\$erver";
//            $database = "mobicloud";
//            $port = 3307;
//            // return json
//            $arr = array();
////            $arr["templates"] = array();
//            // connection
//            //$conn = new mysqli($servername, $username, $password, $database, $port);
//            //if ($conn->connect_error) {
//            //    die("Connection failed: " . $conn->connect_error);
//            //} else {
//                $subgrouparr = explode(',', $input["user_email"]);
//                foreach ($subgrouparr as $subgroup) {
//                    $sqlinsertlasttemplate = "UPDATE subgroups SET lastassigntemplateid=" . $input["temp_id"] . " WHERE id=" . $subgroup;
//                    //$resultinsert = $conn->query($sqlinsertlasttemplate);
//                    $resultinsert = DB::update($sqlinsertlasttemplate);
//                    if ($resultinsert) {
//                        $resultinsert = false;
//                    }
//
//                    $sqlusers = "SELECT users.email FROM users_subgroups JOIN users ON users_subgroups.user_id=users.id WHERE users_subgroups.subgroup_id = " . $subgroup;
//                    //$result = $conn->query($sqlusers);
//                    $result = DB::select($sqlusers);
//                    //while ($row = $result->fetch_assoc()) {
//                    foreach($result as $row) {
//                        $useremail = $row->email;
//                        $sql = "INSERT INTO temp (name, temp, description, lab_vis_json) SELECT name, temp, description, lab_vis_json FROM temp WHERE id=" . $input["temp_id"];
//                        //$result1 = $conn->query($sql);
//                        $result1 = DB::insert($sql);
//                        if ($result1 === TRUE) {
//                            $last_id = $conn->insert_id;
//                            $sql2 = "INSERT INTO user_temp (userid, tempid) VALUES ((SELECT id FROM users WHERE users.email='" . $useremail . "'), " . $last_id . ")";
//                            //$result2 = $conn->query($sql2);
//                            $result2 = DB::insert($sql2);
//                            if ($result2) {
//                                $result2 = false;
//                            }
//                        }
//                    }
//                }
//
//                // close connection
////                mysqli_close($conn);
//            //}
//
//            return Response::json($arr);
//        }
//    }

    public function getOwnClassLabTemp(Request $request)
    {
        if ($request->ajax()) {
            $arr = array();
            $labtemps = DB::select("SELECT DISTINCT openlab_temp.lab_name, openlab_temp.temp_id, openlab_temp.id FROM openlab_temp " .
                "WHERE openlab_temp.useremail = ?", array($this->user));
            foreach ($labtemps as $labtemp) {
                array_push($arr, array("coursename" => $labtemp->lab_name, "tempid" => $labtemp->temp_id, "labid" => $labtemp->id));
            }
            return Response::json($arr);
        }
    }

    public function getOpenClassLabTemp(Request $request)
    {
        if ($request->ajax()) {
            $arr = array();
            $labtemps = DB::select("Select DISTINCT open_lab.lab_name, openlab_temp.temp_id, openlab_temp.id FROM open_lab " .
                                 "JOIN openlab_temp ON (open_lab.term_id = openlab_temp.term_id AND open_lab.lab_id = openlab_temp.lab_id)");
            foreach ($labtemps as $labtemp) {
                array_push($arr, array("coursename" => $labtemp->lab_name, "tempid" => $labtemp->temp_id, "labid" => $labtemp->id));
            }
            return Response::json($arr);
        }
    }

    public function update_lab_info(Request $request)
    {
        if ($request->ajax()) {
            $labs = $request->get('labs');
            $lab_name = $request->get('lab_name');
            $lab_due = $request->get('lab_due');
            $desc = $request->get('desc');
            $response = array('status' => 'Failed', 'message' => 'Update lab information failed.');

            foreach ($labs as $lab) {
                $result = DB::update('UPDATE subgroup_template_project AS s ' .
                    'SET s.lab_name = ?, s.description = ?, s.due_at = ? ' .
                    'WHERE s.subgroup_id = ? AND s.template_id = ?', array($lab_name, $desc, $lab_due, $lab['team_id'], $lab['temp_id']));
                if ($result) {
                    $response = array('status' => 'Success', 'message' => '');
                }
            }
            return Response::json($response);
        }
    }

    function deploy_lab(Request $request)
    {
        if ($request->ajax()) {
            $groupname = $request->get('groupname');
            $lab = $request->get("lab");
            //$deploy_at = Input::get("datetime");

            $cloudRes = App::make('App\Http\Controllers\CloudV2Resource');
            $heat = App::make('App\Http\Controllers\HeatController');
            $proj_desc = "project for group: " . $groupname . ", team: " . $lab['team_name'] . ", lab: " . $lab['lab_name'] . ", template: " . $lab['temp_name'];
            $project = $cloudRes->createProjectFromIV2($lab['temp_id'], $lab['project_name'], $proj_desc);
            if ($project['status'] == 'Success') {
                $tenant = $cloudRes->setProjectMemberFromIV2($lab['team_id'], $lab['project_name'],$groupname);
                if ($tenant) {
                    $template = array();
                    if ($lab['temp_id'] == 0) {
                        $template['status'] = 'Success';
                    } else {
                        $template = $heat->getTempDesignFromI($lab['temp_id']);
                    }
                    if ($template['status'] == 'Success') {
                        $stack = array('status' => 'Success');
                        $status = 'CREATE_COMPLETE';
                        if ($lab['temp_id'] != 0) {
                            $stack = $cloudRes->createStackFromIV2("s-" . $lab['project_name'], $lab['project_name'], $template['template']['temp']);
                            if ($stack['status'] != 'Fail') {
                                $status = $cloudRes->getStackStatusV2("s-" . $lab['project_name'], $lab['project_name']);
                            }
                        }
                        $sql = DB::update('UPDATE deploylabs SET status = ? ' .
                            'WHERE subgroup_id=? AND lab_id=? AND project_id=?', array($status, $lab['team_id'], $lab['temp_id'], $lab['project_id']));
                        if ($stack['status'] == 'Success') {
//                            while ($status === 'CREATE_IN_PROGRESS') {
//                                //sleep(5);
//                                $status = $this->cloudRes->getStackStatus("s-".$lab['project_name'], $lab['project_name']);
//                            }
//                            $sql = DB::update('UPDATE subgroup_template_project SET deploy_at=NOW(), status = ? ' .
//                                'WHERE subgroup_id=? AND template_id=? AND project_name=? ', array($status, $lab['team_id'], $lab['temp_id'], $lab['project_name']));
//                            Activity::log(['contentType' => 'Cloud',
//                                'action' => 'Deploy Lab',
//                                'description' => 'Deploy a lab',
//                                'details' => 'Deploy template ' . $lab['temp_name'] . ' for team ' . $lab['team_name'] . ' in group ' . $groupname
//                            ]);
                            $response = array('status' => 'Success', 'deploy_status' => 'CREATE_COMPLETE');
                        } else {
                            Activity::log(['contentType' => 'Cloud',
                                'action' => 'Failed',
                                'description' => 'Deploy a lab template failed when create a stack',
                                'details' => 'Deploy template ' . $lab['temp_name'] . ' for team ' . $lab['team_name'] . ' in group ' . $groupname .
                                    ' failed when create a stack. Error: ' . $stack['message']
                            ]);
                            $response = array('status' => 'Failed', 'deploy_status' => 'CREATE_FAILED');
                        }
                    } else {
                        $sql = DB::update("UPDATE deploylabs SET status = 'Get Template Error' " .
                            "WHERE subgroup_id=? AND lab_id=? AND project_name=?", array($lab['team_id'], $lab['temp_id'], $lab['project_name']));
                        Activity::log(['contentType' => 'Cloud',
                            'action' => 'Failed',
                            'description' => 'Deploy a lab template failed when getting a template design',
                            'details' => 'Deploy template ' . $lab['temp_name'] . ' for team ' . $lab['team_name'] . ' in group ' . $groupname .
                                ' failed when getting a template design.'
                        ]);
                        $response = array('status' => 'Failed', 'deploy_status' => 'TEMPLATE_ERROR');
                    }
                } else {
                    $sql = DB::update("UPDATE deploylabs SET status = 'Set project Member Error' " .
                        "WHERE subgroup_id=? AND lab_id=? AND project_name=?", array($lab['team_id'], $lab['temp_id'], $lab['project_name']));

                    Activity::log(['contentType' => 'Cloud',
                        'action' => 'Failed',
                        'description' => 'Deploy a lab template failed when setting project member',
                        'details' => 'Deploy template ' . $lab['temp_name'] . ' for team ' . $lab['team_name'] . ' in group ' . $groupname .
                            ' failed when setting project member.'
                    ]);
                    $response = array('status' => 'Failed', 'deploy_status' => 'MEMBER_ERROR');
                }
            } else {
                $sql = DB::update("UPDATE deploylabs SET status = 'Create Project Error' " .
                    "WHERE subgroup_id=? AND lab_id=? AND project_name=?", array($lab['team_id'], $lab['temp_id'], $lab['project_name']));
                Activity::log(['contentType' => 'Cloud',
                    'action' => 'Faeiled',
                    'description' => 'Deploy a lab template failed when creating a project',
                    'details' => 'Deploy template ' . $lab['temp_name'] . ' for team ' . $lab['team_name'] . ' in group ' . $groupname .
                        ' failed when creating a project.'
                ]);
                $response = array('status' => 'Failed', 'deploy_status' => 'PROJECT_FAILED');
            }
            //$response = array('status' => 'Success', 'deploy_status' => 'CREATE_COMPLETE');
            return Response::json($response);
        }
    }

    function delete_stack(Request $request)
    {
        if ($request->ajax()) {
            $groupname = $request->get('groupname');
            $lab = $request->get("lab");

            $cloudRes = App::make('App\Http\Controllers\CloudV2Resource');

            if ($cloudRes->isExistStackV2('s-' . $lab['project_name'], $lab['project_name']) == null) {
                $response = array('status' => 'Success', 'message' => 'Stack does not exist');
                return Response::json($response);
            }
            $res_stack = $cloudRes->deleteStackFromIV2('s-' . $lab['project_name'], $lab['project_name']);
            if ($res_stack['status'] == 'Fail') {
                $response = array('status' => 'Fail', 'message' => 'Delete stack failed');
                return Response::json($response);
            }
            //sleep(5);
            $res_stack = $cloudRes->isExistStackV2('s-' . $lab['project_name'], $lab['project_name']);
            if ($res_stack == null) {
                DB::update('UPDATE subgroup_template_project SET status=? ' .
                    'WHERE subgroup_id=? AND template_id = ? AND project_name=?',
                    array($res_stack->getStatus(), $lab['team_id'], $lab['temp_id'], $lab['project_name']));
                $response = array('status' => 'Success', 'message' => 'Stack delete complete');
                //$response = array('status' => 'Success', 'message' => 'Stack delete complete');
            } else {
                DB::update('UPDATE subgroup_template_project SET status=? ' .
                    'WHERE subgroup_id=? AND template_id = ? AND project_name=?',
                    array($res_stack->getStatus(), $lab['team_id'], $lab['temp_id'], $lab['project_name']));
                $response = array('status' => 'Fail', 'message' => $res_stack->getStatus());
            }
            return Response::json($response);
        }
    }

    function delete_project(Request $request)
    {
        if ($request->ajax()) {
            $groupname = $request->get('groupname');
            $lab = $request->get("lab");

            $cloudRes = App::make('App\Http\Controllers\CloudV2Resource');

            if ($cloudRes->deleteProjectV2($lab['project_name'])) {
//                Activity::log(['contentType' => 'Cloud',
//                    'action' => 'Delete a Project',
//                    'description' => 'Delete a Project',
//                    'details' => 'Delete project ' . $lab['project_name'] . ' from team ' . $lab['team_name'] . ' in group ' . $groupname
//                ]);

                $result = DB::delete('DELETE FROM subgroup_template_project WHERE subgroup_id=? ' .
                    'AND template_id=? AND project_name=?', array($lab['team_id'], $lab['temp_id'], $lab['project_name']));
                if ($result) {
                    $response = array('status' => 'Success', 'message' => 'Lab deleted.');
//                    Activity::log(['contentType' => 'Cloud',
//                        'action' => 'Delete a Lab',
//                        'description' => 'Delete a lab',
//                        'details' => 'Delete lab ' . $lab['lab_name'] . ' with template ' . $lab['temp_name'] .
//                            ' for team ' . $lab['team_name'] . ' in group ' . $groupname
//                    ]);
                } else {
                    $response = array('status' => 'Fail', 'message' => 'Delete lab failed.');
//                    Activity::log(['contentType' => 'Lab',
//                        'action' => 'Failed',
//                        'description' => 'Delete a lab failed',
//                        'details' => 'Delete lab ' . $lab['lab_name'] . ' with template ' . $lab['temp_name'] .
//                            ' for team ' . $lab['team_name'] . ' in group ' . $groupname . ' failed.'
//                    ]);
                }
            } else {
                DB::update('UPDATE subgroup_template_project SET status=? ' .
                    'WHERE subgroup_id=? AND template_id = ? AND project_name=?',
                    array('Project delete fail', $lab['team_id'], $lab['temp_id'], $lab['project_name']));
//                Activity::log(['contentType' => 'Cloud',
//                    'action' => 'Failed',
//                    'description' => 'Delete a project',
//                    'details' => 'Delete project ' . $lab['project_name'] . ' from team ' . $lab['team_name'] . ' in group ' . $groupname . ' failed.'
//                ]);
                $response = array('status' => 'Fail', 'message' => 'Delete project Failed.');
            }
            return Response::json($response);
        }
    }

    function delete_lab()
    {
        if (Request::ajax()) {
            $groupname = Input::get('groupname');
            $lab = Input::get("lab");

            //if ($lab['status'] == 'CREATE_IN_PROGRESS' || $lab['status'] == 'CREATE_COMPLETE') {
            $stack = $this->cloudRes->isExistStack('s-' . $lab['project_name'], $lab['project_name']);
            //$res_stack = $this->cloudRes->deleteStackFromI('s-' . $lab['project_name'], $lab['project_name']);
            $res_stack = array('status' => 'Success');
            if ($stack !== null) {
                $res_stack = $this->cloudRes->deleteStackFromI('s-' . $lab['project_name'], $lab['project_name']);
            }
            $stack_res = null;
            do {
                $stack_res = $this->cloudRes->isExistStack('s-' . $lab['project_name'], $lab['project_name']);
            } while ($stack_res != null and $stack_res->getStatus() == "DELETE_IN_PROGRESS");

            if ($res_stack['status'] == 'Success' and $stack_res == null) {
                if ($this->cloudRes->deleteProjectV2($lab['project_name'])) {
//                    Activity::log(['contentType' => 'Cloud',
//                        'action' => 'Delete a Project',
//                        'description' => 'Delete a Project',
//                        'details' => 'Delete project ' . $lab['project_name'] . ' from team ' . $lab['team_name'] . ' in group ' . $groupname
//                    ]);

                    $result = DB::delete('DELETE FROM subgroup_template_project WHERE subgroup_id=? ' .
                        'AND template_id=? AND project_name=?', array($lab['team_id'], $lab['temp_id'], $lab['project_name']));
                    if ($result) {
                        $response = array('status' => 'Success', 'message' => 'Lab deleted.');
                        Activity::log(['contentType' => 'Cloud',
                            'action' => 'Delete a Lab',
                            'description' => 'Delete a lab',
                            'details' => 'Delete lab ' . $lab['lab_name'] . ' with template ' . $lab['temp_name'] .
                                ' for team ' . $lab['team_name'] . ' in group ' . $groupname
                        ]);
                    } else {
                        $response = array('status' => 'Fail', 'message' => 'Delete lab failed.');
                        Activity::log(['contentType' => 'Lab',
                            'action' => 'Failed',
                            'description' => 'Delete a lab failed',
                            'details' => 'Delete lab ' . $lab['lab_name'] . ' with template ' . $lab['temp_name'] .
                                ' for team ' . $lab['team_name'] . ' in group ' . $groupname . ' failed.'
                        ]);
                    }
                } else {
                    Activity::log(['contentType' => 'Cloud',
                        'action' => 'Failed',
                        'description' => 'Delete a project',
                        'details' => 'Delete project ' . $lab['project_name'] . ' from team ' . $lab['team_name'] . ' in group ' . $groupname . ' failed.'
                    ]);
                    $response = array('status' => 'Fail', 'message' => 'Delete project Failed.');
                }
            } else {
                $result = DB::update('UPDATE subgroup_template_project SET status=? ' .
                    'WHERE subgroup_id=? AND template_id = ? AND project_name=?', array($stack_res->getStatus(), $lab['team_id'], $lab['temp_id'], $lab['project_name']));

                Activity::log(['contentType' => 'Cloud',
                    'action' => 'Failed',
                    'description' => 'Delete a Stack',
                    'details' => 'Delete stack s-' . $lab['project_name'] . ' from team ' . $lab['team_name'] . ' in group ' . $groupname . ' failed.'
                ]);
                $response = array('status' => 'Fail', 'message' => 'Delete Stack Failed.');
            }
            //}
            //$response =array('status' => 'Success', 'message' => 'ok');
            return Response::json($response);
        }
    }

    public function getOpenLabs()
    {
        if (Request::ajax()) {

            // profile database
            $servername = "10.2.255.50";
            $username = "root";
            $password = "Cloud\$erver";
            $database = "mobicloud";
            $port = 3306;
            $labId = $_GET['labId'];
            $labnames = array();
            // connection
            $conn = new mysqli($servername, $username, $password, $database, $port);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } else {
                // group role
                $sql = "Select * FROM  open_lab where lab_id LIKE '$labId%'"  ;
                $result = $conn->query($sql);
                if ($result->num_rows == 0) {
                    return "No errors found";
                } else {
                    $i = 0;
                    while ($row = $result->fetch_assoc()) {
                        $labnames[0][$i] = $row["lab_id"];
                        $labnames[1][$i] = $row["term_id"];
                        $labnames[2][$i++] = $row["lab_name"];
                    }
                }
                // close connection
                mysqli_close($conn);
            }
            return Response::json($labnames);
        }
    }

    public function getWorkingLabList(Request $request)
    {
        if ($request->ajax()) {
            $arr = array();
            $labs = DB::select("SELECT DISTINCT groups.name AS group_name, " .
                "subgroups.name, openlab_temp.lab_name, subgroup_template_project.project_name,  " .
                "subgroup_template_project.status, subgroup_template_project.deploy_at,  " .
                "subgroup_template_project.due_at, openlab_temp.lab_id, openlab_temp.term_id " .
                "FROM groups JOIN subgroups ON groups.id = subgroups.group_id " .
                "JOIN subgroup_template_project ON subgroups.id = subgroup_template_project.subgroup_id " .
                "JOIN users_subgroups ON users_subgroups.subgroup_id = subgroups.id " .
                "JOIN users ON users.id = users_subgroups.user_id " .
                "JOIN users_groups on users.id = users_groups.user_id " .
                "JOIN openlab_temp ON subgroup_template_project.lab_id = openlab_temp.id " .
                "WHERE users_groups.role_id = 13 AND subgroup_template_project.status = 'CREATE_COMPLETE' " .
                "AND users.email = ?", array($this->user));
            if (!$labs) {
                $temp = array("group" => "No Working Lab", "team" => "", "lab" => "", "project" => "", "deployat" => "");
                array_push($arr, $temp);
            } else {
                foreach ($labs as $lab) {
                    $temp = array("group" => $lab->group_name, "team" => $lab->name, "lab" => $lab->lab_name,
                        "project" => $lab->project_name, "deploy_at" => $lab->deploy_at, "due_at" => $lab->due_at,
                        "openlab" => $lab->lab_id, "term" => $lab->term_id);
                    array_push($arr, $temp);
                }
            }
            return Response::json($arr);
        }
    }

}