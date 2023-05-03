<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/15/18
 * Time: 5:21 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subgroup extends Model
{
    public $table = 'subgroups';

    public $fillable = ['name', 'description', 'group_id'];

    public function group()
    {
        return $this->belongsTo('App\Group');
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'users_subgroups', 'subgroup_id');
    }

    public function deploylabs()
    {
        return $this->hasMany('App\DeployLab', 'subgroup_id');
    }

    public function subgrouptempproject()
    {
        return $this->hasMany('App\SubgroupTempProject','subgroup_id');
    }
}