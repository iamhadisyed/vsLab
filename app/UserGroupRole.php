<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/6/18
 * Time: 1:45 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGroupRole extends Model
{
    public $table = 'users_groups_roles';

    public $fillable = ['user_id', 'group_id', 'role_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function group()
    {
        return $this->belongsTo('App\Group');
    }

}