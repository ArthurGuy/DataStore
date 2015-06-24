@extends('layouts.main')

@section('content')

    <h1>Create a Location</h1>

    {!! Form::open(array('route' => 'locations.store')) !!}

    <div class="form-group {!! $errors->has('name') ? 'has-error has-feedback' : '' !!}">
        {!! Form::label('name', 'Name') !!}
        {!! Form::text('name', null, array('class'=>'form-control')) !!}
        {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
    </div>

    <div class="form-group {!! $errors->has('type') ? 'has-error has-feedback' : '' !!}">
        {!! Form::label('type', 'Type') !!}
        {!! Form::select('type', [""=>""]+$locationTypes, null, array('class'=>'form-control')) !!}
        {!! $errors->first('type', '<span class="help-block">:message</span>') !!}
    </div>

    <div class="form-group {!! $errors->has('postcode') ? 'has-error has-feedback' : '' !!}">
        {!! Form::label('postcode', 'Post Code') !!}
        {!! Form::text('postcode', null, array('class'=>'form-control')) !!}
        {!! $errors->first('postcode', '<span class="help-block">:message</span>') !!}
    </div>

    <div class="form-group {!! $errors->has('country') ? 'has-error has-feedback' : '' !!}">
        {!! Form::label('country', 'Country') !!}
        {!! Form::text('country', 'GB', array('class'=>'form-control')) !!}
        {!! $errors->first('country', '<span class="help-block">:message</span>') !!}
    </div>

    {!! Form::submit('Save', array('class'=>'btn btn-primary')) !!}

    {!! Form::close() !!}
@stop