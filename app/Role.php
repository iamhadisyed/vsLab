<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends \Spatie\Permission\Models\Role
{
    public function usersGroupsRoles()
    {
        return $this->hasMany('App\UserGroupRole');
    }

    public function usersSitesRoles()
    {
        return $this->hasMany('App\UserSiteRole');
    }
}
