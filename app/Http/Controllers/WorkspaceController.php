<?php
namespace App\Http\Controllers;

use App, View;
use App\User;
use App\Role;
use Sentry;
use Xavrsl\Cas\Facades\Cas;
use Regulus\ActivityLog\Models\Activity;
use Response, Session, Redirect, DB;
use Illuminate\Http\Request;

//define('FRESHDESK_BASE_URL','http://thothlab.freshdesk.com/');
class WorkspaceController extends Controller
{
    protected $user;
    protected $password;
    protected $cloudRes;
    protected $heat;
    protected $role;
    protected $applicant;
    protected $applicantGroup;
    protected $avatarlink;

    public function __construct()
    {
        if (Cas::isAuthenticated()) {
            $this->user = Cas::getCurrentUser();
            //$this->cloudRes = App::make('App\Http\Controllers\CloudResource');
            //$this->heat = App::make('App\Http\Controllers\HeatController');
        } else {
            return Redirect::to('myworkspace');
        }
    }

    public function keepAlive()
    {
        Session::put('LAST_ACTIVITY', time());
        //Session::set('LAST_ACTIVITY', time());
        return true;
    }

    public function getActivityLog(Request $request)
    {
        if ($request->ajax()) {
            $userEmail = Cas::getCurrentUser();

            $matchKeyValuePairs = ['user_email' => $userEmail];
            $body = \ElasticsearchQueryHelper::generateSearchWithMatchClauses($matchKeyValuePairs);
            \ElasticsearchQueryHelper::addSortClauseToQuery($body, "updated_at", \ElasticsearchQueryHelper::DESCENDING);
            $searchBody = json_encode($body);

            $response = App::make('ElasticsearchResource')->searchDocuments($searchBody,
	            ElasticsearchResource::APPSERVER_INDEX, ElasticsearchResource::ACTIVITY_DOCTYPE);

            $logs = array();
            if ($response['status'] == ElasticsearchResource::SUCCESS) {
                foreach ($response['response']['hits']['hits'] as $hit) {
                    $row = $hit['_source'];
                    $log = array(
                        "timestamp" => $row['created_at'],
                        "type" => $row['content_type'],
                        "action" => $row['action'],
                        "description" => $row['description'],
                        "details" => $row['details'],
                        "ip_address" => $row['ip_address'],
                        "agent" => $row['user_agent']
                    );
                    array_push($logs, $log);
                }
            }
            return Response::json($logs);
        }
    }

    public function getWorkspace()
    {
        if (Cas::isAuthenticated()) {
            $this->user = Cas::getCurrentUser();
//            $result = DB::select("SELECT permission.permissiondetails FROM permission WHERE permission.id IN " .
//                "(SELECT users_groups.role_id FROM users_groups JOIN users ON users_groups.user_id=users.id " .
//                "WHERE users.email = ?)", array($this->user));
//
//            $permissions = array();
//            foreach ($result as $row) {
//                $permissions = array_merge($permissions, explode(':', $row->permissiondetails));
//            }
//            $this->role = implode(':', $permissions);

            $user = User::find(Sentry::getUser()->getId());
            $this->role = $user->roles()->get()->pluck('name')->toArray();

            $arr = array("user" => $this->user);
            //$sql = "SELECT users.avatar, users.id, users.alt_email, user_profiles.first_name, user_profiles.last_name, user_profiles.phone, user_profiles.address, user_profiles.city, user_profiles.state, user_profiles.country, user_profiles.zip, users.institute, users.org_id FROM user_profiles JOIN users ON user_profiles.user_id=users.id WHERE users.email='" . $this->user . "'";
            $result = DB::select("SELECT users.avatar, users.id, users.alt_email, user_profiles.first_name, user_profiles.last_name, user_profiles.phone, user_profiles.address, user_profiles.city, user_profiles.state, user_profiles.country, user_profiles.zip, users.institute, users.org_id " .
                                        "FROM user_profiles JOIN users ON user_profiles.user_id = users.id WHERE users.email = ?", array($this->user));

            foreach ($result as $row) {
                if ($row->avatar == "") {
                    $this->avatarlink = "default.jpg";
                } else {
                    $this->avatarlink = $row->avatar;
                }
                $arr["user_profile"] = array(
                    "avatar" => $row->avatar,
                    "user_id" => $row->id,
                    "alt_email" => $row->alt_email,
                    "first_name" => $row->first_name,
                    "last_name" => $row->last_name,
                    "phone" => $row->phone,
                    "address" => $row->address,
                    "city" => $row->city,
                    "state" => $row->state,
                    "country" => $row->country,
                    "zip" => $row->zip,
                    "institute" => $row->institute//,
                    //"org_id" => $row->org_id
                );
            }

            //if ($this->cloudRes->checkIsUserEnabled()) {
                return View::make('workspace', array(
                    'user' => $this->user, 'roles' => $this->role, 'avatar' => "files/".$this->avatarlink
                ));
            //}
        }
        return Redirect::route('user.login');
    }

