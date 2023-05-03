<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/22/15
 * Time: 10:13 AM
 */
namespace App\Http\Controllers;

use App, DB;
use Redirect, Response;
use Carbon\Carbon;
use OpenStack\OpenStack;
use OpenCloud\OpenStack as OpenCloud;
use Illuminate\Http\Request;
use Regulus\ActivityLog\Models\Activity;
use Adldap\Laravel\Facades\Adldap;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Illuminate\Support\Facades\Auth;
use App\DeployLab;

class CloudResource extends Controller
{
    protected $user;
    protected $user_id;
    protected $op;
    protected $opV2;
    protected $project;
    protected $op_userid;
    protected $user_password;
    protected $openstack;
    protected $identity;
    protected $isUserEnabled;
    protected $openstackres;
    protected $allow_create_project = array("admin", "jachung@hotmail.com", "dijiang@asu.edu", "achaud16@asu.edu", "bingli5@asu.edu", "cchung20@asu.edu", "ydeng19@asu.edu", "huijunwu@asu.edu");
    protected $suspendable_group_id = array(121, 139);

    public function __construct(Request $request)
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                redirect('/');
            }

            $this->user = Auth::user()->email;

            $this->openstackres = App::make('App\Http\Controllers\OpenStackResource');

            //if (($this->user_password = session('op_upass')) == null) {  // not a good design
            $this->user_password = Adldap::search()->where('cn', '=', $this->user)->get()[0]->userpassword[0];
            //    session(['op_upass' => $this->user_password]);
            //}

            if (($this->op_userid = session('op_uid')) == null) {
                $this->op_userid = $this->openstackres->getUserId($this->user);
                session(['op_uid' => $this->op_userid]);
            }

            if (($this->project = $request->route()->parameter('project')) == 'dummy') {
                $this->project = config('openstack.dummy_project_id');
            } else if (!is_null($request->get('project'))) {
                $this->project = $request->get('project');
            }

            $this->op = $this->getUserOpenStack();
//            $ldap_user = Adldap::search()->where('cn', '=', $this->user)->get()->first();
//            $ldap_enable = Adldap::search()->where('cn', '=', 'enabled')->get()->first();
//            if($ldap_user->inGroup($ldap_enable)) {
//                $ldap_enable->addMember($ldap_user);
//            }


//                        $this->isUserEnabled = $this->openstackres->isUserEnabled($this->op_userid);
//                if (!$this->isUserEnabled) {
//                    $this->isUserEnabled = $this->openstackres->enableUser($this->op_userid);
//                }
            $this->openstackres->setdummyproject($this->op_userid);
            // $this->createKeyPair();  // this function need to be modified
            return $next($request);
        });
    }

    public function getUserOpenStack()
    {
        return new OpenStack([
            'authUrl'   => config('openstack.auth_url'),
            'region'    => config('openstack.region'),
            'user'      => [
                'id'        => $this->op_userid,
                'name'      => $this->user,
                'password'  => $this->user_password,
                'domain'    => ['id' => config('openstack.user_domain_id')],
            ],
            'scope'     => [
                'project' => ['id' => $this->project]
            ]
        ]);
    }

    public function getAdminOpenStack()
    {
        return new OpenStack([
            'authUrl'   => config('openstack.auth_url'),
            'region'    => config('openstack.region'),
            'user'      => [
                'id'        => $this->op_userid,
                'name'      => $this->user,
                'password'  => $this->user_password,
                'domain'    => ['id' => config('openstack.user_domain_id')],
            ],
//            'scope'     => [
//                'project' => ['id' => $this->project]
//            ]
        ]);
    }

    public function getProjectMembers(Request $request, $tenantId)
    {
        if ($request->ajax()) {
//            $op = new OpenStack('http://192.168.2.7:5000/v2.0/', array(
//                'username' => $this->user,
//                'password' => $this->password,
//                'tenantName' => 'dummy'
//            ));
//            $tenants = $op->identityService()->getTenants();
            $users = $this->openstackres->getTenantUsers($tenantId);
            $users_str = "";
            foreach ($users as $user) {
                if (isset($user->email) and ($user->email != "admin"))
                    $users_str .= $user->email . "<br />";
            }
            return Response::json($users_str);
        }
    }

