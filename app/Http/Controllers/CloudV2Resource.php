<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/22/15
 * Time: 10:13 AM
 */

namespace App\Http\Controllers;

use App;
use Redirect, Response, Request, mysqli, Input, DB;
use OpenCloud\OpenStack;
use Regulus\ActivityLog\Models\Activity;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientErrorResponseException;

use OpenCloud\Common\Exceptions\InvalidTemplateError;



class CloudV2Resource extends Controller
{

    protected $user;
    protected $password;
    protected $openstack;
    protected $identity;
    protected $isUserEnabled;
    protected $rolePerm;
    protected $openstackres;
    protected $allow_create_project = array("admin", "jachung@hotmail.com", "dijiang@asu.edu", "achaud16@asu.edu", "bingli5@asu.edu", "cchung20@asu.edu", "ydeng19@asu.edu", "huijunwu@asu.edu");
    protected $suspendable_group_id = array(121, 139);

    public function __construct($tenantname = null)
    {
        $data = null;
        if (\Cas::isAuthenticated()) {
            $this->user = \Cas::getCurrentUser();

            $this->rolePerm = App::make('App\Http\Controllers\RolePermission');

            // connect
            $ds = ldap_connect("10.2.11.94", 389) or die("Could not connect to LDAP server.");
            ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

            if ($ds) {

                ldap_bind($ds, "cn=admin,dc=vlab,dc=asu,dc=edu", "CloudServer");

                $result = ldap_search($ds, "ou=Users,dc=vlab,dc=asu,dc=edu", "(mail=$this->user)") or die ("Error in search query: " . ldap_error($ds));
                $data = ldap_get_entries($ds, $result);
            }
            ldap_close($ds);
            $this->password = $data[0]["userpassword"][0];

            $this->openstackres = App::make('App\Http\Controllers\OpenstackV2Resource');


//            $tenant = (isset($tenantname)) ? $tenantname : 'dummy';
//            $this->openstack = new OpenStack('http://192.168.2.7:5000/v2.0/', array(
//                'username' => $this->user,
//                'password' => $this->password,
//                'tenantName' => $tenant
//            ));
        } else {
            return Redirect::to('/');
        }
    }






    public function createProjectV2()
    {
        if (Request::ajax()) {

//            if (Session::token() !== Input::get('_token')) {
//                return Response::json(array('msg' => 'Unauthorized attempt to create project.'));
//            }
            //if (!in_array($this->user, $this->allow_create_project)) {
            $temp_id = Input::get('temp_id');
            $servername = "10.8.0.71";
            $username = "root";
            $password = "Cloud\$erver";
            $database = "mobicloud";
            $port = 3306;
            $verified = 0;
            $arr = array();
            // connection
            $conn = new mysqli($servername, $username, $password, $database, $port);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } else {
                // group role
                $sql = "Select lab_temp.verified FROM  lab_temp  WHERE lab_temp.id =" . $temp_id;
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    $verified = $row["verified"];

                }

                // close connection
//                mysqli_close($conn);
            }
            if($verified == 0){
                $response = array(
                    'status' => 'Fail',
                    'msg' => 'Can\'t deploy since your Template hasn\'t been verified yet, please verify it first in edit mode!' ,
                    'tenant' => null
                );
                Activity::log(['contentType' => 'Cloud',
                    'action' => 'Failed',
                    'description' => 'Deploy an unverified template.',
                    'details' => 'User ' . \Cas::getCurrentUser() . ' tempt to deploy an unverified template ' . $temp_id
                ]);
                return Response::json($response);
            }
//            elseif (!$this->rolePerm->is_deploy()) {
//                $response = array(
//                    'status' => 'Fail',
//                    'msg' => 'You don\'t have privilege to create project. Please contact contact@athenets.com to get trial permission.',
//                    'tenant' => null
//                );
//                Activity::log(['contentType' => 'Cloud',
//                    'action' => 'Failed',
//                    'description' => 'Create a project without privilege.',
//                    'details' => 'User ' . \Cas::getCurrentUser() . ' tempt to create a project without sufficient privilege.'
//                ]);
//                return Response::json($response);
//            }

