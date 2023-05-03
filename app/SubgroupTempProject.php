<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/2/18
 * Time: 3:17 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubgroupTempProject extends Model
{
    public $table = 'subgroup_template_project';

    protected $primaryKey = null;

    public $incrementing = false;

    public $fillable = ['project_name','subgroup_id'];

    public function subgroup()
    {
        return $this->belongsTo('App\Subgroup','subgroup_id');
    }

//    public function subgrouptempprojectuuid()
//    {
//        return $this->hasMany('App\SubgroupTempProjectUuid','project_name');
//    }

    public function labcontent()
    {
        return $this->belongsTo('App\LabContent');
    }
}