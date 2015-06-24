@extends('layouts.main')

@section('content')


    <div class="page-header">
    <h1>API Responses</h1>
</div>

<table class="table table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th>String</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach ($responses as $response)
        <tr>
            <td><a href="{{ route('apiresponse.show', $response['id']) }}" class="btn btn-primary">{{ $response['name'] }}</a></td>
            <td>{{ $response['response'] }}</td>
            <td>
                {{ Form::open(array('route' => array('apiresponse.destroy', $response['id']), 'method'=>'DELETE')) }}

                <a href="{{ route('apiresponse.edit', $response['id']) }}" class="btn btn-sm btn-default">Edit</a> |
                {{ Form::submit('Delete', array('class'=>'btn btn-danger btn-sm')) }}

                {{ Form::close() }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<a href="{{ route('apiresponse.create') }}" class="btn btn-info">Create</a>

@stop