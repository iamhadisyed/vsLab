<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/2/18
 * Time: 3:17 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubgroupTempProjectUuid extends Model
{
    public $table = 'project_uuid';

    protected $primaryKey = null;

    public $incrementing = false;

    public $fillable = ['project_name','uuid'];

    public function subgrouptempproject()
    {
        return $this->belongsTo('App\SubgroupTempProject','project_name');
    }


}