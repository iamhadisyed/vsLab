<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/7/17
 * Time: 2:05 PM
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use OpenStack\OpenStack;

class OpenCloud
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function getOpenStack()
    {
        $client = new OpenStack([
            'authUrl'   => config('openstack.auth_url'),
            'region'    => config('openstack.region'),
            'user'      => [
                'id'      => config('openstack.admin_id'),
                'password'  => config('admin_password'),
            ],
            'scope'     => [
                'project' => ['id' => config('dummy_project_id')]
            ]
        ]);

        return $client;
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
}