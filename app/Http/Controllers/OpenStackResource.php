<?php
namespace App\Http\Controllers;

//use Xavrsl\Cas\Facades\Cas;
use OpenStack\OpenStack;
use Response;
use Illuminate\Http\Request;
use Cache;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Facades\Auth;

//use Guzzle\Http\Exception\ClientErrorResponseException;

class OpenStackResource extends Controller
{
    protected $user;
    protected $openstack;
    protected $identity;
    protected $op_user_id = '';

    public function __construct() {
        //if (Cas::isAuthenticated()) {
        if (Auth::check()) {
            //$this->user = Cas::getCurrentUser();
            $this->user = Auth::user()->email;

            $this->openstack = new OpenStack(config('openstack.adminAuthOptions'));

            $this->identity = $this->openstack->identityV3();

//            $token = $this->identity->generateToken([
//                'user' => [ 'id' => config('openstack.admin_id'), 'password' => config('openstack.admin_password')]
//            ]);
//
//            $tokenObj = $this->identity->getToken($token->id);
//            $tokenObj->retrieve();
//
//            if (!$tokenObj || ($tokenObj && $tokenObj->hasExpired())) {
//                $result = $this->identity->authenticate($tokenObj);
//            }

        } else {
            redirect('/');
        }
    }

    public function checkCache($client)
    {
        if ($token = Cache::get(get_class($client).'.token')) {
            $client->imortCredentials($token);
        }

        $token = $client->getTokenObject();

        if (!$token || ($token && $token->hasExpired())) {
            $client->authenticate();
            Cache::forever(get_class($client).'.token', $client->exportCredentials);
        }
    }

    public function getAllUserList(Request $request)
    {
        if ($request->ajax()) {
            $user_list = $this->getAllUsers();
            return Response::json($user_list);
        }
    }

    public function getAllUsers()
    {
        $user_list = array();
        $users = $this->identity->listUsers([
            'domainId' => config('openstack.user_domain_id'),
            'enabled' => true
        ]);

        foreach ($users as $user) {
            if ($user->email == null) continue;
            array_push($user_list, array('id' => $user->id, 'email' => $user->email));
        }
        return $user_list;
    }

    /**
     *  getUserId from OpenStack.
     * @param $username
     * @return null if not found it, otherwise a user's id
     */
    public function getUserId($username)
    {
        if ($username == null) return null;
        $users = $this->identity->listUsers([
            'name' => $username,
            'domainId' => config('openstack.user_domain_id')
        ]);

        foreach ($users as $user) {
            if ($user->name == $username) {
                //session(['op_uid' => $user->id]);
                return $user->id;
            }
        }
        return null;     // won't happen.
    }


    public function isUserEnabled($userid)
    {
        if ($userid == null) return null;
        $user = $this->identity->getUser($userid);
        $user->retrieve();
        return $user->enabled;
    }

    public function enableUser($userid)
    {
        $user = $this->identity->getUser($userid);
        $user->enabled = true;
        $user->update();
    }

    public function setdummyproject($userid)
    {
        $project = $this->identity->getProject(config('openstack.dummy_project_id'));
        if (!$project->checkUserRole(['userId' => $userid, 'roleId' => config('user_role_id')])) {
            $project->grantUserRole([
                'userId' => $userid,
                'roleId' => config('user_role_id'),
            ]);
        }
    }

    public function getDomainName($domainId)
    {
        $domains = $this->identity->listDomains();
        $domain_list = array();
        foreach ($domains as $domain) {
            $domain_list[$domain->id] = $domain->name;
        }
        return $domain_list[$domainId];
    }

    public function getDomain(Request $request, $domainName)
    {
        $result = array();
        if ($request->ajax()) {
            if ($domainName == 'user') {
                $result = ['id' => config('openstack.user_domain_id'), 'name' => config('openstack.user_domain_name')];
            }
            return Response::JSON($result);
        }
    }

    public function getRoleId($role_name)
    {
        $roles = $this->identity->listRoles();
        foreach ($roles as $role) {
            if ($role->name == $role_name) return $role->id;
        }
        return null;
    }

    public function getProjectUserList(Request $request, $projectId)
    {
        if ($request->ajax()) {
            $user_list = $this->getProjectUsers($projectId);
            return Response::json($user_list);
        }
    }

    public function getProjectUsers($projectId)
    {
        $user_list = array();
        $roleAssigns = $this->identity->listRoleAssignments([
            'projectId' => $projectId
        ]);
        $users = array();
        foreach ($roleAssigns as $item) {
            $user = $this->identity->getUser($item->user->id);
            $user->retrieve();
            if (array_key_exists($user->id, $users)) continue;
            $users[$user->id] = $user->email;
        }

        $project = $this->identity->getProject($projectId);
        $project->retrieve();
        foreach ($users as $key => $val) {
            $roles = array();
            foreach ($project->listUserRoles(['userId' => $key]) as $role) {
                array_push($roles, array('id' => $role->id, 'name' => $role->name));
            }
            array_push($user_list, array('id' => $key, 'email' => $val, 'roles' => $roles));
        }
        return $user_list;
    }

