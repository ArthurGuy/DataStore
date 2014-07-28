

{{ Form::open(array('route' => 'stream.store')) }}

    <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', null, array('class'=>'form-control')) }}
        {{ $errors->first('name', '<span class="help-block">:message</span>') }}
    </div>


    <div class="form-group {{ $errors->has('fields') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('fields', 'Fields') }}
        {{ Form::text('fields', null, array('class'=>'form-control')) }}
        <span class="help-block">A comma separated list</span>
        {{ $errors->first('fields', '<span class="help-block">:message</span>') }}
    </div>

    <div class="form-group {{ $errors->has('filter_field') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('filter_field', 'Filter Field') }}
        {{ Form::text('filter_field', null, array('class'=>'form-control')) }}
        <span class="help-block">Is there a filter for dividing this data or is it the same</span>
        {{ $errors->first('filter_field', '<span class="help-block">:message</span>') }}
    </div>

    <div class="form-group {{ $errors->has('filter_field_names') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('filter_field_names', 'Filter Field Lookup Names') }}
        {{ Form::textarea('filter_field_names', null, array('class'=>'form-control')) }}
        <span class="help-block">Display names for the possible filter values</span>
        {{ $errors->first('filter_field_names', '<span class="help-block">:message</span>') }}
    </div>

    <div class="form-group {{ $errors->has('response_id') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('response_id', 'API Response') }}
        {{ Form::select('response_id', [''=>'']+$api_responses, null, array('class'=>'form-control')) }}
        <span class="help-block">Should a specific message be returned as part of the 200 response</span>
        {{ $errors->first('response_id', '<span class="help-block">:message</span>') }}
    </div>


    {{ Form::submit('Save', array('class'=>'btn btn-primary')) }}


{{ Form::close() }}