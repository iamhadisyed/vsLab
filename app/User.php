<?php

namespace App;

use Cache;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements HasMedia
{
    use Notifiable;
    use HasRoles;
    use HasMediaTrait;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'institute', 'alt_email', 'activation_code', 'org_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function profile()
    {
        return $this->hasOne('App\UserProfile');
    }

    public function groups()
    {
        return $this->belongsToMany('App\Group', 'users_groups_roles');
    }

    public function sites()
    {
        return $this->belongsToMany('App\Site', 'users_sites_roles');
    }

    public function subgroups()
    {
        return $this->belongsToMany('App\Subgroup', 'users_subgroups', 'user_id', 'subgroup_id');
    }

    public function siteAdmins()
    {
        return $this->belongsToMany('App\Site', 'sites_admins');
    }

    public function usersSitesRoles()
    {
        return $this->hasMany('App\UserSiteRole');
    }

    public function usersGroupsRoles()
    {
        return $this->hasMany('App\UserGroupRole');
    }

    public function userSubtaskSubmission()
    {
        return $this->hasMany('App\UserSubtaskSubmission');
    }
}
