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
        'email', 
        'password', 
        'user_id', 
        'login_id', 
        'first_name', 
        'middle_name', 
        'last_name', 
        'role_id', 
        'custname',
        'tmprole',
        'can_modify_referrals',
        'is_admin',
        'can_create_users',
        'can_view_reports',
        'disabled',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function u2c()
    {
        return $this->hasMany('App\UsersToClients');
    }

    public function permissions()
    {
        return $this->hasMany('App\Permission');
    }
}
