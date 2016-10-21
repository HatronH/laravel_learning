<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    //

    protected $fillable = [
        'file_path'
    ];

    public function image_object() {
        return $this->morphTo();
    }

}