    public function getProjectList(Request $request)
    {
        if ($request->ajax()) {
            $project_list = $this->getProjects();
            return Response::json($project_list);
        }
    }

    public function getProjects()
    {
        $project_list = array();
//        $user_id = $this->getUserId($this->user);

            $projects = $this->identity->listProjects();
            foreach ($projects as $project) {
//                if ($project->id !== config('openstack.dummy_project_id')) {
//                    $roles = array();
//                    foreach ($project->listUserRoles(['userId' => $user_id]) as $role) {
//                        array_push($roles, $role->name);
//                    };
//                    if (count($roles) <= 0) continue;
                    array_push($project_list, array(
                        'id' => $project->id,
                        'name' => $project->name,
//                        'role' => $roles,
                        'description' => $project->description,
                        'enabled' => $project->enabled,
                        'domain' => $this->getDomainName($project->domainId),
                    ));
//                }
            }
        return $project_list;
    }

    public function createProject(Request $request)
    {
//        if ($request->ajax()) {
            try {
                $project = $this->createProjectI($request->get('project'), $request->get('members'));
                if (!$project) {
                    $response = array(
                        'status' => 'Fail',
                        'message' => 'Project Created Failed!! \n' .
                            'The project name is already exist. Please enter a new name!',
                        'project' => null
                    );
                } else {
                    $response = array(
                        'status' => 'Success',
                        'message' => 'Project has been successfully created.',
                        'project' => array(
                            'name' => $project->name,
                            'description' => $project->description,
                            'id' => $project->id)
                    );
                }
                return Response::json($response);
            } catch (BadResponseException $e) {
                $response = array(
                    'status' => 'Fail',
                    'message' => 'Project Created Failed!! \n' .
                        'This is due to the project name duplicated or the system resource unavailable.\n' .
                        'Please try again.',
                    'project' => null
                );
                return Response::json($response);
            }
//        }
    }

    public function grantRoleToProjectUser($project_id, $user, $role_id)
    {
        $user_id = $this->getUserId($user);
        $project = $this->identity->getProject($project_id);
        $project->grantUserRole([
            'userId' => $user_id,
            'roleId' => $role_id,
        ]);
    }

    public function createProjectI($project, $members)
    {
        $options = array(
            'name'          => $project['name'],
            'description'   => $project['desc'],
            'domain'        => config('openstack.user_domain_id'),
            'enabled'       => true
        );

        if (!$this->isProjectExist($project['name'])) {
            $tenant = $this->identity->createProject($options);
            if ($tenant && count($members) > 1) {
                foreach ($members as $member) {
                    $user_id = $this->getUserId($member);
                    $tenant->grantUserRole([
                        'userId' => $user_id,
                        'roleId' => config('openstack.user_role_id')
                    ]);
                }
            }
            return $tenant;
        }
        return false;
    }

    public function createProjectforLabTestEnv($project, $member)
    {
        $options = array(
            'name'          => $project['name'],
            'description'   => $project['desc'],
            'domain'        => config('openstack.user_domain_id'),
            'enabled'       => true
        );

        if (!$this->isProjectExist($project['name'])) {
            $tenant = $this->identity->createProject($options);
            if ($tenant) {

                    $user_id = $this->getUserId($member);
                    $tenant->grantUserRole([
                        'userId' => $user_id,
                        'roleId' => config('openstack.user_role_id')
                    ]);

            }
            return $tenant;
        }
        return false;
    }

    public function isProjectExist($proj_name)
    {
        $options = array(
            'name'      => $proj_name,
            'domainId'  => config('openstack.user_domain_id'),
            'enabled'   => true
        );
        $projects = $this->identity->listProjects($options);
        foreach ($projects as $project) {
            if ($project->name == $proj_name)
                return true;
        }
        return false;
    }

    public function updateProject(Request $request)
    {
        $project = $request->get('project');
        $members = $request->get('members');
        $cur_proj = $this->identity->getProject($project['id']);
        $cur_proj->retrieve();

        if (!$cur_proj) {
            return Response::JSON(['status' => 'Failed', 'Message' => 'The requested project is not existed!']);
        }

        if (($cur_proj->name != $project['name']) and $this->isProjectExist($project['name'])) {
            return Response::JSON(['status' => 'Failed', 'Message' => 'The project name has been taken!']);
        }

        $cur_proj->name = $project['name'];
        $cur_proj->description = $project['desc'];
        $cur_proj->enabled = ($project['enabled'] == 'true') ? true : false;

        $cur_proj->update();

        if (count($members) > 0) {
            $member_roles = array();
            foreach ($members as $member) {
                $roles = explode(',', $member['roles']);
                foreach ($roles as $role) {
                    array_push($member_roles, ['userId' => $member['id'], 'roleId' => $this->getRoleId($role)]);
                }
            }
            $this->updateProjectMemberI($project['id'], $member_roles);
        }

        return Response::JSON(['status' => 'Success', 'message' => 'The project\'s information and members have been updated.']);
    }

