<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/2/18
 * Time: 3:17 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class LabEnv extends Model
{
    public $table = 'labenv';

    public $fillable = ['name', 'template','description', 'lab_vis_json','owner_id', 'resource','publicflag','project_id','project_name','deploy_at','status',];


    public function labs()
    {
        return $this->belongsTo('App\Labs','labenv_id');
    }

    public function owner(){
        return $this->belongsTo('App\User','owner_id');
    }


}