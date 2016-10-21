<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //

    protected $fillable = ['name'];

    public function posts(){
        return $this->morphedByMany('App\Post', 'tag_object');
    }


    public function videos(){
        return $this->morphedByMany('App\Video', 'tag_object');
    }
}
