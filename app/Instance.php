<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/15/18
 * Time: 5:21 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instance extends Model
{
    public $table = 'instances_federated';

    //public $fillable = ['name', 'description', 'group_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function deploylab()
    {
        return $this->belongsTo('App\DeployLab');
    }
}