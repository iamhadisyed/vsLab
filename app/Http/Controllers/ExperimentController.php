<?php
namespace App\Http\Controllers;
use View;
use OpenCloud\OpenStack;
use OpenCloud\Compute\Constants\Network;
use OpenCloud\Compute\Constants\ServerState;
use Redirect;
use GuzzleHttp\Exception\BadResponseException;

class ExperimentController extends Controller {

    protected $user;
    protected $password;
    protected $openstack;
    protected $identity;
    protected $isUserEnabled;
    protected $openstackres;

    public function __construct($tenantname = null)
    {
        $data = null;
        if (\Cas::isAuthenticated()) {
            $this->user = \Cas::getCurrentUser();

            // connect
            $ds = ldap_connect("192.168.2.226", 10389) or die("Could not connect to LDAP server.");
            ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

            if ($ds) {

                ldap_bind($ds, "cn=admin,dc=vlab,dc=asu,dc=edu", "CloudServer");

                $result = ldap_search($ds, "ou=Users,dc=vlab,dc=asu,dc=edu", "(mail=$this->user)") or die ("Error in search query: " . ldap_error($ds));
                $data = ldap_get_entries($ds, $result);
            }
            ldap_close($ds);
            $this->password = $data[0]["userpassword"][0];

            $this->openstackres = App::make('OpenStackResource');
            $this->isUserEnabled = $this->openstackres->isUserEnabled($this->user);

            if (!$this->isUserEnabled) {
                $this->isUserEnabled = $this->openstackres->enableUser($this->user);
                $this->openstackres->setdummyproject($this->user);
            }
            $tenant = (isset($tenantname)) ? $tenantname : 'dummy';
            $this->openstack = new OpenStack('http://192.168.2.7:5000/v2.0/', array(
                'username' => $this->user,
                'password' => $this->password,
                'tenantName' => $tenant
            ));
        } else {
            return Redirect::to('/');
        }
    }

    public function getTenants($tenantname=null) {
        if ($this->isUserEnabled) {
            $op = new OpenStack('http://192.168.2.7:5000/v2.0/', array(
                'username' => $this->user,
                'password' => $this->password,
                'tenantName' => 'dummy'
            ));
            $tenants = $op->identityService()->getTenants();
            //$tenants = $this->identity->getTenants();
            $tenant_list[''] = 'Select a Project';
            foreach ($tenants as $tenant) {
                if ($tenantname and ($tenantname == $tenant->getName())) {
                    return $tenant;
                }
                if ($tenant->getName() !== 'dummy') {
                    $tenant_list[$tenant->getName()] = $tenant->getName();
                }
            }
            return $tenant_list;
        }
    }

    public function getProjects() {
        if (Request::ajax()) {
            if ($this->isUserEnabled) {
                $op = new OpenStack('http://192.168.2.7:5000/v2.0/', array(
                    'username' => $this->user,
                    'password' => $this->password,
                    'tenantName' => 'dummy'
                ));
                $tenants = $op->identityService()->getTenants();
                //$tenants = $this->identity->getTenants();
                $tenant_list = array();
                foreach ($tenants as $tenant) {
                    if ($tenant->getName() !== 'dummy') {
                        array_push($tenant_list, array(
                                                'name' => $tenant->getName(),
                                                'description' => $tenant->getDescription(),
                                                'id' => $tenant->getId()
                                                ));
                    }
                }
                $ret = Response::json($tenant_list);
                return $ret;
            }
        }
    }

    public function getServers($tenantname)
    {
        if (Request::ajax()) {
            $op = new OpenStack('http://192.168.2.7:35357/v2.0/', array(
                'username' => $this->user,
                'password' => $this->password,
                'tenantName' => $tenantname
            ));

            $service = $op->computeService('nova', 'regionOne');
            $servers = $service->serverList(true);

            $server_list = array();
            foreach ($servers as $server) {
                $server->image->name = $this->getImage($tenantname, $server->image->id)["name"];
                $f = $this->getFlavor($tenantname, $server->flavor->id);
                $server->flavor->detail = $f->name . ", vcpus:" . $f->vcpus . ", disk:" . $f->disk . "GB, ram:" . $f->ram . "MB";
                array_push($server_list, $server);
            }
            return Response::json($server_list);
        }
    }

