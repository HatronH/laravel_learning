<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRelatedRequest;
use Illuminate\Http\Request;
use App\Post;


class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        //

        //$posts = Post::all();
        $posts = Post::descending();
        //$posts = Post::orderBy('id','asc')->get();

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRelatedRequest $request)
    {
//        $this->validate($request, [
//            'title'=>'required|max:5',
//            'content'=>'required'
//        ]);

        //
        //return $request->all();
        //return $request->get('title');
        //return $request->title;

        Post::create($request->all());

        //$post = new Post;
        //$post->title = $request->title;
        //$post->content = 'content is something else';
        //$post->save();

        return redirect('/posts');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $post = Post::findOrFail($id);

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

        $post = Post::findOrFail($id);

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $post = Post::findOrFail($id);

        $post->update($request->all());

//        $post->title = $request->title;
//        $post->content = $request->content;
//        $post->save();

        return redirect('posts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $post = Post::findOrFail($id);

        $post->delete();

//        $post->title = $request->title;
//        $post->content = $request->content;
//        $post->save();

        return redirect('posts');


    }


    public function show_post() {
        $titles = array();
        $results = \DB::select('select * from posts where id > ?',[1]);

        foreach($results as $post) {
            $titles[] = $post->title;
        }

        //$titles = ['abc','def','ghi'];
        return view('readpost', compact('titles'));
    }

}
