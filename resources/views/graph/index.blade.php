@extends('layouts.main')

@section('content')


    <div class="page-header">
    <h1>Data Graphs</h1>
</div>

<table class="table table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th>Plotting</th>
            <th>Filter</th>
            <th>Period</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach ($graphs as $graph)
        <tr>
            <td><a href="{{ route('graph.show', $graph['id']) }}" class="btn btn-primary">{{ $graph['name'] }}</a></td>
            <td>{{ $graph['field'] }}</td>
            <td>{{ $graph['filter_field'] }} = {{ $graph['filter'] }}</td>
            <td>{{ $timePeriods[$graph['time_period']] }}</td>
            <td>
                {!! Form::open(array('route' => array('graph.destroy', $graph['id']), 'method'=>'DELETE')) !!}

                <a href="{{ route('graph.edit', $graph['id']) }}" class="btn btn-sm btn-default">Edit</a> |
                {!! Form::submit('Delete', array('class'=>'btn btn-danger btn-sm')) !!}

                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<a href="{{ route('graph.create') }}" class="btn btn-info">Create</a>

@stop