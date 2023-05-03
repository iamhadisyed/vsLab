<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/15/18
 * Time: 5:21 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instanceinactive extends Model
{
    public $table = 'instances_status';

    public $fillable = ['id', 'inactivefor','uuid','instanceid'];


    public function instance()
    {
        return $this->belongsTo('App\Instance','instanceid');
    }
}