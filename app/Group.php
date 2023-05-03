<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/5/18
 * Time: 9:42 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public $table = 'groups';

    public $fillable = ['name', 'description', 'site_id', 'resource_requested' , 'private', 'expiration',
        'approved', 'enabled', 'resource_allocated', 'resource_usage'];

    public function site()
    {
        return $this->belongsTo('App\Site');
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'users_groups');
    }

    public function usersGroupsRoles()
    {
        return $this->hasMany('App\UserGroupRole');
    }

    public function subgroups()
    {
        return $this->hasMany('App\Subgroup');
    }

    public function lab()
    {
        return $this->hasMany('App\Lab');
    }
}