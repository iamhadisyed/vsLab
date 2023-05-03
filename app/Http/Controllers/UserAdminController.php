<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/14/17
 * Time: 2:43 PM
 */
namespace App\Http\Controllers;

use App;
use App\User;
use App\UserProfile;
use Xavrsl\Cas\Facades\Cas;
use Regulus\ActivityLog\Models\Activity;
use Illuminate\Http\Request;
use Response, Redirect, DB;
use Adldap\Laravel\Facades\Adldap;

class UserAdminController extends Controller
{

    protected $user;

    public function __construct()
    {
        if (Cas::isAuthenticated()) {
            $this->user = Cas::getCurrentUser();
        } else {
            return Redirect::to('/');
        }
    }

    public function addNewUser(Request $request)
    {
        $nUser = $request->get('user');

//        $isUser = User::where('email', '=', $nUser['email'])->get()->first();
//        if ($isUser) {
//            return Response::JSON(['status' => 'Failed', 'message' => 'User ' . $nUser['email'] . ' already exists!']);
//        }

        $randompass = substr(str_shuffle(MD5(microtime())), 0, 10);
        $new_user = array_merge($nUser, ['password'=>$randompass, 'password_confirmation'=>$randompass]);
        $user = App::make('register_service')->register2($new_user);
        //App::make('register_service')->sendRegistrationMailToClient2(['email' => $nUser['email'], 'password' => '7cba486746', 'first_name' => 'James']);
        if ($user) {
            return Response::JSON(['status' => 'Success', 'message' => 'User ' . $nUser['email'] . ' has been registered.']);
        } else {
            return Response::JSON(['status' => 'Failed', 'message' => 'User ' . $nUser['email'] . ' registration failed']);
        }
//        $l_user = Adldap::search()->where('cn', '=', $nUser['email'])->get()->first();
//        if ($l_user) {
//            return Response::JSON(['status' => 'Failed', 'message' => 'User ' . $nUser['email'] . ' already exists in LDAP!']);
//        }
//        $random_pass = substr(str_shuffle(MD5(microtime())), 0, 10);
//        $info = ['givenName' => $nUser['first_name'], 'ou' => $nUser['last_name'], 'sn' => $nUser['email'],
//            'userPassword' => $random_pass, 'c' => $nUser['country'], 'o' => $nUser['institute']];
//
//        $ldap_user = Adldap::make()->user($info);
//        $ldap_user->setAttribute('objectClass', ['inetOrgPerson', 'extensibleObject']);
//        $ldap_user->setDn('cn=' . $nUser['email'] . ',' . $ldap_user->getDnBuilder()->get());
//
//        $ldap_user->save();
//
//
//        $db_user = new User();
//        $db_user->fill()->save();
//        $profile = new UserProfile();
//        $profile->fill();
//        $db_user->profile()->save($profile);
    }

