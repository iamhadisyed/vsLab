<?php
namespace App\Http\Controllers;

use App, Request, mysqli, Response, Input;
use Xavrsl\Cas\Facades\Cas;
use MongoDB;
use OpenStack\OpenStack;

class HeatController extends Controller
{

    protected $user;
    protected $password;
    protected $cloudRes;
    protected $role;

    public function __construct()
    {
        if (Cas::isAuthenticated()) {
            $this->user = Cas::getCurrentUser();

            //$this->cloudRes = App::make('CloudResource');
        } else {
            return redirect('/');
        }
    }

    public function saveTemp()
    {
        if (Request::ajax()) {
            $input = Input::all();

// profile database
            $servername = "127.0.0.1";
            $username = "root";
            $password = "Cloud\$erver";
            $database = "mobicloud";
            $port = 3306;
// connection
            $conn = new mysqli($servername, $username, $password, $database, $port);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } else {

                $sql = "INSERT INTO lab_temp (name, temp, description,lab_vis_json,shared,verified,created_at,updated_at,vmcount) VALUES ('" . $input['temp_name'] . "', '" . $input['temp'] . "', '" . $input['temp_des'] . "', '" . $input['temp_design'] . "',0,1,NOW(),NOW(),".$input['temp_vmcount'].")";
                $result = $conn->query($sql);
                if ($result == TRUE) {
                    echo "save temp success";
                } else {
                    echo "save temp failure : " . $conn->error;
                }
                $user_id = "(SELECT id FROM users WHERE email='" . $this->user . "')";
                $temp_id = $conn->insert_id;
                $sql = "INSERT INTO user_temp (userid, tempid) VALUES ( " . $user_id . ", " . $temp_id . ")";
                $result = $conn->query($sql);
                if ($result == TRUE) {
                    echo "save user_temp success";
                } else {
                    echo "save user_temp failure : " . $conn->error;
                }
// close connection
//                mysqli_close($conn);
            }
        }
    }

    public function assignTemp()
    {
        if (Request::ajax()) {
            $input = Input::all();

// profile database
            $servername = "10.8.0.71";
            $username = "root";
            $password = "Cloud\$erver";
            $database = "mobicloud";
            $port = 3306;
// connection
            $conn = new mysqli($servername, $username, $password, $database, $port);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } else {

                $sql = "INSERT INTO openlab_temp (lab_id, term_id, temp_id,lab_name,lab_desc,useremail,difficulty) VALUES ('" . $input['content_id'] . "','" . $input['name_id'] . "','" . $input['temp_id'] . "','" . $input['lab_name'] . "','" . $input['desc'] . "','".$this->user."',0)";
                $result = $conn->query($sql);
                if ($result == TRUE) {
                    echo "Assigned temp success";
                } else {
                    echo "Assigned temp failure : " . $conn->error;
                }

            }
        }
    }

    public function updateAssignTemp()
    {
        if (Request::ajax()) {
            $input = Input::all();

// profile database
            $servername = "10.8.0.71";
            $username = "root";
            $password = "Cloud\$erver";
            $database = "mobicloud";
            $port = 3306;
// connection
            $conn = new mysqli($servername, $username, $password, $database, $port);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } else {

                //$sql = "INSERT INTO openlab_temp (lab_id, term_id, temp_id,lab_name,lab_desc,useremail) VALUES ('" . $input['content_id'] . "','" . $input['name_id'] . "'," . $input['temp_id'] . ",'" . $input['lab_name'] . "','" . $input['desc'] . "','".$this->user."')";
                $sql= "UPDATE openlab_temp SET lab_id ='".$input['content_id']."',term_id = '".$input['name_id']."',temp_id = ".$input['temp_id'].",lab_name = '".$input['lab_name']."',lab_desc ='".$input['desc']."' WHERE id = ".$input['id'];
                $result = $conn->query($sql);
                if ($result == TRUE) {
                    echo "Assigned temp success";
                } else {
                    echo "Assigned temp failure : " . $conn->error;
                }

            }
        }
    }

    public function deleteAssignTemp()
    {
        if (Request::ajax()) {
            $input = Input::all();

// profile database
            $servername = "10.8.0.71";
            $username = "root";
            $password = "Cloud\$erver";
            $database = "mobicloud";
            $port = 3306;
// connection
            $conn = new mysqli($servername, $username, $password, $database, $port);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } else {

                //$sql = "INSERT INTO openlab_temp (lab_id, term_id, temp_id,lab_name,lab_desc,useremail) VALUES ('" . $input['content_id'] . "','" . $input['name_id'] . "'," . $input['temp_id'] . ",'" . $input['lab_name'] . "','" . $input['desc'] . "','".$this->user."')";
                $sql= "DELETE FROM openlab_temp  WHERE id = ".$input['id'];
                $result = $conn->query($sql);
                if ($result == TRUE) {
                    echo "Assigned temp success";
                } else {
                    echo "Assigned temp failure : " . $conn->error;
                }

            }
        }
    }

    public function updateTemp()
    {
        if (Request::ajax()) {
            $input = Input::all();

            // profile database
            $servername = "10.8.0.71";
            $username = "root";
            $password = "Cloud\$erver";
            $database = "mobicloud";
            $port = 3306;
            // connection
            $conn = new mysqli($servername, $username, $password, $database, $port);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } else {
                // group role
                $sql = "UPDATE lab_temp SET " .
                    "lab_temp.temp='" . $input['temp'] . "'" .
                    ", lab_temp.lab_vis_json='" . $input['temp_design'] . "'" .
                    ",lab_temp.verified=1,lab_temp.updated_at= NOW() " .
                    ",lab_temp.vmcount='" . $input['temp_vmcount'] . "'" .
                    "WHERE lab_temp.id='" . $input['temp_id'] . "'";
                $result = $conn->query($sql);
                if ($result == TRUE) {
                    $response = array(
                        'status' => 'Success',
                        'msg' => 'Lab design has been successfully updated.'
                    );
                    return Response::json($response);
                } else {
                    echo "update failure : " . $conn->error;
                }

                // close connection
//                mysqli_close($conn);
            }
        }
    }

    public function shareTemp()
    {
        if (Request::ajax()) {
            $input = Input::all();

            // profile database
            $servername = "10.8.0.71";
            $username = "root";
            $password = "Cloud\$erver";
            $database = "mobicloud";
            $port = 3306;
            // connection
            $conn = new mysqli($servername, $username, $password, $database, $port);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } else {
                // group role
                $user_id = "(SELECT id FROM users WHERE email='" . $input['user_email'] . "')";
                $sql = "INSERT INTO user_temp (userid, tempid) VALUES ( " . $user_id . ", " . $input['temp_id'] . ")";
                $result = $conn->query($sql);
                $sql = "UPDATE lab_temp SET lab_temp.shared = 1 WHERE lab_temp.id ='" . $input['temp_id'] . "'";
                $result = $conn->query($sql);
                if ($result == TRUE) {

                    //echo "save user_temp success";
                } else {
                    //echo "save user_temp failure : " . $conn->error;
                }

                // close connection
//                mysqli_close($conn);
            }
            return Response::json(array());
        }
    }

    public function copyTemp()
    {
        if (Request::ajax()) {
            $input = Input::all();

            // profile database
            $servername = "10.8.0.71";
            $username = "root";
            $password = "Cloud\$erver";
            $database = "mobicloud";
            $port = 3306;
            $copied_id = null;
            // connection
            $conn = new mysqli($servername, $username, $password, $database, $port);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } else {
                // group role
                $sql = "INSERT INTO lab_temp(name, temp, description, lab_vis_json) SELECT name, temp, description, lab_vis_json from lab_temp where id = " . $input['temp_id'];
                $result = $conn->query($sql);
                if ($result == TRUE) {
                    $copied_id = $conn->insert_id;
                    //echo "save temp success";
                } else {
                    //echo "save temp failure : " . $conn->error;
                }
                $user_id = "(SELECT id FROM users WHERE email='" . $input['user_email'] . "')";
                $sql = "INSERT INTO user_temp (userid, tempid) VALUES ( " . $user_id . ", " . $copied_id . ")";
                $result = $conn->query($sql);
                if ($result == TRUE) {
                    //echo "save user_temp success";
                } else {
                    //echo "save user_temp failure : " . $conn->error;
                }

                // close connection
//                mysqli_close($conn);
            }
            return Response::json(array());
        }
    }

    public function deleteTemp()
    {
        if (Request::ajax()) {
            $input = Input::all();

            // profile database
            $servername = "10.8.0.71";
            $username = "root";
            $password = "Cloud\$erver";
            $database = "mobicloud";
            $port = 3306;
            // connection
            $conn = new mysqli($servername, $username, $password, $database, $port);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } else {
                // group role
                $sql = "DELETE FROM lab_temp WHERE id='" . $input['temp_id'] . "'";
                $result = $conn->query($sql);
                if ($result == TRUE) {
                    echo "delete temp success";
                } else {
                    echo "delete temp failure : " . $conn->error;
                }
                $sql = "DELETE FROM user_temp WHERE tempid='" . $input['temp_id'] . "'";
                $result = $conn->query($sql);
                if ($result == TRUE) {
                    echo "delete user_temp success";
                } else {
                    echo "delete user_temp failure : " . $conn->error;
                }
                $sql= "DELETE FROM openlab_temp  WHERE temp_id = ".$input['temp_id'];
                $result = $conn->query($sql);
                if ($result == TRUE) {
                    echo "delete private lab success";
                } else {
                    echo "delete private lab failure : " . $conn->error;
                }

                // close connection
//                mysqli_close($conn);
            }
        }
    }

    public function getTestingLab()
    {
        if (Request::ajax()) {

            // profile database
            $servername = "10.8.0.71";
            $username = "root";
            $password = "Cloud\$erver";
            $database = "mobicloud";
            $port = 3306;
            $arr = array();
            // connection
            $conn = new mysqli($servername, $username, $password, $database, $port);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } else {
                // group role

                $sql = "Select projects.name, projects.project_desc FROM projects WHERE projects.user_name='" . $this->user . "'";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    $temp = array("name" => $row["name"], "description" => $row["project_desc"]);
                    array_push($arr, $temp);
                }

                // close connection
//                mysqli_close($conn);
            }
            return Response::json($arr);
        }

    }

    public function getTemp()
    {
        if (Request::ajax()) {

            // profile database
            $servername = "10.8.0.71";
            $username = "root";
            $password = "Cloud\$erver";
            $database = "mobicloud";
            $port = 3306;
            $arr = array();
            // connection
            $conn = new mysqli($servername, $username, $password, $database, $port);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } else {
                // group role
                $user_id = "(SELECT id FROM users WHERE email='" . $this->user . "')";
                $sql = "Select lab_temp.shared,lab_temp.id,lab_temp.name, lab_temp.description FROM user_temp JOIN lab_temp ON user_temp.tempid = lab_temp.id WHERE user_temp.userid IN " . $user_id ." ";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    $temp = array("temp_id" => $row["id"], "temp_name" => $row["name"], "temp_des" => $row["description"], "temp_shared" => $row["shared"]);
                    array_push($arr, $temp);
                }

                // close connection
//                mysqli_close($conn);
            }
            return Response::json($arr);
        }
    }

    public function getOpenTempId($labId)
    {
        if (Request::ajax()) {
            $servername = "10.8.0.71";
            $username = "root";
            $password = "Cloud\$erver";
            $database = "mobicloud";
            $port = 3306;
            $arr = array();
            // connection
            $conn = new mysqli($servername, $username, $password, $database, $port);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } else {
                // group role
                $sql = "Select openlab_temp.temp_id FROM openlab_temp  WHERE lab_id ='" . $labId . "'";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    $temp = $row["temp_id"];

                }

                // close connection
//                mysqli_close($conn);
            }
            return Response::json($temp);
        }

    }

    public function getTempDesign($tempId)
    {
        if (Request::ajax()) {
            // profile database
            $servername = "10.8.0.71";
            $username = "root";
            $password = "Cloud\$erver";
            $database = "mobicloud";
            $port = 3306;
            $lab_temp = array();
            // connection
            $conn = new mysqli($servername, $username, $password, $database, $port);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } else {
                // group role
                $sql = "Select lab_temp.temp, lab_temp.lab_vis_json FROM  lab_temp  WHERE lab_temp.id =" . $tempId;
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    $template = $row["temp"];
                    $lab_temp = array("temp" => $template, "temp_vis" => $row["lab_vis_json"]);
                }
                // close connection
//                mysqli_close($conn);
            }
            return Response::json($lab_temp);
        }

