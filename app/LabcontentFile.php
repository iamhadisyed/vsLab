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

class LabcontentFile extends Model implements HasMedia
{
    use HasMediaTrait;
    public $table = 'labcontent_files';

    public $fillable = ['user_id','submission','desc'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function labcontent()
    {
        return $this->belongsTo('App\LabContent');
    }



}