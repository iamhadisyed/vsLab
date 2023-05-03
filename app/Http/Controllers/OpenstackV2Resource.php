<?php
namespace App\Http\Controllers;
use OpenCloud\OpenStack;
use App;
use Xavrsl\Cas\Facades\Cas;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Exception\BadResponseException;

class OpenstackV2Resource extends Controller {

    protected $user;
    protected $openstack;
    protected $identity;
    protected $op_userid;
    protected $openstackres;

    public function __construct() {
        $this->openstack = new OpenStack('http://172.29.236.11:35357/v2.0/', array(
            'username' => 'admin',
            'password' => 'admin_pass',
            'tenantName' => 'admin'
        ));
        $this->identity = $this->openstack->identityService();
        $this->openstackres = App::make('App\Http\Controllers\OpenStackResource');
        $this->user = Cas::getCurrentUser();
        $this->op_userid = $this->openstackres->getUserId($this->user);
    }

    public function createProjectV2($username, $proj_name, $proj_desc) {
        $array = array(
            'name'          => $proj_name,
            'description'   => $proj_desc,
            'enabled'       => true
        );
//        if ($this->identity->chkTenantByName($proj_name) !== null) {
//            return false;
//        }
        $tenant = $this->identity->createTenant($array);
        $tenant->setMember('8baa72a1d4a2fc4254fdb47417b395ab525dc07d04396af3de9aa5d5ea083dd3','8a3a678b03704170badfa75379427243');
        //$tenant->setMember('8b97ab8ad61f40a5b59b2549e707792d', '621528ff366a47eb8998ce73ec301c58');
        $tenant->setMember($this->op_userid, '9fe2ff9ee4384b1894a90878d3e92bab');
        return $tenant;
    }



    public function addTenantMemberV2($tenant, $username) {
        $project = $this->getTenantV2($tenant);
        $userid=$this->openstackres->getUserId($username);
        $roles = $this->identity->getTenantUserRole($project->getId(), $userid);
        if (!$roles) {
            $project->setMember($userid, '9fe2ff9ee4384b1894a90878d3e92bab');
        }
        return true;
    }


    public function getTenantV2($tenant)
    {
        $this->openstack = new OpenStack('http://172.29.236.11:35357/v2.0/', array(
            'username' => 'admin',
            'password' => 'admin_pass',
            'tenantName' => $tenant
        ));
        $this->identity = $this->openstack->identityService();
        return $this->openstack->getTenantObject();
    }

    public function deleteProjectV2($tenant)
    {
        //$proj_id = $this->getTenant($tenant)->getId();
        try {
            $proj_id = $this->identity->chkTenantByName($tenant);
            $this->identity->deleteTenant($proj_id);
            return true;
        } catch (BadResponseException $e) {
            if ($e->getResponse()->getStatusCode() == '404') return true;
            else return false;}
    }

    public function getTenantUsersV2($tenantId)
    {
        $users = $this->identity->getTenantUsers($tenantId);
        return $users;
    }

//    public function getTenants($username, $tenantname=null) {
//
//            $tenants = $this->identity->getTenants();
//            $tenant_list[''] = 'Select a Project';
//            foreach ($tenants as $tenant) {
//                if ($tenantname and ($tenantname == $tenant->getName())) {
//                    return $tenant;
//                }
//                $op = new OpenStack('http://192.168.2.7:35357/v2.0/', array(
//                    'username' => 'admin',
//                    'password' => 'admin_pass',
//                    'tenantName' => $tenant->getName()
//                ));
//
//                //$this->openstack->setTenant($tenant->getId());
//                $users = $op->identityService()->getUsers();
//
//                $tenant_list[$tenant->getName()] = $tenant->getName();
//            }
//            return $tenant_list;
//    }
}