//        if (count($lab_temp) > 0) {
//            $response = array('status' => 'success', 'template' => $lab_temp);
//        } else
//            $response = array('status' => 'Fail', 'template' => null);
//
//        return $response;
    }

    public function getTempDesignFromI($tempId)
    {
//        if (Request::ajax()) {
        // profile database
//        $servername = "127.0.0.1";
//        $username = "root";
//        $password = "Cloud\$erver";
//        $database = "mobicloud";
//        $port = 3306;
        $lab_temp = array();
        // connection
//        $conn = new mysqli($servername, $username, $password, $database, $port);
        $temp = DB::select("SELECT labenv.template FROM labenv WHERE labenv.id =" . $tempId);
        $vis= DB::select("SELECT labenv.template FROM labenv WHERE labenv.id =" . $tempId);
//        if ($conn->connect_error) {
//            die("Connection failed: " . $conn->connect_error);
//        } else {
//            // group role
//            $sql = "Select lab_temp.temp, lab_temp.lab_vis_json FROM  lab_temp  WHERE lab_temp.id =" . $tempId;
//            $result = $conn->query($sql);
//            while ($row = $result->fetch_assoc()) {
//                $template = $row["temp"];
        $lab_temp = array("temp" => $temp, "temp_vis" => $vis);
//            }
//            // close connection
////                mysqli_close($conn);
//        }
        //return Response::json($lab_temp);
        return array('status' => 'Success', 'template' => $lab_temp);
//        }

//        if (count($lab_temp) > 0) {
//            $response = array('status' => 'success', 'template' => $lab_temp);
//        } else
//            $response = array('status' => 'Fail', 'template' => null);
//
//        return $response;
    }

    public function getOwnClass()
    {
        if (Request::ajax()) {

            // profile database
            $servername = "10.2.0.15";
            $username = "root";
            $password = 'Cloud$erver';
            $database = "edxapp";
            $port = 3306;
            $arr = array();
            // connection
            $conn = new mysqli($servername, $username, $password, $database, $port);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } else {
                // group role
                $sql = "Select student_courseaccessrole.course_id FROM  auth_user JOIN student_courseaccessrole ON auth_user.id = student_courseaccessrole.user_id  WHERE auth_user.username ='" . $this->user . "' AND student_courseaccessrole.role = 'instructor'";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    if (substr($row["course_id"],0,10)=="course-v1:"){
                        $data = explode('+', substr($row["course_id"],10));
                    }else{
                    $data = explode('/', $row["course_id"]);
                    }
                    $courseid = $data[1];
                    $nameid = $data[2];
                    $m = new MongoDB\Driver\Manager("mongodb://10.2.0.15:27017");
                    //$db = $m->edxapp;
                    //$collection = $db->modulestore;
                    $query = new MongoDB\Driver\Query(array('$and' => array(array('_id.course' => $courseid), array('_id.name' => $nameid))));
                    $cursor = $m->executeQuery('edxapp.modulestore',$query);
                    $array = ($cursor->toArray());
                    foreach ($array as $document) {
                        $document = json_decode(json_encode($document),True);
                        $coursename = $document['metadata']['display_name'];
                    }
                    $servername1 = "10.8.0.71";
                    $username1 = "root";
                    $password1 = "Cloud\$erver";
                    $database1 = "mobicloud";
                    $port1 = 3306;
                    $arr1 = array();
                    // connection
                    $conn1 = new mysqli($servername1, $username1, $password1, $database1, $port1);
                    if ($conn1->connect_error) {
                        die("Connection failed: " . $conn1->connect_error);
                    } else {
                        // group role
                        $sql1 = "Select openlab_temp.temp_id, openlab_temp.id FROM openlab_temp  WHERE lab_id ='" . $courseid . "' AND term_id='" . $nameid . "'";
                        $result1 = $conn1->query($sql1);
                        if ($result1->num_rows == 0) {
                            $temp = 'No Template assigned yet';
                            $labid = '0';
                        } else {
                            while ($row1 = $result1->fetch_assoc()) {
                                $temp = $row1["temp_id"];
                                $labid = $row1["id"];

                            }
                        }

                        // close connection
//                mysqli_close($conn);
                    }
                    $coursedata = array("coursename" => $coursename, "courseid" => $courseid, "nameid" => $nameid, "urlid" => $row["course_id"], "tempid" => $temp, "labid" => $labid);

                    array_push($arr, $coursedata);
                }

                // close connection
//                mysqli_close($conn);
            }
            return Response::json($arr);
        }
    }

    public function getLabDesign()
    {
        if (Request::ajax()) {

            $input = Input::all();

            // profile database
            $servername = "10.8.0.71";
            $username = "root";
            $password = "Cloud\$erver";
            $database = "mobicloud";
            $port = 3306;
            // return json
            $arr = array();

            // connection
            $conn = new mysqli($servername, $username, $password, $database, $port);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } else {
                // group role
                $sql = "SELECT openlab_temp.id, openlab_temp.lab_name, openlab_temp.lab_id, openlab_temp.term_id, openlab_temp.temp_id, openlab_temp.lab_desc FROM openlab_temp  WHERE openlab_temp.useremail = '" . $this->user . "'";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    if($row["temp_id"]!='0') {
                        $sql1 = "SELECT  lab_temp.name FROM lab_temp  WHERE lab_temp.id = '" . $row["temp_id"] . "'";
                        $result1 = $conn->query($sql1);
                        $row1 = $result1->fetch_assoc();
                    }else {
                        $row1["name"]="";
                    }

                    $lab = array("lab_id" => $row["id"], "lab_name" => $row["lab_name"], "temp_id" => $row["temp_id"], "temp_name" => $row1["name"], "content_id" => $row["lab_id"], "term_id" => $row["term_id"], "description" => $row["lab_desc"]);
                    array_push($arr, $lab);

                }

                // close connection
//                mysqli_close($conn);
            }



        return Response::json($arr);
    }
}

    public function getDesign($tempId)
    {
            if($tempId==0){

            }

            // profile database
            $servername = "10.8.0.71";
            $username = "root";
            $password = "Cloud\$erver";
            $database = "mobicloud";
            $port = 3306;
            $arr = array();
            // connection
            $conn = new mysqli($servername, $username, $password, $database, $port);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } else {
                // group role
                $sql = "Select lab_temp.temp, lab_temp.lab_vis_json FROM  lab_temp  WHERE lab_temp.id =" . $tempId;
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    $temp = array("temp" => $row["temp"], "temp_vis" => $row["lab_vis_json"]);
                    array_push($arr, $temp);
                }

                // close connection
