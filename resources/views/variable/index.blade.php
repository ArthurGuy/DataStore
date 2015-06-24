@extends('layouts.main')

@section('content')

    <div class="page-header">
    <h1>Variables</h1>
</div>

<table class="table table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th>Value</th>
            <th>Type</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach ($variables as $variable)
        <tr>
            <td>{!! $variable['name'] !!}</td>
            <td>{!! $variable['value'] !!}</td>
            <td>{!! $variable['type'] !!}</td>
            <td>
                {!! Form::open(array('route' => array('variable.destroy', $variable['id']), 'method'=>'DELETE')) !!}

                <a href="{!! route('variable.edit', $variable['id']) !!}" class="btn btn-sm btn-default">Edit</a> |
                {!! Form::submit('Delete', array('class'=>'btn btn-danger btn-sm')) !!}

                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<a href="{!! route('variable.create') !!}" class="btn btn-info">Create</a>

@stop