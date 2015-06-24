@extends('layouts.main')

@section('content')


    <div class="page-header">
    <h1>Triggers</h1>
</div>

<table class="table table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach ($triggers as $trigger)
        <tr>
            <td><a href="{{ route('trigger.show', $trigger['id']) }}" class="btn btn-primary">{{ $trigger['name'] }}</a></td>
            <td>
                {!! Form::open(array('route' => array('trigger.destroy', $trigger['id']), 'method'=>'DELETE')) !!}

                <a href="{{ route('trigger.edit', $trigger['id']) }}" class="btn btn-sm btn-default">Edit</a> |
                {!! Form::submit('Delete', array('class'=>'btn btn-danger btn-sm')) !!}

                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<a href="{{ route('trigger.create') }}" class="btn btn-info">Create</a>

@stop