@extends('layouts.app')
@section('content')
    <h1>Read Post</h1>

    @if (count($titles))
        @foreach($titles as $title)
            <li>{{$title}}</li>
        @endforeach
    @endif

@stop