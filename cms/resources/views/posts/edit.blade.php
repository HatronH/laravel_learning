@extends('layouts.app')
@section('content')

    <h1>Edit Post</h1>

    {!! Form::model($post, ['method'=>'PATCH','action'=>['PostsController@update', $post->id]]) !!}

    <div class="form-group">
        {!! Form::label('title','Title:') !!}
        {!! Form::text('title', null, ['class'=>'form-control']) !!}
        <br>
        {!! Form::label('content','Content:') !!}
        {!! Form::text('content', null, ['class'=>'form-control']) !!}
    </div>
    <br>
    <div class="form-group">
        {!! Form::submit('Update Post', ['class'=>'btn btn-info']) !!}
    </div>

    {!! Form::close() !!}

    {!! Form::open(['method'=>'DELETE','action'=>['PostsController@destroy', $post->id]]) !!}

    {!! Form::submit('Delete Post', ['class'=>'btn btn-delete']) !!}

    {!! Form::close() !!}


    {{--
    <form method="post" action="/posts/{{$post->id}}">
        {{csrf_field()}}
        <input type="hidden" name="_method" value="PUT" />
        <input type="text" name="title" placeholder="Enter title here" value="{{$post->title}}"/>
        <input type="text" name="content" placeholder="Enter content here" value="{{$post->content}}" />
        <input type="submit" name="submit" />
    </form>

    <form method="post" action="/posts/{{$post->id}}">
        {{csrf_field()}}
        <input type="hidden" name="_method" value="DELETE" />
        <input type="submit" value="Delete" />
    </form>
    --}}

@endsection
