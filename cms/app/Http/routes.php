<?php

use App\Post;
use App\User;
use App\Role;
use App\Photo;
use App\Tag;
use App\Video;
use Carbon\Carbon;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/admin/user/roles', ['middleware'=>['role','auth','web'], function(){


    return "Middleware role";
}]);


Route::resource('/posts', 'PostsController');

/*
 * Since the 5.2.27 version,
 * the web middleware it's applied by default to all the routes.
 * So if you apply it again in the routes file, you'll not get any value from the $errors
//Route::group(['middleware' => ['web']], function () {
//});
 *
 *
 * */

Route::get('/', function () {
    //return view('welcome');

    if (Auth::check()){
        return "You are logged in";
    } else {
        return redirect('login');
    }
});

Route::get('/readpost', 'PostsController@show_post');

Route::get('/insert',function(){

    DB::insert('insert into posts(title, content, created_at) values (?,?,?)', ['First title','This is the 1st content','2016/10/17 14:43']);

    return "Inserted successfully";
});

Route::get('/read', function(){
    $results = DB::select('select * from posts where id >= ?',[1]);

    foreach($results as $post){
        echo "<li>". $post->title. "</li>";
    }

    return;
});

/*
 * ELOQUENT
 * */

Route::get('/createuser',function(){
    User::create(['name'=>'Marq','email'=>'marq@hotmail.com','password'=>bcrypt("123456")]);
    return;
});

Route::get('/eloqfind', function(){
    //$post = Post::find(2);
    //return $post;

    //$posts = Post::all();
    //foreach($posts as $post){
    //    return $post->title;
    //}

    $posts = Post::where('id','>',1)->orderBy('created_at','desc')->take(3)->get();
    foreach($posts as $post){
        echo "<li>". $post->title. "</li>";
    }

    return;
});

Route::get('/eloqinsert', function() {

    $post = new Post;

    $post->title = 'Elo first title';
    $post->content = 'Elo first content';
    $post->save();

    return;
});


Route::get('/eloqsave', function() {

    $post = Post::find(2);

    $post->title = 'Updated Elo title again';
    $post->content = 'Updated Elo content again';
    $post->save();

    return;
});


Route::get('/eloqphysdelete', function() {

    $post = Post::onlyTrashed()->where('id',1)->forcedelete();


    return;
});


Route::get('/eloqgetsoftdeleted', function() {

    $post = Post::onlyTrashed()->where('id',1)->get();
    //$post = Post::withTrashed()->where('id',1)->get();

    return $post;
});

Route::get('/eloqrestoresoftdeleted', function() {

    $post = Post::onlyTrashed()->where('id',1)->restore();
    //$post = Post::withTrashed()->where('id',1)->get();

    return;
});

Route::get('/eloqdestroy', function() {

    Post::destroy([17,19]);

    return;
});


Route::get('/eloqsoftdelete', function() {

    $post = Post::find(1);

    $post->delete();

    return;
});

Route::get('/eloqupdate', function() {

    Post::where('id',11)->update(['title'=>'Title updated by elo update', 'content'=>'Content updated by elo update']);

    return;
});


Route::get('/eloqcreate', function() {

    Post::create(['title'=>'elo create title', 'content'=>'elo create content']);

    return;
});

/*
 *
 * ELOQUENT RELATIONSHIPS
 *
 * */
/*
 * One to one
 */
Route::get('/user/{id}/post', function($id){

    return User::find($id)->post->title;

});

/*
 * One to one
 * */
Route::get('/post/{id}/user', function($id){
    return Post::find($id)->user->name;
});

/*
 * One to many
 * */
Route::get('/readpost/{id}', function($id){
    $user = User::find($id);

    foreach($user->posts as $post) {
        echo "<li>". $post->title. "</li>";
    }
    return;
});


Route::get('/createpost/{id}',function($id){
    $user = User::findOrFail($id);
    $post = new Post(['title'=>'New title created by user', 'content'=>'New content created by user']);
    $user->posts()->save($post);
    return;
});


Route::get('/updatepost/{id}',function($id){
    $user = User::findOrFail($id);
    /*
     * using ->first(), it returns a single object
     * using ->get(), it returns an array
     * */
    $post = $user->posts()->where('user_id','=',$id)->orderBy('id','desc')->first();
    $post->update(['title'=>'update title by user','content'=>'update content by user id']);

    return;
});

Route::get('/deletepost/{id}',function($id){
    $user = User::findOrFail($id);
    $post = $user->posts()->where('user_id','=',$id)->orderBy('id','desc')->first();
    $post->delete();

    return;
});


/*
 * Many to many
 * */
Route::get('/user/{id}/role', function($id){
    $user = User::find($id);

    foreach($user->roles as $role){
        echo $role->name;
    }

    return;
});

/*
 * Accessing the intermediate table / pivot table
 * */

Route::get('/user/pivot',function(){
    $user = User::find(1);

    foreach($user->roles as $role){
        echo $role->pivot->created_at;
    }

    return;
});


Route::get('/createuserrole/{id}', function($id){
    $user = User::findOrFail($id);

    $role = new Role(['name'=>'Agent']);

    $user->roles()->save($role);

    return;
});

/*
 *  Updating a pivot table
 * */
Route::get('/updateuserrole/{uid}/{oid}/{nid}', function($uid,$oid,$nid){
    $user = User::findOrFail($uid);

    if($user->has('roles')){
        $user->roles()->updateExistingPivot($oid,['role_id'=>$nid]);
    }

    return $nid;
});

/*
 *  Updating role table by user id
 * */
Route::get('/updaterolebyuser/{id}', function($id){
    $user = User::findOrFail($id);

    if($user->has('roles')){
        $roles = $user->roles()->where('name','=','Agent')->get();
        foreach($roles as $role){
            $role->update(['name'=>'Power User']);
        }
    }

    return;
});

