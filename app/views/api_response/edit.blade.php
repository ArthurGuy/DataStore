

{{ Form::open(array('route' => ['apiresponse.update', $response['id']], 'method'=>'PUT')) }}

    <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', $response['name'], array('class'=>'form-control')) }}
        {{ $errors->first('name', '<span class="help-block">:message</span>') }}
    </div>

    <div class="form-group {{ $errors->has('response') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('response', 'Response') }}
        {{ Form::text('response', $response['response'], array('class'=>'form-control')) }}
        {{ $errors->first('response', '<span class="help-block">:message</span>') }}
    </div>


    {{ Form::submit('Save', array('class'=>'btn btn-primary')) }}


{{ Form::close() }}