

{{ Form::open(array('route' => 'account.store')) }}

<div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
    {{ Form::label('name', 'Name') }}
    {{ Form::text('name', null, ['class'=>'form-control']) }}
    {{ $errors->first('name', '<span class="help-block">:message</span>') }}
</div>

<div class="form-group {{ $errors->has('username') ? 'has-error has-feedback' : '' }}">
    {{ Form::label('username', 'Username') }}
    {{ Form::text('username', null, ['class'=>'form-control']) }}
    {{ $errors->first('username', '<span class="help-block">:message</span>') }}
</div>

<div class="form-group {{ $errors->has('email') ? 'has-error has-feedback' : '' }}">
    {{ Form::label('email', 'Email') }}
    {{ Form::text('email', null, ['class'=>'form-control']) }}
    {{ $errors->first('email', '<span class="help-block">:message</span>') }}
</div>


<div class="form-group {{ $errors->has('password') ? 'has-error has-feedback' : '' }}">
    {{ Form::label('password', 'Password') }}
    {{ Form::password('password', ['class'=>'form-control']) }}
    {{ $errors->first('password', '<span class="help-block">:message</span>') }}
</div>



{{ Form::submit('Create', array('class'=>'btn btn-primary')) }}


{{ Form::close() }}