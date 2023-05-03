<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/6/18
 * Time: 1:45 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class UserSubtaskSubmission extends Model implements HasMedia
{
    use HasMediaTrait;
    public $table = 'users_subtasks_submission';

    public $fillable = ['user_id', 'lab_id','task_id','submission'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function lab()
    {
        return $this->belongsTo('App\Lab');
    }

    public function labSubtask()
    {
        return $this->belongsTo('App\LabSubtask');
    }

}