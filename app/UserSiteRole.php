<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/6/18
 * Time: 1:45 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSiteRole extends Model
{
    public $table = 'users_sites_roles';

    public $fillable = ['user_id', 'site_id', 'role_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function site()
    {
        return $this->belongsTo('App\Site');
    }

}