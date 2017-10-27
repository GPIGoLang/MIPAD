<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password','group_id', 'activation_code', 'verified', 'org_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'api_token', 'remember_token', 'deleted_at', 'activation_code'
    ];

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function group() {
        return $this->belongsTo(Group::class);
    }

    public function tests()
    {
        return $this->hasMany('App\UserTest');
    }

    public function isAdmin() {
        $permission = $this->group->permissions;

        $permission = json_decode($this->group->permissions, true);
        if($permission['admin']){
            return true;
        }else{
            return false;
        }
    }
}