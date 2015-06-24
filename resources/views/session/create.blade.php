@extends('layouts.main')

@section('content')


    {!! Form::open(array('route' => 'session.store')) !!}

<div class="form-group {!! $errors->has('username') ? 'has-error has-feedback' : '' !!}">
    {!! Form::label('username', 'Username') !!}
    {!! Form::text('username', null, ['class'=>'form-control']) !!}
    {!! $errors->first('username', '<span class="help-block">:message</span>') !!}
</div>

<div class="form-group {!! $errors->has('password') ? 'has-error has-feedback' : '' !!}">
    {!! Form::label('password', 'Password') !!}
    {!! Form::password('password', ['class'=>'form-control']) !!}
    {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
</div>



{!! Form::submit('Login', array('class'=>'btn btn-primary')) !!}


{!! Form::close() !!}

@stop