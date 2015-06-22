@extends('layouts.main')

@section('content')

    <h1>Edit a Location</h1>

    {{ Form::open(array('route' => ['locations.update', $location['id']], 'method'=>'PUT')) }}

    <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', $location['name'], array('class'=>'form-control')) }}
        {{ $errors->first('name', '<span class="help-block">:message</span>') }}
    </div>

    <div class="form-group {{ $errors->has('type') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('type', 'Type') }}
        {{ Form::select('type', [""=>""]+$locationTypes, $location['type'], array('class'=>'form-control')) }}
        {{ $errors->first('type', '<span class="help-block">:message</span>') }}
    </div>

    <div class="form-group {{ $errors->has('postcode') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('postcode', 'Post Code') }}
        {{ Form::text('postcode', $location['postcode'], array('class'=>'form-control')) }}
        {{ $errors->first('postcode', '<span class="help-block">:message</span>') }}
    </div>

    <div class="form-group {{ $errors->has('country') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('country', 'Country') }}
        {{ Form::text('country', $location['country'], array('class'=>'form-control')) }}
        {{ $errors->first('country', '<span class="help-block">:message</span>') }}
    </div>

    {{ Form::submit('Update', array('class'=>'btn btn-primary')) }}

    {{ Form::close() }}

@stop