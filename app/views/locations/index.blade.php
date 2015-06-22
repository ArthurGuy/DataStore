@extends('layouts.main')

@section('content')
<div class="page-header">
    <h1>Locations</h1>
</div>

<table class="table table-hover">
    <thead>
        <tr>
            <th>Building</th>
            <th>Room</th>
            <th>Temp</th>
            <th>Humidity</th>
            <th>Home</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach ($locations as $location)
        <tr>
            <td>{{ $location['name'] }}</td>
            <td></td>
            <td>{{ $location['temperature'] }}</td>
            <td>{{ $location['humidity'] }}</td>
            <td>{{ $location['home'] }}</td>
            <td>
                {{ Form::open(array('route' => array('locations.destroy', $location['id']), 'method'=>'DELETE')) }}

                <a href="{{ route('locations.edit', $location['id']) }}" class="btn btn-sm btn-default">Edit</a> |
                {{ Form::submit('Delete', array('class'=>'btn btn-danger btn-sm')) }}

                {{ Form::close() }}
            </td>
        </tr>

        @foreach ($location->rooms() as $room)

            <tr class="active">
                <td></td>
                <td>{{ $room['name'] }}</td>
                <td>{{ $room['temperature'] }}</td>
                <td>{{ $room['humidity'] }}</td>
                <td>{{ $room['home'] }}</td>
                <td>
                    {{ Form::open(array('route' => array('locations.destroy', $room['id']), 'method'=>'DELETE')) }}

                    <a href="{{ route('locations.edit', $room['id']) }}" class="btn btn-sm btn-default">Edit</a> |
                    {{ Form::submit('Delete', array('class'=>'btn btn-danger btn-sm')) }}

                    {{ Form::close() }}
                </td>
            </tr>

        @endforeach

    @endforeach
    </tbody>
</table>
<a href="{{ route('locations.create') }}" class="btn btn-info">Create</a>
@stop