<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/2/18
 * Time: 3:17 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class LabTask extends Model
{
    public $table = 'lab_task';

    public $fillable = ['name', 'content','labid','owner_id','submission'];

    public function lab()
    {
        return $this->belongsTo('App\LabContent','instanceid');
    }
    public function subtask()
    {
        return $this->hasMany('App\LabSubtask');
    }
    public function userTask()
    {
        return $this->hasMany('App\UserTask','task_id');
    }
}