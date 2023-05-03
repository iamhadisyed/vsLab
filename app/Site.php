<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/2/18
 * Time: 3:17 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    public $table = 'sites';

    public $fillable = ['name', 'description', 'resources', 'resource_usage', 'group_default_resource'];

    public function groups()
    {
        return $this->hasMany('App\Group');
    }

    public function sites()
    {
        return $this->hasMany('App\Site');
    }

    public function usersSitesRoles()
    {
        return $this->hasMany('App\UserSiteRole');
    }

//    public function admins()
//    {
//        return $this->belongsToMany('App\User', 'sites_admins');
//    }
}