@extends('layouts.app')
@section('content')
    <h1>Create Post</h1>

    {{--<form method="post" action="/posts">--}}
    {!! Form::open(['method'=>'POST','action'=>'PostsController@store']) !!}

        <div class="form-group">
            {!! Form::label('title','Title:') !!}
            {!! Form::text('title', null, ['class'=>'form-control']) !!}
            <br>
            {!! Form::label('content','Content:') !!}
            {!! Form::text('content', null, ['class'=>'form-control']) !!}
        </div>
        <br>
        <div class="form-group">
            {!! Form::submit('Create Post', ['class'=>'btn btn-primary']) !!}
        </div>
    {{--
        <input type="text" name="title" placeholder="Enter title here" />
        <input type="text" name="content" placeholder="Enter content here" />
        <input type="submit" name="submit">
        {{csrf_field()}}
    --}}

    {!! Form::close() !!}

    @if(count($errors)>0)
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endsection