    public function deleteProject(Request $request)
    {
        if ($request->ajax()) {
            $projectId = $request->get('projectId');
            $result = $this->deleteProjectI($projectId);

            if ($result) {
                $response = array(
                    'status' => 'Success',
                    'message' => 'Project has been successfully deleted.'
                );
            } else {
                $response = array(
                    'status' => 'Fail',
                    'message' => 'Delete Project Failed!! \n' .
                        'This is due to the project not exist or the system resource unavailable.'
                );
            }
            return Response::json($response);
        }
    }

    public function deleteProjectI($projectId)
    {
        $project = $this->identity->getProject($projectId);
        if (!$project) return false;

        $users = $this->getProjectUsers($projectId);
        foreach ($users as $user) {
            foreach ($user['roles'] as $role) {
                $project->revokeUserRole(['userId' => $user['id'], 'roleId' => $role['id']]);
            }
        }
        $project->delete();
        return true;
    }

    public function updateProjectMember(Request $request)
    {
        if ($request->ajax()) {
            $projectId = $request->get('projectId');
            $members = $request->get('members');

            if (!$members) {
                return Response::JSON(['status' => 'Failed', 'message' => 'No Project Member Update!']);
            }
            if (!$this->updateProjectMemberI($projectId, $members)) {
                return Response::JSON(['status' => 'Failed', 'message' => 'Update Project Member Failed!']);
            }
        }
        return Response::JSON([['status' => 'Success', 'message' => 'Update Project Member Done.' ]]);
    }

    public function updateProjectMemberI($projectId, $member_roles)
    {
        $project = $this->identity->getProject($projectId);
        if (!$project) return false;

        $project->retrieve();

        //$cur_member_roles = array();
        foreach ($this->identity->listRoleAssignments(['projectId' => $projectId]) as $assign) {
            //array_push($cur_member_roles, ['userId' => $assign->user->id, 'roleId' => $assign->role->id]);
            $project->revokeUserRole(['userId' => $assign->user->id, 'roleId' => $assign->role->id]);
        }

        foreach ($member_roles as $new_member) {
            $uid = $this->getUserId($new_member['email']);
            //$rid = $new_member['roleId'];
            $project->grantUserRole(['userId' => $uid, 'roleId' => $new_member['roleId']]);
        }
        return true;
    }

    public function getHypervisors(Request $request)
    {
        if ($request->ajax()) {
            $hypervisor_list = array();
            $compute = $this->openstack->computeV2();
            $hypervisors = $compute->listHypervisors(true);
            foreach ($hypervisors as $hypervisor) {

                //$hypervisor = $compute->getHypervisor(['id' => '{hypervisorId}']);
                //$hy = $hypervisor->retrieveServers();
                array_push($hypervisor_list, $hypervisor);
            }
            return Response::json($hypervisor_list);
        }
    }

    public function getQuota(Request $request, $projectId)
    {
        if ($request->ajax()) {
            $quotaNova = $this->openstack->computeV2()->getQuotaSet($projectId, true);
            $quotaNova->retrieve();
            $quotaCinder = $this->openstack->blockStorageV2()->getQuotaSet($projectId);
            $quotaCinder->retrieve();
            $quotaNet = $this->openstack->networkingV2()->getQuota($projectId);
            $quotaNet->retrieve();

            $result = ['id' => $quotaNova->tenantId, 'instances' => $quotaNova->instances, 'vcpus' => $quotaNova->cores,
                'ram' => $quotaNova->ram, 'metadata' => $quotaNova->metadataItems,
                'volumes' => $quotaCinder->volumes, 'snapshots' => $quotaCinder->snapshots, 'snapshot_size' => $quotaCinder->gigabytes,
                'fips' => $quotaNet->floatingip, 'nets' => $quotaNet->network, 'subnets' => $quotaNet->subnet, 'ports' => $quotaNet->port,
                'routers' => $quotaNet->router
                ];
            return Response::JSON($result);
        }
    }

    public function removeTenantMember($projectname, $username){
        $userid= $this->getUserId($username);
        $user = $this->identity->getUser($userid);

        foreach ($user->listProjects() as $project) {
            if ($project->name ==$projectname){
                $project->revokeUserRole(['userId' => $userid, 'roleId' => '9fe2ff9ee4384b1894a90878d3e92bab']);
                return $project->id;
            }
        }
        return false;
    }

    public function addTenantMember($projectid, $username){
        $userid= $this->getUserId($username);
        $project = $this->identity->getProject($projectid);
        $project->grantUserRole(['userId' => $userid, 'roleId' => '9fe2ff9ee4384b1894a90878d3e92bab']);
        return true;

    }

}