            $project_name = Input::get('project_name');
            $project_desc = Input::get('project_desc');
            try {
                $tenant = $this->openstackres->createProjectV2($this->user, $project_name, $project_desc);
                $response = array(
                    'status' => 'Success',
                    'msg' => 'Project has been successfully created.',
                    'tenant' => array(
                        'name' => $tenant->getName(),
                        'description' => $tenant->getDescription(),
                        'id' => $tenant->getId())
                );
                $sql = "INSERT INTO project_uuid (project_name, uuid) VALUES ('" . $tenant->getName() . "', '" . $tenant->getId() . "')";
                //$result = $conn->query($sql);
                DB::insert($sql);
                return Response::json($response);
            } catch (BadResponseException $e) {
                $response = array(
                    'status' => 'Fail',
                    'msg' => 'Project Created Failed!! \n' .
                        'This is due to the project name duplicated or the system resource unavailable.\n' .
                        'Please try again.',
                    'tenant' => null
                );
                return Response::json($response);
            }
        }
    }

    public function createProjectFromIV2($temp_id, $project_name, $project_desc)
    {

        try {
            $tenant = $this->openstackres->createProjectV2($this->user, $project_name, $project_desc);
            $response = array(
                'status' => 'Success',
                'msg' => 'Project has been successfully created.',
                'tenant' => array(
                    'name' => $tenant->getName(),
                    'description' => $tenant->getDescription(),
                    'id' => $tenant->getId())
            );
            $sql = "INSERT INTO project_uuid (project_name, uuid) VALUES ('" . $tenant->getName() . "', '" . $tenant->getId() . "')";
            //$result = $conn->query($sql);
            DB::insert($sql);
            return $response;
        } catch (BadResponseException $e) {
            $response = array(
                'status' => 'Fail',
                'msg' => 'Project Created Failed!! \n' .
                    'This is due to the project name duplicated or the system resource unavailable.\n' .
                    'Please try again.',
                'tenant' => null
            );
            return $response;
        }
    }



    public function deleteProjectV2($tenant)
    {
        try {
            $this->openstackres->deleteProjectV2($tenant);
            return true;
        } catch (ClientErrorResponseException $e) {
            if ($e->getResponse()->getStatusCode() == '404') return true;
            else return false;
        }
    }


    public function verifyHeatTemplateV2()
    {
        if (Request::ajax()) {
            $template = Input::get('template');

            $op = new OpenStack('http://10.2.1.1:5000/v2.0/', array(
                'username' => $this->user,
                'password' => $this->password,
                'tenantName' => 'dummy',
            ));
            $orchestrationService = $op->orchestrationService('heat', 'RegionOne');

            try {
                $orchestrationService->validateTemplate(array(
//                    'template' => file_get_contents(__DIR__, $filename)
                    'template' => $template
                ));
                $response = array(
                    'status' => 'Success',
                    'msg' => 'Template has been successfully verified.');
            } catch (InvalidTemplateError $e) {
                $response = array(
                    'status' => 'Failed',
                    'msg' => $e->getMessage());
            }
            //if($response.status=="Success"){
            $input = Input::all();

            // profile database
            $servername = "10.2.255.50";
            $username = "root";
            $password = "Cloud\$erver";
            $database = "mobicloud";
            $port = 3307;
            // connection
            $conn = new mysqli($servername, $username, $password, $database, $port);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } else {
                // group role
                $sql = "UPDATE temp SET ".
                    "temp.verified=1 ".
                    "WHERE temp.id='" . $input['temp_id'] . "'";
                $result = $conn->query($sql);
                if ($result==TRUE){
                    //echo "update success";
                } else {
                    //echo "update failure : " . $conn->error;
                }

                // close connection
//                mysqli_close($conn);
            }
            //}
            return Response::json($response);
        }
    }

    public function createStackFromIV2($stackname, $tenant, $template)
    {
        $response = array('status' => 'Fail', 'message' =>'User doesn\'t have privilege to deploy template');
        //if ($this->rolePerm->is_deploy()) {
        $op = new OpenStack('http://172.29.236.11:35357/v2.0/', array(
            'username' => $this->user,
            'password' => $this->password,
            'tenantName' => $tenant,
        ));
        $orchestrationService = $op->orchestrationService('heat', 'RegionOne');

        try {
            $stack = $orchestrationService->createStack(array(
                'name' => $stackname,
                'template' => $template,
                'parameters' => array(),
                'timeoutMins' => 60
            ));
            $response = array(
                'status' => 'Success',
                'message' => 'Stack has been successfully created.',
                'stack' => $stack
            );
//            Activity::log(['contentType' => 'Cloud',
//                'action' => 'Create Stack',
//                'description' => 'Create a cloud stack',
//                'details' => 'User ' . \Cas::getCurrentUser() . ' create a cloud stack ' . $stackname
//            ]);

//                $callback = function($stack) {
//                    if (!empty($stack->error)) {
//                        return array('status' => 'Fail', 'msg' => var_dump($vm->error));
//                    } else {
//                        return array('status' => 'Success',
//                                                'msg' => sprintf("Waiting on %s/%-12s %4s%%",
//                                                $vm->name(), $vm->status(),
//                                                isset($vm->progress) ? $vm->progress : 0
//                                                ));
//                    }
//                };
//                $stack->waitFor(stack_status::CREATE_COMPLETE, 600, $callback);
        } catch (ClientErrorResponseException $e) {
            $message = $e->getResponse();
            //$message = $e->getMessage();
            $response = array(
                'status' => 'Fail',
                'message' => $e->getMessage(), //"You don't have privilege to deploy template. Please contact contact@athenets.com to get trial permission.",
                'stack' => null
            );
//            Activity::log(['contentType' => 'Cloud',
//                'action' => 'Create Stack Failed.',
//                'description' => 'Create a cloud stack failed',
//                'details' => $message
//            ]);
        }
        //}
        return $response;
    }

    public function setProjectMemberFromIV2($team_id, $tenant_name, $groupname)
    {
        $users= DB::select('SELECT users.email FROM users_subgroups JOIN users ON users_subgroups.user_id=users.id ' .
            'WHERE users_subgroups.subgroup_id = ?', array($team_id));
        foreach ($users as $user) {
            $this->openstackres->addTenantMemberV2($tenant_name, $user->email);
        }

        $owners= DB::select('SELECT users.email FROM users_groups JOIN users ON users_groups.user_id=users.id ' .
            'JOIN groups ON users_groups.group_id=groups.id WHERE groups.class is NULL AND users_groups.role_id=15 AND groups.name = ?', array($groupname));
        foreach ($owners as $owner) {
            if ($owner->email != $this->user ) {
                $this->openstackres->addTenantMemberV2($tenant_name, $owner->email);
            }
        }
        return true;
    }

    public
    function createStackV2()
    {

        if (Request::ajax()) {
            $stackname = Input::get('stack');
            $template = Input::get('template');
            $tenant = Input::get('project');
            $desc=Input::get('project_desc');

            //if ($this->rolePerm->is_deploy()) {


//            $tenant = 'yulitest2';
            $op = new OpenStack('http://172.29.236.11:35357/v2.0/', array(
                'username' => $this->user,
                'password' => $this->password,
                'tenantName' => $tenant,
            ));
            $orchestrationService = $op->orchestrationService('heat', 'RegionOne');
            try {
                $stack = $orchestrationService->createStack(array(
                    'name' => $stackname,
                    'template' => $template,
                    'parameters' => array(),
                    'timeoutMins' => 30
                ));
                $response = array(
                    'status' => 'Success',
                    'message' => 'Stack has been successfully created.',
                    'stack' => $stack
                );
                Activity::log(['contentType' => 'Cloud',
                    'action' => 'Create Stack',
                    'description' => 'Create a cloud stack',
                    'details' => 'User ' . \Cas::getCurrentUser() . ' create a cloud stack ' . $stackname
                ]);
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
                    if ($stackname != "KeyPair") {
                        $sql = "INSERT INTO projects (name, project_desc, user_name) VALUES ( '" . $tenant . "', '" . $desc . "','" . $this->user . "')";
                        $result = $conn->query($sql);

                        if ($result == TRUE) {
                            //echo "update success";
                        } else {
                            //echo "update failure : " . $conn->error;
                        }
                    }
                    // close connection
//                mysqli_close($conn);
                }
            } catch (ClientErrorResponseException $e) {
                $message = $e->getResponse();
                //$message = $e->getMessage();
                $response = array(
                    'status' => 'Fail',
                    'message' => $e->getMessage(), //"You don't have privilege to deploy template. Please contact contact@athenets.com to get trial permission.",
                    'stack' => null
                );
                Activity::log(['contentType' => 'Cloud',
                    'action' => 'Create Stack Failed.',
                    'description' => 'Create a cloud stack failed',
                    'details' => $message
                ]);
            }
            //}
            return $response;

        }
    }

    public function deleteStackV2()
    {

        if (Request::ajax()) {
            $stackname = Input::get('stack');

            $tenant = Input::get('project');



            $op = new OpenStack('http://172.29.236.11:35357/v2.0/', array(
                'username' => $this->user,
                'password' => $this->password,
                'tenantName' => $tenant,
            ));
            $orchestrationService = $op->orchestrationService('heat', 'RegionOne');

            try {
                $stack = $orchestrationService->getStack($stackname);
                $stack->delete();
                $response = array(
                    'status' => 'Success', 'message' => 'Project has been successfully delete.');


            } catch (ClientErrorResponseException $e) {
                $response = array(
                    'status' => 'Fail', 'message' => 'Delete stack failed.');

            }
            if($response['status']=='Fail'){
                return Response::json($response);
            }
            try {
                $this->openstackres->deleteProjectV2($tenant);
                $response = array(
                    'status' => 'Success', 'message' => 'Project has been successfully delete.');

            } catch (ClientErrorResponseException $e) {
                if ($e->getResponse()->getStatusCode() == '404') {$response = array(
                    'status' => 'Success', 'message' => 'Project has been successfully delete.');
                }
                else{$response = array(
                    'status' => 'Fail', 'message' => 'Delete Project failed.');
                }
            }

            if ($response['status'] == 'Success'){
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

                    $sql = "DELETE FROM projects WHERE name = '".$tenant."' AND user_name='".$this->user."'";
                    $result = $conn->query($sql);

                    if ($result == TRUE) {
                        //echo "update success";
                    } else {
                        //echo "update failure : " . $conn->error;
                    }

                }
            }
            return Response::json($response);

        }
    }

    public function deleteStackFromIV2($stackname, $tenant)
    {
//        $stackname = Input::get('stack');
//        $tenant = Input::get('project');

        $op = new OpenStack('http://172.29.236.11:35357/v2.0/', array(
            'username' => $this->user,
            'password' => $this->password,
            'tenantName' => $tenant,
        ));
        $orchestrationService = $op->orchestrationService('heat', 'RegionOne');

        try {
            $stack = $orchestrationService->getStack($stackname);
            $stack->delete();
            $response = array(
                'status' => 'Success', 'message' => 'Stack has been successfully delete.');

            Activity::log(['contentType' => 'Cloud',
                'action' => 'Delete Stack',
                'description' => 'Delete a cloud stack',
                'details' => 'User ' . \Cas::getCurrentUser() . ' delete a cloud stack ' . $stackname
            ]);
        }
        catch (ClientErrorResponseException $e) {
            $response = array(
                'status' => 'Fail', 'message' => 'Delete stack failed.');

            Activity::log(['contentType' => 'Cloud',
                'action' => 'Failed',
                'description' => 'Delete a cloud stack failed',
                'details' => $e->getResponse()
            ]);
        }
        return $response;
    }

    public function isExistStackV2($stackname, $tenant)
    {
        $op = new OpenStack('http://172.29.236.11:35357/v2.0/', array(
            'username' => $this->user,
            'password' => $this->password,
            'tenantName' => $tenant,
        ));
        try {
            $orchestrationService = $op->orchestrationService('heat', 'RegionOne');
            return $orchestrationService->getStack($stackname);
        }
        catch (\Guzzle\Http\Exception\BadResponseException $e) {
            return null;
        }
    }

    public function listStacksV2()
    {
        if (Request::ajax()) {
            $op = new OpenStack('http://10.2.1.1:5000/v2.0/', array(
                'username' => $this->user,
                'password' => $this->password,
            ));
            $orchestrationService = $op->orchestrationService(null, 'RegionOne');
            $stacks = $orchestrationService->listStacks();
            $stack_list = array();
            foreach ($stacks as $stack) {
                // process each $stack
            }
            return Response::json($stack_list);
        }
    }

    public function getStackStatusV2($stackname, $tenant)
    {
        $op = new OpenStack('http://172.29.236.11:35357/v2.0/', array(
            'username' => $this->user,
            'password' => $this->password,
            'tenantName' => $tenant,
        ));
        $orchestrationService = $op->orchestrationService('heat', 'RegionOne');
        $stack = $orchestrationService->getStack($stackname);
        return $stack->getStatus();
    }

    public function checkStackEventsV2()
    {
        if (Request::ajax()) {
            $tenant = Input::get('project_name');
            $stackname = "s-".$tenant;

            $client = new OpenStack('http://172.29.236.11:35357/v2.0/', array(
                'username' => $this->user,
                'password' => $this->password,
                'tenantName' => $tenant,
            ));
            try {
// 2. Obtain an Orchestration service object from the client.
                $orchestrationService = $client->orchestrationService('heat', 'RegionOne');

// 3. Get stack.
                $stack = $orchestrationService->getStack($stackname);

// 4. Get list of events for the stack.
                $stackEvents = $stack->listEvents();
                $i=0;
                foreach ($stackEvents as $stackEvent) {

                    $event=$stackEvent->getEvent();
                    $events['event'.$i]=$event;
                    $i++;
                }
                $events['size']=$i;

                return Response::json($events);
            }
            catch (ClientErrorResponseException $e) {
                $response = array('status' => 'Stack not found!');
                return Response::json($response);
            }

        }
    }

    public function checkStackStatusV2()
    {
        if (Request::ajax()) {
            $tenant = Input::get('project_name');
            $stackname = "s-".$tenant;

            $op = new OpenStack('http://172.29.236.11:35357/v2.0/', array(
                'username' => $this->user,
                'password' => $this->password,
                'tenantName' => $tenant,
            ));
            try {
                $orchestrationService = $op->orchestrationService('heat', 'RegionOne');
                $stack = $orchestrationService->getStack($stackname);


                $response = array('status' => $stack->getStatus());
                return Response::json($response);
            }
            catch (ClientErrorResponseException $e) {
                $response = array('status' => 'Stack Deleted');
                return Response::json($response);
            }
        }
    }



}