    public function getConsole($tenantname, $serverId)
    {
        if (\Cas::isAuthenticated()) {
            $op = new OpenStack('http://192.168.2.7:35357/v2.0/', array(
                'username' => $this->user,
                'password' => $this->password,
                'tenantName' => $tenantname
            ));
            $service = $op->computeService('nova', 'regionOne');
            $server = $service->server($serverId);
            //$console_url = $server->console('spice-html5');
            $console_url = $server->console('novnc');
            return Redirect::to($console_url->url);
        } else {
            return Redirect::to('/');
        }
    }


    public function getImages($tenantname) {
        if (Request::ajax()) {
            $op = new OpenStack('http://192.168.2.7:5000/v2.0/', array(
                'username' => $this->user,
                'password' => $this->password,
                'tenantName' => $tenantname
            ));
            $service = $op->computeService('nova', 'regionOne');
            $images = $service->imageList();
            $image_list = array(); //[''] = 'Select a Image';
            foreach ($images as $image) {
                $image_list[$image->id] = $image->name;
            }
            return Response::json($image_list);
        }
    }

    public function getImage($tenantname, $imageId) {
//        if (Request::ajax()) {
            $op = new OpenStack('http://192.168.2.7:5000/v2.0/', array(
                'username' => $this->user,
                'password' => $this->password,
                'tenantName' => $tenantname
            ));
            $service = $op->imageService('glance', 'regionOne');
            $image = $service->getImage($imageId);
//            return Response::json($image);
            return $image;
//        }
    }

    public function getFlavors($tenantname) {
        if (Request::ajax()) {
            $op = new OpenStack('http://192.168.2.7:5000/v2.0/', array(
                'username' => $this->user,
                'password' => $this->password,
                'tenantName' => $tenantname
            ));
            $service = $op->computeService('nova', 'regionOne');
            $flavors = $service->flavorList();
            $flavor_list = array(); //[''] = 'Select a flavor';
            foreach ($flavors as $flavor) {
                $flavor_list[$flavor->id] = $flavor->name;
            }
            return Response::json($flavor_list);
        }
    }

    public function getFlavor($tenantname, $flavorId) {
//        if (Request::ajax()) {
            $op = new OpenStack('http://192.168.2.7:5000/v2.0/', array(
                'username' => $this->user,
                'password' => $this->password,
                'tenantName' => $tenantname
            ));
            $service = $op->computeService('nova', 'regionOne');
            $flavor = $service->flavor($flavorId);
            return $flavor;
//            return Response::json($flavor);
//        }
    }

    public function getNetworks($tenantname) {
        if (Request::ajax()) {
            $op = new OpenStack('http://192.168.2.7:5000/v2.0/', array(
                'username' => $this->user,
                'password' => $this->password,
                'tenantName' => $tenantname
            ));
            $service = $op->networkingService('quantum', 'regionOne');
            $networks = $service->listNetworks();
            $network_list = array();
            foreach ($networks as $network) {
                $network_list[$network->getId()] = $network->getName();
            }
            return Response::json($network_list);
        }
    }

    public function createNetwork() {
        if (Request::ajax()) {
            if (Session::token() !== Input::get('_token')) {
                return Response::json(array('msg' => 'Unauthorized attempt to create network.'));
            }
            $tenant = Input::get('project_name');
            $network_name = Input::get('network_name');
            $subnet_name = Input::get('subnet_name');
            $network_address = Input::get('network_address');

            $op = new OpenStack('http://192.168.2.7:35357/v2.0/', array(
                'username' => $this->user,
                'password' => $this->password,
                'tenantName' => $tenant
            ));

            $network_service = $op->networkingService('quantum', 'regionOne');

            try {
                $network = $network_service->createNetwork(array(
                    'name' => $network_name,
                    'admin_state_up' => true
                ));
                $subnet = $network_service->createSubnet(array(
                    'name'      => $subnet_name,
                    'networkId' => $network,
                    'ipVersion' => 4,
                    'cidr'      => $network_address
                ));
                $response = array('status' => 'Success', 'msg' => 'Network creation request submitted.');
            } catch (BadResponseException $e) {
                $responseBody = (string) $e->getResponse()->getBody();
                $statusCode   = $e->getResponse()->getStatusCode();
                $headers      = $e->getResponse()->getHeaderLines();
                $response = array('status' => $statusCode, 'msg' => $responseBody);
            }
            return Response::json($response);
        }
    }

