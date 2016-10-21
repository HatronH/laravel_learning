<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function post(){

        return $this->hasOne('App\Post');
    }

    public function posts(){
        return $this->hasMany('App\Post');
        /*
         * If the column name is different from user_id, id
         * then it can be customized as follow
         * */
        //return $this->hasMany('App\Post','another_user_id','id');

    }

    public function roles(){
        return $this->belongsToMany('App\Role');

        /*
         * The following defines which columns from the pivot table are visible
         * */
        //return $this->belongsToMany('App\Role')->withPivot('id','created_at');

        /*
         * If the column name is different from user_id, role_id (or the table name is different)
         * then use the following syntax
         * */
        //return $this->belongsToMany('App\Role','user_roles','another_user_id','role_id');
    }

    public function photos(){
        return $this->morphMany('App\Photo','image_object');
    }

    public function isadmin(){
        foreach($this->roles as $role) {
            if($role->name == 'Administrator'){
                return true;
            }
        }
        return false;
    }

}
