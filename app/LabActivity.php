<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/2/18
 * Time: 3:17 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class LabActivity extends Model
{
    public $table = 'activity_log';

    public $fillable = ['log_name', 'description', 'causer_id','created_at'];

    public function deploylab()
    {
        return $this->belongsTo('App\DeployLab','description');
    }

}