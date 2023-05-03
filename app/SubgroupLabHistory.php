<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/4/18
 * Time: 2:16 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubgroupLabHistory extends Model
{
    public $table = 'subgroup_lab_history';
    /**
     * Fillable fields for user lab history
     *
     * @var array
     */
    protected $fillable = [
        'user_id','labenv_id', 'labcontent_id', 'grades','subgroup_id'
    ];

    /**
     * A history of labs a user done
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function labenv()
    {
        return $this->belongsTo('App\LabEnv');
    }
    public function labcontent()
    {
        return $this->belongsTo('App\LabContent');
    }

    public function subgroup()
    {
        return $this->belongsTo('App\Subgroup');
    }

}