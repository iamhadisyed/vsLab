<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/2/18
 * Time: 3:17 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class LabContent extends Model
{
    public $table = 'labcontent';

    public $fillable = ['name','content', 'description', 'taskcount','publicflag','lab_cat_id','owner_id','tags','objects','os','experttime','time','difficulty','preparations','created_at','updated_at'];


    public function labTask()
    {
        return $this->hasMany('App\LabTask','labid');
    }


    public function labs()
    {
        return $this->belongsTo('App\Labs','labcontent_id');
    }

    public function owner()
    {
        return $this->belongsTo('App\User','owner_id');
    }

    public function deploylabs()
    {
        return $this->belongsToMany('App\DeployLab', 'deploylab_labcontent', 'labcontent_id', 'deploylab_id')
            ->withPivot('start_at', 'due_at')->withTimestamps();
    }
}