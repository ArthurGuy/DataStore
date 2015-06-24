@extends('layouts.main')

@section('content')

    @foreach($locations as $location)
        <a href="{{ route('dashboard.view', [$location->id]) }}">{{ $location->name }}</a>
    @endforeach

@stop