    public function getProjects(Request $request)
    {
        if ($request->ajax()) {
            $project_list = $this->cloudRes->getProjects();
            return Response::json($project_list);
        }
    }

    public function uploadImage()
    {
        $valid_exts = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
        $max_size = 2000 * 1024; // max file size (200kb)
        $path = public_path() . '/img/avatar'; // upload directory
        $fileName = NULL;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //$file = Input::file('uploaded_img');
            $file = request()->file('files');
            // get uploaded file extension
            //$ext = $file['extension'];
            $ext = $file->guessClientExtension();
            // get size
            $size = $file->getClientSize();
            // looking for format and size validity
            $name = $file->getClientOriginalName();
            if (in_array($ext, $valid_exts) AND $size < $max_size) {
                // move uploaded file from temp to uploads directory
                if ($file->move($path, $name)) {
                    $status = 'Image successfully uploaded!';
                    $fileName = $name;
                } else {
                    $status = 'Upload Fail: Unknown error occurred!';
                }
            } else {
                $status = 'Upload Fail: Unsupported file format or It is too large to upload!';
            }
        } else {
            $status = 'Bad request!';
        }
        // echo out json encoded status
        return header('Content-type: application/json') . json_encode(array(
            'status' => $status,
            'fileName' => $fileName
        ));
    }

    public function updateRoleJson(Request $request)
    {
        if ($request->ajax()) {
            $input = Input::get('role_json');
            $arr = array("user" => $this->user);
            $sql = "update role_resource set resource='" . $input . "' WHERE role_id = 17";
            $result = DB::update($sql);
            if ($result){
                $result = false;
            }
            return Response::json($arr);
        }
    }

    public function checkTrialRole() {
        $result = DB::select("SELECT users_groups.subgroup, groups.name AS 'group', permission.description AS 'role' FROM users_groups JOIN users ON users_groups.user_id=users.id JOIN groups ON users_groups.group_id=groups.id JOIN permission ON users_groups.role_id=permission.id WHERE users.email=? AND users.id<>groups.owner_id", array($this->user));
        foreach($result as $row) {
//            $groupRole = array("group" => $row->group, "role" => $row->role, "subgroup" => $row->subgroup);
            if ( $row->role == 'Trial') {
                return true;
            }
        }
        return false;
    }



    public function hearbeat(Request $request) {
        if ($request->ajax()) {
            // return json
            $arr = array("user" => $this->user);
            $sql = "Update users SET users.last_activity=now() WHERE users.email='" . $this->user ."'";

            $result = DB::update($sql);
            if (!$result) {
                $result = false;
            }
                return Response::json($arr);
        }
    }

    public function queryHearbeat(Request $request) {
        if ($request->ajax()) {
            $input = $request->get('query_user');
            $arr = array("status" => 'offline');

            $sql = "Select users.email FROM users WHERE (users.last_activity> now()- INTERVAL 3 MINUTE ) AND users.email='" . $input . "'";
            $result = DB::select($sql);
            foreach ($result as $row) {
                $arr = array("status" => 'online');
            }
            return Response::json($arr);
        }
    }
    public function queryHearbeatBatch(Request $request) {
        if ($request->ajax()) {
            $input = $request->get('query_user');
            $arr = array();

            $sql = "Select users.email FROM users WHERE (users.last_activity> now()- INTERVAL 3 MINUTE ) AND users.email IN (" . $input .")";
            $result = DB::select($sql);
            foreach ($result as $row) {
                array_push($arr, $row->email);
            }
            return Response::json($arr);
        }
    }


    function getTemplate(Request $request)
    {
        if ($request->ajax()) {
            $input = Input::all();
            // profile database
            $servername = "10.2.255.50";
            $username = "root";
            $password = "Cloud\$erver";
            $database = "mobicloud";
            $port = 3307;
            // return json
            $arr = array();
            $arr["templates"] = array();
            // connection
            //$conn = new mysqli($servername, $username, $password, $database, $port);
            //if ($conn->connect_error) {
            //    die("Connection failed: " . $conn->connect_error);
            //} else {
                $sql = "SELECT temp.id, temp.name, temp.temp, temp.description " .
                    "FROM user_temp " .
                    "JOIN temp ON user_temp.tempid=temp.id " .
                    "JOIN users ON user_temp.userid=users.id " .
                    "WHERE users.email='" . $this->user . "';";
                //$result = $conn->query($sql);
                $result = DB::select($sql);
                //while ($row = $result->fetch_assoc()) {
                foreach($result as $row) {
                    $member = array("id" => $row->id, "name" => $row->name, "temp" => $row->temp, "description" => $row->description);
                    array_push($arr["templates"], $member);
                }

                // close connection
//                mysqli_close($conn);
            //}

            return Response::json($arr);
        }
    }

    function getWallPaper(Request $request)
    {
        if ($request->ajax()) {
            $arr = array();
            $sql = "SELECT wall_paper  " .
                "FROM users " .
                "WHERE users.email='" . $this->user . "';";
            $result = DB::select($sql);
            foreach($result as $row) {
                $arr["wall_paper"] = $row->wall_paper;
//              $member = array("wall_paper" => $row["wall_paper"]);
//              array_push($arr["members"], $member);
            }
            return Response::json($arr);
        }
    }


    function setWallPaper(Request $request)
    {
        if ($request->ajax()) {
            $input_wall_paper = $request->get("wall_paper");
            $sql = "UPDATE users SET wall_paper= '" . $input_wall_paper . "' WHERE email='" . $this->user . "';";
            $result = DB::update($sql);
//                if ($result) {
//                    Activity::log(['contentType' => 'Wallpaper',
//                        'action' => 'Set Wallpaper',
//                        'description' => 'User set a wallpaper',
//                        'details' => 'User ' . $this->user . ' set the wallpaper to ' . $input["wall_paper"]
//                    ]);
//                } else {
//                    Activity::log(['contentType' => 'Wallpaper',
//                        'action' => 'Failed',
//                        'description' => 'User set a wallpaper failed',
//                        'details' => 'User ' . $this->user . ' set the wallpaper ' . $input["wall_paper"] . ' failed.'
//                    ]);
//                }
            return Response::json(true);
        }
    }

    function acceptFile()
    {
        print_r($_POST);
        print_r($_FILES);
    }


    public function  update_Lab_Project(Request $request)
    {
        if ($request->ajax()) {
            $project_name = $request->get('project_name');
            $status = $request->get("status");
            $deploy_at = $request->get("deploy_at");

            $result = DB::update('UPDATE subgroup_template_project SET status = ?, deploy_at=? ' .
                'WHERE project_name=?', array($status, $deploy_at, $project_name));
            if ($result) {
                $response = array('status' => 'Success', 'message' => 'Stack Created.');
                Activity::log(['contentType' => 'Cloud',
                    'action' => 'Create a stack',
                    'description' => 'Create a stack',
                    'details' => 'Stack ' . $project_name . ' Created'
                ]);
            }
            else {
                $response = array('status' => 'Failed', 'message' => 'Create Stack Failed.');
                Activity::log(['contentType' => 'Cloud',
                    'action' => 'Create a stack',
                    'description' => 'Create a statck',
                    'details' => 'Stack ' . $project_name . ' Created'
                ]);
            }
            return Response::json($response);
        }
    }

//    public function getHelpUrl() {
//        if (Cas::isAuthenticated()) {
//            $this->user = Cas::getCurrentUser();
//            $timestamp = time();
//            $to_be_hashed = $this->user . '9892d25c94f42dd1f11a1a9ebae41d0f' . $this->user . $timestamp;
//            $hash = hash_hmac('md5', $to_be_hashed, '9892d25c94f42dd1f11a1a9ebae41d0f');
//            return FRESHDESK_BASE_URL . "login/sso/?name=" . urlencode($this->user) . "&email=" . urlencode($this->user) . "&timestamp=" . $timestamp . "&hash=" . $hash;
//        }
//    }

}
