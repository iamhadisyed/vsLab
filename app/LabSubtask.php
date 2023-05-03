<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 9/23/18
 * Time: 2:45 AM
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class LabSubtask extends Model
{
    public $table = 'lab_submission';

    public $fillable = ['name', 'description','content','taskid','type','seq'];

    public function labTask()
    {
        return $this->belongsTo('App\LabTask');
    }
    public function userSubtaskSubmission()
    {
        return $this->hasMany('App\UserSubtaskSubmission');
    }
}