    public function selectLdapUser(Request $request)
    {
        if ($request->ajax()) {
            $t0 = microtime(true);
            $t1 = microtime(true);
            $t2 = microtime(true);
            $t3 = microtime(true);
            $t4 = microtime(true);
            $t5 = microtime(true);
            $t6 = microtime(true);

            // profile database
            $arr = array("user" => $this->user);
            $t1 = microtime(true);
            $t2 = microtime(true);

            $result = DB::select("SELECT users_groups.subgroup, groups.name AS 'group', permission.description AS 'role' " .
                "FROM users_groups " .
                "JOIN users ON users_groups.user_id = users.id " .
                "JOIN groups ON users_groups.group_id = groups.id " .
                "JOIN permission ON users_groups.role_id = permission.id " .
                "WHERE users.email = ? AND users.id <> groups.owner_id", array($this->user));
            $t3 = microtime(true);

            $arr["group_role"] = array();
            foreach($result as $row) {
                $groupRole = array("group" => $row->group, "role" => $row->role, "subgroup" => $row->subgroup);
                if ($row->role != 'GlobalSuperUser' && $row->role != 'GlobalAdmin' && $row->role != 'Trial') {
                    array_push($arr["group_role"], $groupRole);
                }
            }
            // user profile
            $t4 = microtime(true);

            $result = DB::select("SELECT users.avatar, users.id, users.alt_email, user_profiles.first_name, " .
                "user_profiles.last_name, user_profiles.phone, user_profiles.address, user_profiles.city, " .
                "user_profiles.state, user_profiles.country, user_profiles.zip, users.institute, users.org_id " .
                "FROM user_profiles JOIN users ON user_profiles.user_id = users.id " .
                "WHERE users.email = ?", array($this->user));
            $t5 = microtime(true);

            //while ($row = $result->fetch_assoc()) {
            foreach ($result as $row) {
                if ($row->avatar == "") {
                    $arr["user_profile"] = array(
                        "avatar" => "default.jpg",
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
                        //"org_id" => $row["org_id"]
                    );
                } else {
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
                        //"org_id" => $row["org_id"]
                    );
                }
            }
            $t6 = microtime(true);

            $arr["ttt"] = array(
                "t0" => $t0,
                "t1" => $t1,
                "t2" => $t2,
                "t3" => $t3,
                "t4" => $t4,
                "t5" => $t5,
                "t6" => $t6,
            );
            return Response::json($arr);
        }
    }

    function myhash($string)
    {
        // Usually caused by an old PHP environment, see
        // https://github.com/cartalyst/sentry/issues/98#issuecomment-12974603
        // and https://github.com/ircmaxell/password_compat/issues/10
        if (!function_exists('password_hash')) {
            throw new \RuntimeException('The function password_hash() does not exist, your PHP environment is probably incompatible. Try running [vendor/ircmaxell/password-compat/version-test.php] to check compatibility or use an alternative hashing strategy.');
        }

        if (($hash = password_hash($string, PASSWORD_DEFAULT)) === false) {
            throw new \RuntimeException('Error generating hash from string, your PHP environment is probably incompatible. Try running [vendor/ircmaxell/password-compat/version-test.php] to check compatibility or use an alternative hashing strategy.');
        }

        return $hash;
    }

    public function changePassword(Request $request, $id)
    {
        if (Request()->ajax()) {
            $cur_pass = $request->get('cur_pass');
            $new_pass = $request->get('new_pass');

            $ldap_user = Adldap::search()->where('cn', '=', $this->user)->first();
            if ($cur_pass != $ldap_user->userpassword[0]) {
                return Response::JSON(['status' => 'Failed', 'message' => 'Current Password doesn\'t match!']);
            }
            if ($new_pass != $ldap_user->userpassword[0]) {
                return Response::JSON(['status' => 'Failed', 'message' => 'New Password is same as current password!']);
            }

            $user = User::find($id);
            $user->fill(['password' => $this->myhash($new_pass)])->save();

            $ldap_user->update(['userPassword' => $new_pass]);

            return Response::json(['status' => 'Success', 'message' => 'User ' . $this->user . ' password updated.']);
        }
    }

    public function getFirstLogin() {
        if (Request()->ajax()) {
            $arr = array("user" => $this->user);
            $result = DB::select("SELECT first_time  FROM users  WHERE email = ? ", array($this->user));
            foreach($result as $row) {
                $arr = array("user" => $row->first_time);
                if (1==$row->first_time) {
                    $result2 = DB::update("update users set avatar='default.jpg' WHERE email=? ", array($this->user));
                    if ($result2) {
                        $result2 = false;
                    }
                }
            }
            return Response::json($arr);
        }
    }

    public function getShowHelp() {
        if (Request()->ajax()) {
            $arr = array("user" => $this->user);
            $sql = "SELECT show_help_info  FROM users  WHERE email='" . $this->user . "' ";
            $result = DB::select($sql);
            foreach($result as $row) {
                $arr = array("user" => $row->show_help_info);
            }
            return Response::json($arr);
        }
    }

    public function searchUserRole(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->input();
            $arr = array();
            $arr["users_super"] = array();
            $arr["users_admin"] = array();
            $arr["users_normal"] = array();
            $arr["users_trial"] = array();

            $sql2 = "(select user_id from users_groups where users_groups.group_id='112' and users_groups.role_id='19')";
            $sql = "SELECT users.email FROM users " .
                "WHERE users.email LIKE '%" . $input['search_user_txt'] . "%' and " .
                "users.id in " . $sql2 ;
            $result = DB::select($sql);
            foreach($result as $row) {
                array_push($arr["users_super"], $row->email);
            }

            $sql2 = "(select user_id from users_groups where users_groups.group_id='447' and users_groups.role_id='17')";
            $sql = "SELECT users.email FROM users " .
                "WHERE users.email LIKE '%" . $input['search_user_txt'] . "%' and " .
                "users.id in " . $sql2 ;
            $result = DB::select($sql);
            foreach($result as $row) {
                array_push($arr["users_trial"], $row->email);
            }

            $sql2 = "(select user_id from users_groups where users_groups.group_id='91' and users_groups.role_id='18')";
            $sql = "SELECT users.email FROM users " .
                "WHERE users.email LIKE '%" . $input['search_user_txt'] . "%' and " .
                "users.id in " . $sql2 ;
            $result = DB::select($sql);
            foreach($result as $row) {
                array_push($arr["users_admin"], $row->email);
            }

            $sql2 = "(select user_id from users_groups where users_groups.group_id='112' and users_groups.role_id='19')";
            $sql3 = "(select user_id from users_groups where users_groups.group_id='91' and users_groups.role_id='18')";
            $sql4 = "(select user_id from users_groups where users_groups.group_id='447' and users_groups.role_id='17')";
            $sql = "SELECT users.email FROM users " .
                "WHERE users.email LIKE '%" . $input['search_user_txt'] . "%' and " .
                "users.id not in " . $sql2 ." and users.id not in " .$sql3  ." and users.id not in " .$sql4;
            $result = DB::select($sql);
            foreach($result as $row) {
                array_push($arr["users_normal"], $row->email);
            }

            return Response::json($arr);
        }
    }

    public function searchUserRolebyRole(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->input();
            $arr = array();
            $arr["users_super"] = array();
            $arr["users_admin"] = array();
            $arr["users_normal"] = array();
            $arr["users_trial"] = array();

            if ($input['search_role_txt'] == "superuser") {
                $sql2 = "select users.email  from users_groups join users on users_groups.user_id=users.id where users_groups.group_id='112' and users_groups.role_id='19'";
                $sql = "SELECT users.email FROM users " .
                    "WHERE users.email LIKE '%" . $input['search_role_txt'] . "%' and " .
                    "users.id in " . $sql2;
                //$result = $conn->query($sql);
                $result = DB::select($sql2);
                //while ($row = $result->fetch_assoc()) {
                foreach ($result as $row) {
                    array_push($arr["users_super"], $row->email);
                }
            }

            if ($input['search_role_txt'] == "adminuser") {
                $sql2 = "(select users.email from users_groups join users on users_groups.user_id=users.id where users_groups.group_id='91' and users_groups.role_id='18')";
                $result = DB::select($sql2);
                foreach ($result as $row) {
                    array_push($arr["users_admin"], $row->email);
                }
            }

            if ($input['search_role_txt'] == "trialuser") {
                $sql2 = "(select users.email from users_groups join users on users_groups.user_id=users.id where users_groups.group_id='447' and users_groups.role_id='17')";
                $result = DB::select($sql2);
                foreach ($result as $row) {
                    array_push($arr["users_trial"], $row->email);
                }
            }

            if ($input['search_role_txt'] == "normaluser") {
                $sql2 = "(select user_id from users_groups where users_groups.group_id='112' and users_groups.role_id='19')";
                $sql3 = "(select user_id from users_groups where users_groups.group_id='91' and users_groups.role_id='18')";
                $sql4 = "(select user_id from users_groups where users_groups.group_id='447' and users_groups.role_id='17')";
                $sql = "SELECT users.email FROM users " .
                    "WHERE  users.id not in " . $sql2 . " and users.id not in " . $sql3 . " and users.id not in " . $sql4;
                $result = DB::select($sql);
                foreach ($result as $row) {
                    array_push($arr["users_normal"], $row->email);
                }
            }

            return Response::json($arr);
        }
    }

    public function downloadvpnconifg(Request $request) {
        if (Request()->ajax()) {
            //$input = Input::all();
            $input = $request->input();

            // profile database
            $servername = "10.2.255.50";
            $username = "root";
            $password = "Cloud\$erver";
            $database = "mobicloud";
            $port = 3307;
            // return json
            $arr = array();
            $arr["filenames"] = array();
            // connection
            //$conn = new mysqli($servername, $username, $password, $database, $port);
            //if ($conn->connect_error) {
            //    die("Connection failed: " . $conn->connect_error);
            //} else {
            // group role
            //$sql3 = "(Select owner_id from groups where groups.id='" . $input["select_group_id"] . "')";
            //$sql2 = "(select user_id from users_groups where users_groups.group_id='" . $input["select_group_id"] . "')";
            $sql = "SELECT users.vpnId, users.email FROM users " .
//                    "JOIN tmp_users_groups ON groups.id=tmp_users_groups.group_id " .
//                    "JOIN users ON groups.owner_id=users.id " .
                "WHERE users.email =  '" . $this->user . "'  ";// .
            //"users.id not in " . $sql2 . " and users.id not in " . $sql3;// .
//                    "AND users.email<>'" . $this->user . "'";
            //$result = $conn->query($sql);
            $result = DB::select($sql);
            //while ($row = $result->fetch_assoc()) {
            foreach($result as $row) {
                //$oneuser = array("email" => $row["email"], "institute" => $row["institute"], "org_id" => $row["org_id"]);

                if (strlen($row->vpnId)<2) {
                    $randompass = substr(str_shuffle(MD5(microtime())), 0, 10);

                    //shell_
                    //exec('bash -x /var/www/mobicloud/app/controllers'.'/testvpn.sh '. $randompass .' 2>&1 | tee -a /tmp/vpn.log; echo test', $output);

                    //$output = "";
                    SSH::run(array(
                        'cd /etc/openvpn/easy-rsa/',
                        'source ./vars',
                        './build-key --batch '. $randompass,
                        'cd ./keys',
                        'cat thoth_base.ovpn > '.$randompass.'.ovpn',
                        'echo \'<ca>\' >> '.$randompass.'.ovpn',
                        'cat ca.crt >> '.$randompass.'.ovpn',
                        'echo \'</ca>\' >> '.$randompass.'.ovpn',
                        'echo \'<cert>\' >> '.$randompass.'.ovpn',
                        'cat '.$randompass.'.crt >> '.$randompass.'.ovpn',
                        'echo \'</cert>\' >> '.$randompass.'.ovpn',
                        'echo \'<key>\' >> '.$randompass.'.ovpn',
                        'cat '.$randompass.'.key >> '.$randompass.'.ovpn',
                        'echo \'</key>\' >> '.$randompass.'.ovpn',
                    ), function($line){
                        $this->output .= $line.PHP_EOL;
                    });

                    SSH::get('/etc/openvpn/easy-rsa/keys/'.$randompass.'.ovpn',
                        '/var/www/mobicloud/public/files/'.$randompass.'.ovpn');

                    array_push($arr["filenames"], $randompass);

                    $sql = "Update users set vpnId='".$randompass."'  " .
                        "WHERE users.email =  '" . $this->user . "'  ";
                    //$result2 = $conn->query($sql);
                    $result2 = DB::update($sql);
                    if ($result2) {
                        $result2 = false;
                    }
                } else {
                    array_push($arr["filenames"], $row->vpnId);
                }
            }


            // close connection
//                mysqli_close($conn);
            //}

            return Response::json($arr);
        }
    }

    public function searchUser(Request $request)
    {
        if (Request()->ajax()) {
            //$input = Input::all();
            $input = $request->input();
            // profile database
            $servername = "10.2.255.50";
            $username = "root";
            $password = "Cloud\$erver";
            $database = "mobicloud";
            $port = 3307;
            // return json
            $arr = array();
            $arr["users"] = array();
            // connection
            //$conn = new mysqli($servername, $username, $password, $database, $port);
            //if ($conn->connect_error) {
            //    die("Connection failed: " . $conn->connect_error);
            //} else {
            // group role
            $sql3 = "(Select owner_id from groups where groups.id='" . $input["select_group_id"] . "')";
            $sql2 = "(select user_id from users_groups where users_groups.group_id='" . $input["select_group_id"] . "')";
            $sql = "SELECT users.email, users.institute, users.org_id FROM users " .
//                    "JOIN tmp_users_groups ON groups.id=tmp_users_groups.group_id " .
//                    "JOIN users ON groups.owner_id=users.id " .
                "WHERE users.email LIKE '%" . $input['search_user_txt'] . "%' and " .
                "users.id not in " . $sql2 . " and users.id not in " . $sql3;// .
//                    "AND users.email<>'" . $this->user . "'";
            //$result = $conn->query($sql);
            $result = DB::select($sql);
            //while ($row = $result->fetch_assoc()) {
            foreach($result as $row) {
                $oneuser = array("email" => $row->email, "institute" => $row->institute, "org_id" => $row->org_id);
                array_push($arr["users"], $oneuser);
            }

            // close connection
//                mysqli_close($conn);
            //}

            return Response::json($arr);
        }
    }

    public function userRoleUpdate(Request $request)
    {
        if (Request()->ajax()) {
            //$input = Input::all();
            $input = $request->input();
            // return json
            $arr = array();
            $arr["users"] = array();
            // group role
            if ($input["new_role"] == 'super') {
                $sql1 = "select id from users where email='".$input["user_email"]."'";
                //$result = $conn->query($sql1);
                $result = DB::select($sql1);
                if ($result == false) {
                    $arr["reuslt"]= $result;
                } else {
                    //while ($row = $result->fetch_assoc()) {
                    foreach ($result as $row) {
                        $sql = "insert into users_groups (user_id, group_id, role_id) values( ". $row->id.",112,19);" ;//.
                        //$result = $conn->query($sql);
                        $result = DB::insert($sql);
                        $arr["reuslt"]= $result;
                    }
                }
            } else if ($input["new_role"] == 'normal') {
                $sql = "DELETE users_groups FROM users_groups join users on users_groups.user_id=users.id WHERE (users_groups.role_id = 19 or users_groups.role_id = 18) AND users.email = '".$input["user_email"]."'; " ;//.
                //$result = $conn->query($sql);
                $result =DB::delete($sql);
                $arr["reuslt"]= $result;
            } else if ($input["new_role"] == 'admin') {// admin
                $sql1 = "select id from users where email='".$input["user_email"]."'";
                //$result = $conn->query($sql1);
                $result = DB::select($sql1);
                if ($result == false) {
                    $arr["reuslt"]= $result;
                } else {
                    //while ($row = $result->fetch_assoc()) {
                    foreach($result as $row) {
                        $sql = "insert into users_groups (user_id, group_id, role_id) values( ". $row->id.",91,18);" ;//.
                        //$result = $conn->query($sql);
                        $result = DB::insert($sql);
                        $arr["reuslt"]= $result;
                    }
                }
            } else if ($input["new_role"] == 'trial') {//trial
                $sql = "DELETE users_groups FROM users_groups join users on users_groups.user_id=users.id WHERE (users_groups.role_id = 19 or users_groups.role_id = 18) AND users.email = '".$input["user_email"]."'; " ;//.
                //$result = $conn->query($sql);
                $result =DB::delete($sql);
                $arr["reuslt"]= $result;
                //////////
                $sql1 = "select id from users where email='".$input["user_email"]."'";
                //$result = $conn->query($sql1);
                $result = DB::select($sql1);
                if ($result == false) {
                    $arr["reuslt"]= $result;
                } else {
                    //while ($row = $result->fetch_assoc()) {
                    foreach($result as $row) {
                        $sql = "insert into users_groups (user_id, group_id, role_id) values( ". $row->id.",447,17);" ;//.
                        //$result = $conn->query($sql);
                        $result = DB::insert($sql);
                        $arr["reuslt"]= $result;
                    }
                }
            }

            return Response::json($arr);
        }
    }

    public function updateUserProfile(Request $request)
    {
        if (Request()->ajax()) {
            //$input = Input::all();
            $input = $request->input();

            $arr = array("user" => $this->user);
            // connection
            //$conn = new mysqli($servername, $username, $password, $database, $port);
            //if ($conn->connect_error) {
            //    die("Connection failed: " . $conn->connect_error);
            //} else {
            // group role
            if (!array_key_exists("alt_email" , $input)) {
                $input['alt_email'] = '';
            }
            if (!array_key_exists("phone" , $input)) {
                $input['phone'] = '';
            }
            if (!array_key_exists("address" , $input)) {
                $input['address'] = '';
            }
            if (!array_key_exists("city" , $input)) {
                $input['city'] = '';
            }
            if (!array_key_exists("state" , $input)) {
                $input['state'] = '';
            }
            if (!array_key_exists("zip" , $input)) {
                $input['zip'] = '';
            }
            $sql = "UPDATE user_profiles JOIN users ON user_profiles.user_id=users.id SET " .
                "users.alt_email='" . $input['alt_email'] . "', " .
                "users.first_time=0, " .
                "user_profiles.first_name='" . $input['first_name'] . "', " .
                "user_profiles.last_name='" . $input['last_name'] . "', " .
                "user_profiles.phone='" . $input['phone'] . "', " .
                "user_profiles.address='" . $input['address'] . "', " .
                "user_profiles.city='" . $input['city'] . "', " .
                "user_profiles.state='" . $input['state'] . "', " .
                "user_profiles.country='" . $input['country'] . "', " .
                "user_profiles.zip='" . $input['zip'] . "', " .
                //"users.institute='" . $input['institute'] . "', " .
                "users.institute='" . $input['institute'] . "' " .
                //"users.org_id='" . $input['org_id'] . "' " .
                "WHERE users.email='" . $this->user . "'";
            //$result = $conn->query($sql);
            $result = DB::update("UPDATE user_profiles JOIN users ON user_profiles.user_id=users.id SET " .
                "users.alt_email=?, " .
                "users.first_time=0, " .
                "user_profiles.first_name=?, " .
                "user_profiles.last_name=?, " .
                "user_profiles.phone=?, " .
                "user_profiles.address=?, " .
                "user_profiles.city=?, " .
                "user_profiles.state=?, " .
                "user_profiles.country=?, " .
                "user_profiles.zip=?, " .
                "users.institute=? " .
                "WHERE users.email=?", array($input['alt_email'],
                $input['first_name'], $input['last_name'], $input['phone'], $input['address'], $input['city'],
                $input['state'], $input['country'], $input['zip'], $input['institute'], $this->user
            ));
            if ($result == TRUE) {
                //echo "update success";
                Activity::log(['contentType' => 'User',
                    'action' => 'Update Profile',
                    'description' => 'Update user profile',
                    'details' => 'User ' . \Cas::getCurrentUser() . ' update user profile.'
                ]);
            } else {
                //echo "update failure : " . $conn->error;
                Activity::log(['contentType' => 'User',
                    'action' => 'Failed',
                    'description' => 'Update user profile failed',
                    'details' => 'User ' . Cas::getCurrentUser() . ' update user profile failed.'
                ]);
            }
            return Response::json($arr);
        }
    }

    public function doNotshowhelp() {
        if (Request()->ajax()) {
            $arr = array();
            $sql = "Update users set  show_help_info =0 " .
                "WHERE users.email='" . $this->user. "';";// .
            $result = DB::update($sql);
            return Response::json($arr);
        }
    }
    public function checkIfShowHelp() {
        if (Request()->ajax()) {
            $arr = array();
            $sql = "SELECT users.show_help_info FROM users " .
                "WHERE users.email='" . $this->user. "';";// .
            $result = DB::select($sql);
            foreach($result as $row) {
                $arr["showHelp"] = $row->show_help_info;
            }
            return Response::json($arr);
        }
    }

}