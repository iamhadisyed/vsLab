<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/2/18
 * Time: 3:17 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Mpociot\HasCompositeKey\HasCompositeKey;

class DeployLab extends Model
{
//    use HasCompositeKey;

//    protected $primaryKey = ['subgroup_id', 'lab_id'];

    public $table = 'deploylabs';

    public $fillable = ['subgroup_id', 'lab_id', 'project_id', 'project_name', 'assign_at','deploy_at','status','description','start_time','due_at'];

    public function subgroup()
    {
        return $this->belongsTo('App\Subgroup','subgroup_id');
    }

    public function labs()
    {
        return $this->belongsTo('App\Labs','lab_id');
    }

    public function labcontents()
    {
        return $this->belongsToMany('App\LabContent', 'deploylab_labcontent', 'deploylab_id', 'labcontent_id')
            ->withPivot('start_at', 'due_at')->withTimestamps()->where('deploylab_labcontent.referencelabflag','=',0);
    }

    public function alllabcontents()
    {
        return $this->belongsToMany('App\LabContent', 'deploylab_labcontent', 'deploylab_id', 'labcontent_id')
            ->withPivot('start_at', 'due_at')->withTimestamps();
    }

    public function mainlabcontents()
    {
        return $this->belongsToMany('App\LabContent', 'deploylab_labcontent', 'deploylab_id', 'labcontent_id')
            ->withPivot('start_at', 'due_at')->withTimestamps()->where('deploylab_labcontent.usedtobemianflag','=',true);
    }

    public function referencelabcontents()
    {
        return $this->belongsToMany('App\LabContent', 'deploylab_labcontent', 'deploylab_id', 'labcontent_id')
            ->withPivot('start_at', 'due_at')->withTimestamps()->where('deploylab_labcontent.referencelabflag','=',1);
    }


    public function instances()
    {
        return $this->hasMany('App\Instances');
    }
}