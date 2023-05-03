<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 4/28/15
 * Time: 4:41 PM
 */

namespace App\Http\Controllers;
use View;
use Redirect;
use Sabre\DAV\Client;

class OwnCloudController extends Controller {
    protected $user;
    protected $password;

    protected $client;

    public function __construct()
    {
        if (\Cas::isAuthenticated()) {
            $this->user = \Cas::getCurrentUser();

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

//            include 'vendor/autoload.php';

            $settings = array(
                //'baseUri' => 'https://personal.mobicloud.asu.edu/remote.php/webdav/',
                'userName' => $this->user,
                'password' => $this->password,
            );

            $this->client = new Client($settings);
        } else {
            return Redirect::to('/');
        }
    }

    public function getOwncloudFilelist() {
        if (Request::ajax()) {
            $input = Input::all();
            // must lib/Client.php 336: curl_setopt($this->curlHandle, CURLOPT_SSL_VERIFYPEER, false);
            $response = $this->client->propfind(rawurlencode($input['path']), array(), 1);
            return Response::json($response);
        }
    }
}