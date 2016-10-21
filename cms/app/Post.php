<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    //

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'posts';

    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'content'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }


    /*
     * Use morphMany when relating to another table
     * In this example photos is the other table
     * */
    public function photos(){
        return $this->morphMany('App\Photo','image_object');
    }

    /*
     * Use morphToMany when relating to a pivot table
     * In this example tag_objects is the pivot table
     * ############## TRICK #############
     * The name 'tag_object' must be in single form,
     * it will then look for the field 'tag_object_id' from the plural form table name 'tag_objects' by default.
     * If 'tag_objects' is used instead,
     * it will prompt 'tag_objects_id' not found.
     * The pivot table name is also very important to be in the plural form.
     * If that table is not 'tag_objects', it will prompt 'another_table_name'.'tag_object_id' not found
     * ############ END TRICK ###########
     * */
    public function tags() {
        return $this->morphToMany('App\Tag', 'tag_object');
    }


    public function getTitleAttribute($value) {
        //return strtoupper($value);
        return ucfirst($value);
    }

    public function setTitleAttribute($value) {
        //$this->attributes['title'] = strtoupper($value);
        $this->attributes['title'] = ucfirst($value);
    }

    public static function scopeDescending($query){
        return $query->orderBy('id','desc')->get();
    }

}