//    public function setProjectMember($tenant_name, $member)
//    {
//        $op = new OpenStack('http://192.168.2.7:35357/v2.0/', array(
//            'username' => 'admin', //$this->user,
//            'password' => 'admin_pass', //$this->password,
//            'tenantName' => $tenant_name
//        ));
//        $tenant = $op->getTenantObject();
//        $tenant->setMember($member, '9fe2ff9ee4384b1894a90878d3e92bab');
//        //$users = $this->openstackres->addTenantMember($tenantId, $username);
//        return $tenant;
//    }

    public function setProjectMemberFromI($team_id, $tenant_name)
    {
        $users= DB::select('SELECT users.email FROM users_subgroups JOIN users ON users_subgroups.user_id=users.id ' .
               'WHERE users_subgroups.subgroup_id = ?', array($team_id));
        foreach ($users as $user) {
            $this->openstackres->addTenantMember($tenant_name, $user->email);
        }
        return true;
    }

    public function setProjectMember($projectId, $user) {
        $this->openstackres->addTenantMember($projectId, $user);
        return true;
    }

    public function removeProjectMember($tenant, $user)
    {
        $projectId=$this->openstackres->removeTenantMember($tenant, $user);
        return $projectId;
    }

    public function getImages()
    {
        $service = $this->op->imagesV2();
        $images = $service->listImages();
        $image_list = array();
        foreach ($images as $image) {
            if($image->name != 'Quagga-Router-Ubuntu-14.04-Server-64-150406') {
                $im = array(
                'status' => $image->status,
                'name' => $image->name,
                'container_format' => $image->containerFormat,
                'created_at' => $image->createdAt,
                'disk_format' => $image->diskFormat,
                'updated_at' => $image->updatedAt,
                'visibility' => $image->visibility,
                'min_disk' => $image->minDisk,
                'protected' => $image->protected,
                'id' => $image->id,
                'size' => $image->size,
                'min_ram' => $image->minRam,
                'owner' => $image->ownerId,
                'checksum' => $image->checksum);
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

    public function getImage($imageId)
    {
//        $op = $this->getUserOpenStack($project);
        $service = $this->op->imagesV2();
        try {
            $image = $service->getImage($imageId);
            $image->retrieve();
        } catch (BadResponseException $e) {
            return null;
        }
        return $image;
    }

    public function getFlavors()
    {
        $service = $this->op->computeV2();
        $flavors = $service->listFlavors();
        $flavor_list = array();
        foreach ($flavors as $flavor) {
            $fl = array('name' => $flavor->name, 'id' => $flavor->id,
                'vcpus' => $flavor->vcpus, 'ram' => $flavor->ram, 'disk' => $flavor->disk);
            array_push($flavor_list, $fl);
        }
//        if (Request::ajax())
//            return Response::json($flavor_list);
        return $flavor_list;
    }

    public function getFlavor($flavorId)
    {
//        $op = $this->getUserOpenStack($project);
        $service = $this->op->computeV2();
        try {
            $flavor = $service->getFlavor(['id' => $flavorId]);
            $flavor->retrieve();
        } catch (BadResponseException $e) {
            return null;
        }
        return $flavor;
    }

    public function getResources(Request $request)
    {
        if ($request->ajax()) {
            $ret = array('images' => $this->getImages(),
                'flavors' => $this->getFlavors(),
                'networks' => $this->getNetworks());
            return Response::json($ret);
        }
    }

    public function getServers()
    {
        $service = $this->op->computeV2();
        $servers = $service->listServers(true);

        $server_list = array();
        foreach ($servers as $server) {
            $image = $this->getImage($server->image->id);
            if ($image) {
                $server->image->name = $image->name;
                $server->image->os_distro = isset($image->os_distro) ? $image->os_distro : "(not defined)";
                $server->image->os_version = isset($image->os_version) ? $image->os_version : "(not defined)";
            } else {
                $server->image->name = "(not found)";
            }
            $fid = $this->getFlavor($server->flavor->id);
            $f = ($fid) ? $fid : "(not found)";
            $server->flavor->detail = $f->name . ", vcpus:" . $f->vcpus . ", disk:" . $f->disk . "GB, ram:" . $f->ram . "MB";
            array_push($server_list, $server);
        }
        return $server_list;
    }

    public function getServerList(Request $request)
    {
        if ($request->ajax()) {
            $vm_list = $this->getServers();
            return Response::json($vm_list);
        }
    }

    public function getConsole($project, $serverId)
    {
//        $op = $this->getUserOpenStack($project);
        $service = $this->op->computeV2();
        $server = $service->getServer(['id' => $serverId]);
        $console = $server->getVncConsole();

        return Redirect::to($console['url']);
    }

    public function getVM(Request $request, $project, $serverId)
    {
        if ($request->ajax()) {
//        $op = $this->getUserOpenStack($project);
            $service = $this->op->computeV2();
            $server = $service->getServer(['id' => $serverId]);
            $server->retrieve();
            $server->image = $this->getImage($server->image->id);
            return Response::json($server);
        }
    }

    public function getNetworks()
    {
//            $op = $this->getUserOpenStack($project);
        $networking = $this->op->networkingV2();
        $networks = $networking->listNetworks(['tenantId' => $this->project]);

        $network_list = array();
        foreach ($networks as $network) {
            if ($network->name == 'ext-net') continue;
            $subnet_list = array();
            foreach ($network->subnets as $subnetId) {
                // if ($subnet->networkId !== $network->id) continue;
                $subnet = $networking->getSubnet($subnetId);
                $subnet->retrieve();
                $subnet_detail = array('name' => $subnet->name, 'id' => $subnet->id,
                    'enableDhcp' => $subnet->enableDhcp, 'gatewayIp' => $subnet->gatewayIp,
                    'cidr' => $subnet->cidr);
                array_push($subnet_list, $subnet_detail);
            }
            $port_list = array();
            $ports = $networking->listPorts(['networkId' => $network->id]);
            foreach ($ports as $port) {
                if ($port->networkId !== $network->id) continue;
                $port_detail = array('name' => $port->name, 'id' => $port->id,
                    'deviceId' => $port->deviceId, 'deviceOwner' => $port->deviceOwner,
                    'fixedIps' => $port->fixedIps, 'status' => $port->status);
                array_push($port_list, $port_detail);
            }
            $net = array('name' => $network->name, 'id' => $network->id,
                'status' => $network->status, 'subnets' => $subnet_list, 'ports' => $port_list);
            array_push($network_list, $net);
        }
        return $network_list;
    }

    public function getNetworkList(Request $request)
    {
        if ($request->ajax()) {
            $network_list = $this->getNetworks();
            return Response::json($network_list);
        }
    }

    public function getNetworkTopology(Request $request)
    {
        if ($request->ajax()) {
            $netTopology = array('network_list' => $this->getNetworks(), 'vm_list' => $this->getServers());
            return Response::json($netTopology);
        }
    }

    public function vmAction(Request $request)
    {
        if ($request->ajax()) {
            $project = $request->get('project');
            $vmId = $request->get('vmId');
            $action = $request->get('action');

//            $op = $this->getUserOpenStack($project);
            $service = $this->op->computeV2();
            $vm = $service->getServer(['id' => $vmId]);
            $vm->retrieve();
            $response = null;

            switch ($action) {
                case 'Reboot':
                    $response = $vm->reboot();
//                    Activity::log(['contentType' => 'VM',
//                        'action' => 'Reboot',
//                        'description' => 'VM Reboot',
//                        'details' => 'User ' . $this->user . ' reboot VM ' . $vmId . ' in project ' . $project
//                    ]);
                    break;
                case 'Shutdown':
                    $response = $vm->stop();
//                    Activity::log(['contentType' => 'VM',
//                        'action' => 'Shutdown',
//                        'description' => 'VM Shutdown',
//                        'details' => 'User ' . $this->user . ' shutdown VM ' . $vmId . ' in project ' . $project
//                    ]);
                    break;
                case 'Start':
                    $response = $vm->start();
//                    Activity::log(['contentType' => 'VM',
//                        'action' => 'Start',
//                        'description' => 'VM Start',
//                        'details' => 'User ' . $this->user . ' start VM ' . $vmId . ' in project ' . $project
//                    ]);
                    break;
                case 'Delete':
                    $response = $vm->delete();
//                    Activity::log(['contentType' => 'VM',
//                        'action' => 'Delete',
//                        'description' => 'VM Delete',
//                        'details' => 'User ' . $this->user . ' delete VM ' . $vmId . ' in project ' . $project
//                    ]);
                    break;
                case 'Rebuild':
                    $response = $vm->rebuild(array('imageId' => $vm->image->id, 'adminPass' => config('openstack.admin_password')));
//                    Activity::log(['contentType' => 'VM',
//                        'action' => 'Rebuild',
//                        'description' => 'VM Rebuild',
//                        'details' => 'User ' . $this->user . ' rebuild VM ' . $vmId . ' in project ' . $project
//                    ]);
                    break;
                case 'Pause':
                    $response = $vm->pause();
//                    Activity::log(['contentType' => 'VM',
//                        'action' => 'Suspend',
//                        'description' => 'VM Suspend',
//                        'details' => 'User ' . $this->user . ' pause VM ' . $vmId . ' in project ' . $project
//                    ]);
                    break;
                case 'Unpause':
                    $response = $vm->unpause();
//                    Activity::log(['contentType' => 'VM',
//                        'action' => 'Suspend',
//                        'description' => 'VM Suspend',
//                        'details' => 'User ' . $this->user . ' unpause VM ' . $vmId . ' in project ' . $project
//                    ]);
                    break;
                case 'Suspend':
                    $response = $vm->suspend();
//                    Activity::log(['contentType' => 'VM',
//                        'action' => 'Suspend',
//                        'description' => 'VM Suspend',
//                        'details' => 'User ' . $this->user . ' suspend VM ' . $vmId . ' in project ' . $project
//                    ]);
                    break;
                case 'Resume':
                    $response = $vm->resume();
//                    Activity::log(['contentType' => 'VM',
//                        'action' => 'Resume',
//                        'description' => 'VM Resume',
//                        'details' => 'User ' . $this->user . ' resume VM ' . $vmId . ' in project ' . $project
//                    ]);
                    break;
                case 'Snapshot':
                    $response = $vm->createImage(['name' => $vm->name . '_snapshot_' . Carbon::now()]);
                    break;
            }
            return Response::json($response);
        }
    }


    public function deleteProject($tenant)
    {
        try {
            $this->openstackres->deleteProject($tenant);
            return true;
        } catch (ClientException $e) {
            return ($e->getResponse()->getStatusCode() == '404') ? true : false;
        }
    }

    public function checkIsUserEnabled()
    {
        return $this->isUserEnabled;
    }


    public function updateProject(Request $request)
    {
        if ($request->ajax()) {
            $project_id   = $request->get('project_id');
            $project_name = $request->get('project_name');
            $project_desc = $request->get('project_desc');

            try {
                $tenant = $this->openstackres->updateProject($project_id, $project_name, $project_desc);
                $response = array(
                    'status' => 'Success',
                    'msg' => 'Project has been successfully updated.',
                    'tenant' => array(
                        'name' => $tenant->getName(),
                        'description' => $tenant->getDescription(),
                        'id' => $tenant->getId())
                );

                Activity::log(['contentType' => 'Cloud',
                    'action' => 'Update Project',
                    'description' => 'Update a cloud project',
                    'details' => 'User ' . \Cas::getCurrentUser() . ' update project ' . $project_name
                ]);
                return Response::json($response);
            } catch (BadResponseException $e) {
                $response = array(
                    'status' => 'Fail',
                    'msg' => 'Project update Failed!! ' .
                        'This is due to the project name duplicated or the system resource unavailable. ' .
                        'Please try again.',
                    'tenant' => null
                );

                Activity::log(['contentType' => 'Cloud',
                    'action' => 'Failed',
                    'description' => 'Update cloud project failed',
                    'details' => 'User ' . \Cas::getCurrentUser() . ' update project ' . $project_name . ' failed.'
                ]);
                return Response::json($response);
            }
        }
    }





}