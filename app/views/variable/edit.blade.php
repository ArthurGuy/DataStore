<h1>Edit a Variable</h1>

{{ Form::open(array('route' => ['variable.update', $variable['id']], 'method'=>'PUT')) }}

    <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', $variable['name'], array('class'=>'form-control')) }}
    </div>


    <div class="form-group {{ $errors->has('value') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('value', 'Value') }}
        {{ Form::text('value', $variable['value'], array('class'=>'form-control')) }}
    </div>


    <div class="form-group {{ $errors->has('type') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('type', 'Type') }}
        {{ Form::select('type', [""=>""]+$variableTypes, $variable['type'], array('class'=>'form-control')) }}
        {{ $errors->first('type', '<span class="help-block">:message</span>') }}
    </div>



    {{ Form::submit('Update', array('class'=>'btn btn-primary')) }}


{{ Form::close() }}