//                mysqli_close($conn);
            }
            return $arr;

    }

    public function getSharedMembers($tempId)
    {
        if (Request::ajax()) {

            // profile database
            $servername = "10.8.0.71";
            $username = "root";
            $password = "Cloud\$erver";
            $database = "mobicloud";
            $port = 3306;
            $users_str = "";
            // connection
            $conn = new mysqli($servername, $username, $password, $database, $port);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } else {
                // group role
                $sql = "Select users.email FROM user_temp JOIN users ON user_temp.userid = users.id WHERE user_temp.tempid=" . $tempId ;
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    if ($this->user != $row["email"]) {
                        $useremail = $row["email"];
                        $users_str .= $useremail . "<br />";
                    }
                }

                // close connection
//                mysqli_close($conn);
            }
            if ($users_str == "") {
                return Response::json("Didn't Shared");
            } else {
                return Response::json($users_str);
            }
        }
    }
    public function searchAllUser()
    {
        if (Request::ajax()) {
            $input = Input::all();

            // profile database
            $servername = "10.8.0.71";
            $username = "root";
            $password = "Cloud\$erver";
            $database = "mobicloud";
            $port = 3306;
            // return json
            $arr = array();
            $arr["users"] = array();
            // connection
            $conn = new mysqli($servername, $username, $password, $database, $port);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } else {
                // group role
                $sql = "SELECT users.email, users.institute, users.org_id FROM users " .
//                    "JOIN tmp_users_groups ON groups.id=tmp_users_groups.group_id " .
//                    "JOIN users ON groups.owner_id=users.id " .
                    "WHERE users.email LIKE '%" . $input['search_user_txt'] . "%' AND users.email NOT IN(SELECT users.email FROM users JOIN user_temp ON users.id = user_temp.userid WHERE tempid ='" . $input['temp_id'] . "')";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    $oneuser = array("email" => $row["email"], "institute" => $row["institute"], "org_id" => $row["org_id"]);
                    array_push($arr["users"], $oneuser);
                }

                // close connection
//                mysqli_close($conn);
            }

            return Response::json($arr);
        }
    }
}