/*
 *  Deleting from role table by user id
 * TRICK:
 * This does not delete the related record from the pivot table, so this will create orphan records
 * */
Route::get('/deleterolebyuser/{id}', function($id){
    $user = User::findOrFail($id);

    if($user->has('roles')){
        $roles = $user->roles()->where('name','=','Power User')->delete();
    }

    return;
});


Route::get('/attachuserrole/{uid}/{rid}', function($uid,$rid){

    $user = User::findOrFail($uid);

    $user->roles()->attach($rid);

    return;
});

Route::get('/detachuserrole/{uid}/{rid}', function($uid,$rid){

    $user = User::findOrFail($uid);

    /* detach() deletes all records of the user */
    //$user->roles()->detach();
    /* detach(id) deletes the specific records of the user */
    $user->roles()->detach($rid);

    return;
});

Route::get('/syncuserrole/{uid}', function($uid){

    $user = User::findOrFail($uid);

    /* detach() deletes all records of the user */
    //$user->roles()->detach();
    /* detach(id) deletes the specific records of the user */
    $user->roles()->sync([1,3]);

    return;
});



/*
 * Polymorphic Relations, pivot table is not used
 * */

Route::get('/user/photos',function(){

    $users = User::all();

    foreach($users as $user){
        foreach($user->photos as $photo){
            echo "<li>". $photo->file_path. "</li>";
        }
    }

    return;

});

Route::get('user/{id}/photos',function($id){

    $user = User::find($id);

    foreach($user->photos as $photo){
        echo "<li>". $photo->file_path. "</li>";
    }

    return;
});

Route::get('user/{id}/createphoto', function($id){

    $user = User::find($id);

    $user->photos()->create(['file_path'=>'hatron4.jpg']);

    return;
});

Route::get('user/{id}/updatephoto', function($id){

    $user = User::findOrFail($id);

    //$user->photos()->where('file_path','=','hatron4.jpg')->first()->update(['file_path'=>'hatron5.jpg']);
    $photo = $user->photos()->where('file_path','=','hatron4.jpg')->first();

    $photo->file_path = "hatron5.jpg";

    $photo->save();

    return;
});

Route::get('user/{id}/deletephoto', function($id){

    $user = User::findOrFail($id);

    $user->photos()->where('file_path','=','hatron5.jpg')->first()->delete();

    return;
});

Route::get('user/{uid}/assignphoto/{pid}', function($uid,$pid){

    $user = User::findOrFail($uid);

    $photo = Photo::findOrFail($pid);

    $user->photos()->save($photo);

    return;
});


Route::get('post/{id}/photos',function($id){

    $post = Post::find($id);

    foreach($post->photos as $photo){
        echo "<li>". $photo->file_path. "</li>";
    }

    return;
});

Route::get('photo/{id}/owner',function($id){

    $photo = Photo::findOrFail($id);

    return $photo->image_object->name;

});


/*
 * Polymorphic Relations using pivot table
 * */

Route::get('/post/tag',function(){
    $post = Post::findOrFail(4);

    foreach($post->tags as $tag){
        echo $tag->name;
    }

    return;
});

Route::get('/tag/post', function(){
    $tag = Tag::findOrFail(2);

    foreach($tag->posts as $post) {
        echo $post->title;
    }

    return;
});


Route::get('/createtagforpost/{id}', function($id){
    $post = Post::findOrFail($id);

    $tag = Tag::findOrFail(1);

    $post->tags()->save($tag);

    return;
});


Route::get('/createvideo', function(){
    $video = Video::create(['title'=>'marq.mov']);

    $tag = Tag::findOrFail(2);

    $video->tags()->save($tag);

    return;
});

Route::get('/updatetagofpost/{id}',function($id){
    $post = Post::findOrFail($id);

    foreach($post->tags as $tag){
        $tag->where('name','=','PHP')->first()->update(['name'=>'PHP tutorial']);
    }

    /*
    * Some ways to update pivot table of a post
    */
    //$tag = Tag::find(3);
    //$post->tags()->save($tag);
    //$post->tags()->attach($tag);
    //$post->tags()->sync([1,2]);

    return;
});

/*
*  Deleting from tag table by post id
* TRICK:
* This does not delete the related record from the pivot table, so this will create orphan records
*/
Route::get('/deletetagofpost/{id}', function($id){
    $posts = Post::all();

    foreach($posts as $post){
        foreach($post->tags as $tag){
            $tag->where('name','=','To be deleted')->delete();
        }
    }

    return;
});


/*
 *
 * Date Example
 *
 * */


Route::get('/dates', function(){
    $date = new DateTime('+1 week');


    echo $date->format('Y-m-d H:i:s.u');

    echo '<br>';

    echo Carbon::now(new DateTimeZone('Australia/Sydney'))->format('Y-m-d H:i:s.u');

    echo '<br>';

    $t = microtime(true);
    $micro = sprintf("%06d",($t - floor($t)) * 1000000);
    $date = Carbon::now(new DateTimeZone('Australia/Sydney'))->format('Y-m-d H:i:s').".".$micro;

    echo $date;

    return;
});

/*
 *  Accessor Example
 *
 *  Remark: Add getTitleAttribute($value) to Post
 *
 * */

Route::get('/accessor', function(){
    $post = Post::find(3);

    return $post->title;
});


/*
 *  Mutator Example
 *
 *  Remark: Add setTitleAttribute($value) to Post
 *
 * */

Route::get('/mutator', function(){
    $post = Post::find(3);

    $post->title = "relation post";

    $post->save();

    return;
});

Route::auth();

Route::get('/home', 'HomeController@index');

Route::get('/goadmin', 'AdminController@index');
