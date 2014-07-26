<h1>Create a Variable</h1>

{{ Form::open(array('route' => 'variable.store')) }}

    <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', null, array('class'=>'form-control')) }}
    </div>


    <div class="form-group {{ $errors->has('value') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('value', 'Value') }}
        {{ Form::text('value', null, array('class'=>'form-control')) }}
    </div>


    <div class="form-group {{ $errors->has('type') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('type', 'Type') }}
        {{ Form::select('type', [""=>""]+$variableTypes, null, array('class'=>'form-control')) }}
        {{ $errors->first('type', '<span class="help-block">:message</span>') }}
    </div>



    {{ Form::submit('Save', array('class'=>'btn btn-primary')) }}


{{ Form::close() }}