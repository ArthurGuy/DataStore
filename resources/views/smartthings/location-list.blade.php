@extends('layouts.main')

@section('content')

    <ul>
    @foreach($locations as $location)
        <li>
            {{ $location->name }}
            <ul>
                <li><a href="{{ route('smartthings.connect', ['locationId' => $location->id, 'type' =>'temp']) }}">Temperature</a></li>
                <li><a href="{{ route('smartthings.connect', ['locationId' => $location->id, 'type' =>'movement']) }}">Movement</a></li>
            </ul>

        </li>
    @endforeach
    </ul>
@stop