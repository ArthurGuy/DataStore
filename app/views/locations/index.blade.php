@extends('layouts.main')

@section('content')
<div class="page-header">
    <h1>Locations</h1>
</div>

<table class="table table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th>Temp</th>
            <th>Humidity</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach ($locations as $location)
        <tr>
            <td>{{ $location['name'] }}</td>
            <td>{{ $location['temperature'] }}</td>
            <td>{{ $location['humidity'] }}</td>
            <td>
                {{ Form::open(array('route' => array('locations.destroy', $location['id']), 'method'=>'DELETE')) }}

                <a href="{{ route('locations.edit', $location['id']) }}" class="btn btn-sm btn-default">Edit</a> |
                {{ Form::submit('Delete', array('class'=>'btn btn-danger btn-sm')) }}

                {{ Form::close() }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<a href="{{ route('locations.create') }}" class="btn btn-info">Create</a>
@stop