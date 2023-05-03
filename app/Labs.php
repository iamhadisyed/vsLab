<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/2/18
 * Time: 3:17 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Labs extends Model
{
    public $table = 'labs';

    public $fillable = ['name', 'description', 'publicflag','starttime','due','labenv_id','labcontent_id','owner_id'];

    public function labcontent()
    {
        return $this->hasOne('App\LabContent','labcontent_id');
    }


    public function labenv()
    {

        return $this->hasOne('App\LabEnv','labenv_id');
    }

    public function owner(){
        return $this->belongsTo('App\User','owner_id');
    }

    public function userSubtaskSubmission()
    {
        return $this->hasMany('App\UserSubtaskSubmission');
    }


}