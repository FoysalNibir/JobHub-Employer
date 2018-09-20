<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends \TCG\Voyager\Models\User
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function companydetail()
    {
        return $this->hasOne('App\Companydetail');
    }

    public function contactdetail()
    {
        return $this->hasOne('App\Contactdetail');
    }

    public function jobinfos()
    {
        return $this->hasMany('App\Jobinfo');
    }
}
