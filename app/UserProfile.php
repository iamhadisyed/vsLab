<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/4/18
 * Time: 2:16 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    public $table = 'user_profiles';
    /**
     * Fillable fields for a Profile
     *
     * @var array
     */
    protected $fillable = [
        'user_id','first_name', 'last_name', 'phone', 'state', 'city', 'country', 'zip', 'address', 'timezone','password_updated_at'
    ];

    /**
     * A profile belongs to a user
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}