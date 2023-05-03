<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/20/19
 * Time: 3:17 PM
 */
namespace App\Http\Controllers;

use App, Auth;
use Carbon\Carbon;
use OpenStack\OpenStack;
use OpenCloud\OpenStack as OpenCloud;
use Illuminate\Http\Request;
use Regulus\ActivityLog\Models\Activity;
use Adldap\Laravel\Facades\Adldap;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Exception\ClientErrorResponseException;

class CloudResourceApi extends Controller
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

    public function __construct(Request $request)
    {
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
//            } else if (($this->project = $request->get('project')) == 'dummy') {
//                $this->project = config('openstack.dummy_project_id');
        }

        $this->op = $this->getUserOpenStack();

        //            $this->isUserEnabled = $this->openstackres->isUserEnabled($this->op_userid);
        //            if (!$this->isUserEnabled) {
        //                $this->isUserEnabled = $this->openstackres->enableUser($this->op_userid);
        //            }
        $this->openstackres->setdummyproject($this->op_userid);
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

    public function createStackFromIV2($stackname, $project_name, $template)
    {
        $opV2 = new OpenCloud(config('openstack.auth_url_v2'), array(
            'username' => $this->user,
            'password' => $this->user_password,
            'tenantName' => $project_name,
        ));

        $orchestrationService = $opV2->orchestrationService('heat', 'RegionOne', 'internalURL');

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

//            $stack->waitFor('CREATE_COMPLETE', 600, function($stack) use ($lab) {
//                $lab->fill(['status' => $stack->status()])->save();
//            });
//
            $response = array('status' => 'Success', 'message' => $stackname . ' created!');
        } catch (ClientErrorResponseException $e) {
            $response = array(
                'status' => 'Failed',
                'message' => $e->getMessage(), //"You don't have privilege to deploy template. Please contact contact@athenets.com to get trial permission.",
            );
//            Activity::log(['contentType' => 'Cloud',
//                'action' => 'Create Stack Failed.',
//                'description' => 'Create a cloud stack failed',
//                'details' => $message
//            ]);
        }
        //return $response;
    }

    public function getStackV2($project_name)
    {
        $opV2 = new OpenCloud(config('openstack.auth_url_v2'), array(
            'username' => $this->user,
            'password' => $this->user_password,
            'tenantName' => $project_name,
        ));

        try {
            $orchestrationService = $opV2->orchestrationService('heat', 'RegionOne', 'internalURL');
            $stack = $orchestrationService->getStack('s-' . $project_name);
        } catch(BadResponseException $e) {
            return null;
        }
        return $stack;
    }

    public function deleteStackV2($project_name)
    {
        $opV2 = new OpenCloud(config('openstack.auth_url_v2'), array(
            'username' => $this->user,
            'password' => $this->user_password,
            'tenantName' => $project_name,
        ));

        try {
            $orchestrationService = $opV2->orchestrationService('heat', 'RegionOne', 'internalURL');
            $stack = $orchestrationService->getStack('s-' . $project_name);
        } catch(BadResponseException $e) {
            return null;
        }
        $stack->delete();
    }

}