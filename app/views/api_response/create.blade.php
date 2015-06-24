@extends('layouts.main')

@section('content')


    {{ Form::open(array('route' => 'apiresponse.store')) }}

    <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', null, array('class'=>'form-control')) }}
        {{ $errors->first('name', '<span class="help-block">:message</span>') }}
    </div>

    <div class="form-group {{ $errors->has('response') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('response', 'Response') }}
        {{ Form::text('response', null, array('class'=>'form-control')) }}
        {{ $errors->first('response', '<span class="help-block">:message</span>') }}
    </div>


    {{ Form::submit('Save', array('class'=>'btn btn-primary')) }}


{{ Form::close() }}

@stop