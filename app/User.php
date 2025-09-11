<?php

namespace App;

use App\Models\Post;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

//class User extends Authenticatable implements MustVerifyEmail

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date',
        'phone_verified_at' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'id';
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    //public function setPasswordAttribute($value){

    //$this->attributes['password'] = bcrypt($value);
    //}
}
