<?php
namespace App\Http\Controllers;

use DB;
use Xavrsl\Cas\Facades\Cas;
/**
 * Created by PhpStorm.
 * User: root
 * Date: 4/8/15
 * Time: 9:37 PM
 */
class RolePermission extends Controller
{
    private $user;
    public $permissions = array();

    // constructor
    public function __construct()
    {
        if (Cas::isAuthenticated()) {
            $this->user = Cas::getCurrentUser();

            $result = DB::select("SELECT permission.permissiondetails FROM permission WHERE permission.id IN " .
                                                    "(SELECT users_groups.role_id FROM users_groups JOIN users ON users_groups.user_id=users.id " .
                                                    "WHERE users.email = ?)", array($this->user));
            foreach ($result as $row) {
                $this->permissions = array_merge($this->permissions, explode(':', $row->permissiondetails));
            }
        }
    }

    // is functions
    public function is_deploy()
    {
        $b = false;
        foreach ($this->permissions as $p) {
            if ($p == 'deploy') {
                $b = true;
            }
        }
        return $b;
    }

    public function is_create_group()
    {
        return true;
    }

    public function is_create_subgroup()
    {
        return true;
    }

    public function is_create_template()
    {
        return true;
    }

    // setters

}
