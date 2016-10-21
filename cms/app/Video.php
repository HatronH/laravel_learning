<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    //
    protected $fillable = ['title'];

    public function tags() {
        return $this->morphToMany('App\Tag', 'tag_object');
    }

}