    public function createVM() {
        if (Request::ajax()) {
            if (Session::token() !== Input::get('_token')) {
                return Response::json(array('msg' => 'Unauthorized attempt to create instance.'));
            }
            $tenant = Input::get('project_name');
            $vm_name = Input::get('vm_name');
            $image_id = Input::get('image');
            $flavor_id = Input::get('flavor');
            $network_ids = Input::get('network');

            $op = new OpenStack('http://192.168.2.7:35357/v2.0/', array(
                'username' => $this->user,
                'password' => $this->password,
                'tenantName' => $tenant
            ));
            $service = $op->computeService('nova', 'regionOne');
            $network_service = $op->networkingService('quantum', 'regionOne');
            $vm = $service->server();
            //$image = $service->image($image_id);
            //$flavor = $service->flavor($flavor_id);
            $networks = array();
            foreach ($network_ids as $network_id) {
                array_push($networks, $network_service->getNetwork($network_id));
            }

            try {
                $vm->create(array(
                    'name' => $vm_name,
                    'flavorId' => $flavor_id,
                    'imageId' => $image_id,
                    'networks' => $networks
                ));

//                $callback = function($vm) {
//                    if (!empty($vm->error)) {
//                        return array('status' => 'Fail', 'msg' => var_dump($vm->error));
//                    } else {
//                        return array('status' => 'Success',
//                                                'msg' => sprintf("Waiting on %s/%-12s %4s%%",
//                                                $vm->name(), $vm->status(),
//                                                isset($vm->progress) ? $vm->progress : 0
//                                                ));
//                    }
//                };
//                $vm->waitFor(ServerState::ACTIVE, 600, $callback);
                $response = array('status' => 'Success',
                            'msg' => 'VM creation request submitted. Please wait for 5 minutes to use the VM.');
            } catch (BadResponseException $e) {
                $responseBody = (string) $e->getResponse()->getBody();
                $statusCode   = $e->getResponse()->getStatusCode();
                $headers      = $e->getResponse()->getHeaderLines();
                $response = array('status' => $statusCode, 'msg' => $responseBody);
            }
            return Response::json($response);
        }
    }

    public function createProject() {
        //if (Request::ajax()) {
            if (Session::token() !== Input::get('_token')) {
                return Response::json(array('msg' => 'Unauthorized attempt to create project.'));
            }
            $project_name = Input::get('project_name');
            $project_desc = Input::get('project_desc');
            if ($this->openstackres->createProject($this->user, $project_name, $project_desc)) {
                $response = array('status' => 'Success', 'msg' => 'Project created successfully.');
            }
            else {
                $response = array(
                    'status' => 'Fail',
                    'msg' => 'Project Created Failed due to the project name duplicated or system resource unavailable.'
                );
            }
            return Response::json($response);
        //}
    }

	public function getResource() {

        if ($this->isUserEnabled) {
            $tenant_list = $this->getTenants();
            //$image_list = $this->getImages();
            //$flavor_list = $this->getFlavors();
            //$network_list = $this->getNetworks();

            return View::make('experiment', array(
                'isUserEnabled' => true,
                'tenant_list' => $tenant_list,
                //'image_list' => $image_list,
                //'flavor_list' => $flavor_list,
                //'network_list' => $network_list
            ));
        } else {
            return View::make('experiment', array(
                'isUserEnabled' => false,
                'tenant_list' => null,
                //'image_list' => null,
                //'flavor_list' => null,
                //'network_list' => null
            ));
        }
	}

    public function checkIsUserEnabled() {
        return $this->isUserEnabled;
    }
}
