<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/2/18
 * Time: 3:17 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    public $table = 'lab';

    public $fillable = ['name', 'description', 'taskcount','starttime','due','publicflag'];

    public function group()
    {
        return $this->belongsTo('App\Group','groupid');
    }

    public function labTask()
    {
        return $this->hasMany('App\LabTask','labid');
    }

    public function userSubtaskSubmission()
    {
        return $this->hasMany('App\UserSubtaskSubmission');
    }



}