<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;

use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use Notifiable;


    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'perms' => 'array'
    ];



    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function roles()
    {
        return  $this->belongsToMany('App\Models\Role', 'user_role');
    }

    // public function check($var)
    // {
    //     if (!is_array($var)) $var = [$var];
    //     if (!empty(array_intersect(['admin'],  $this->permissions))) return 1;
    //     return   !empty(array_intersect($var,  $this->permissions));
    // }
}
