

{{ Form::open(array('route' => array('stream.update', $stream['id']), 'method'=>'PUT')) }}

<div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
    {{ Form::label('name', 'Name') }}
    {{ Form::text('name', $stream['name'], array('class'=>'form-control')) }}
    {{ $errors->first('name', '<span class="help-block">:message</span>') }}
</div>


<div class="form-group {{ $errors->has('fields') ? 'has-error has-feedback' : '' }}">
    {{ Form::label('fields', 'Fields') }}
    {{ Form::text('fields', implode(", ",$stream['fields']), array('class'=>'form-control')) }}
    <span class="help-block">A comma seperated list of field names</span>
    {{ $errors->first('fields', '<span class="help-block">:message</span>') }}
</div>

<div class="form-group {{ $errors->has('filter_field') ? 'has-error has-feedback' : '' }}">
    {{ Form::label('filter_field', 'Filter Field') }}
    {{ Form::text('filter_field', $stream['filter_field'], array('class'=>'form-control')) }}
    <span class="help-block">Is there a filter for dividing this data or is it all the same</span>
    {{ $errors->first('filter_field', '<span class="help-block">:message</span>') }}
</div>

<div class="form-group {{ $errors->has('filter_field_names') ? 'has-error has-feedback' : '' }}">
    {{ Form::label('filter_field_names', 'Filter Field Lookup Names') }}
    {{ Form::textarea('filter_field_names', $stream['filter_field_names'], array('class'=>'form-control')) }}
    <span class="help-block">Display names for the possible filter values. value:Name</span>
    {{ $errors->first('filter_field_names', '<span class="help-block">:message</span>') }}
</div>

<div class="form-group {{ $errors->has('') ? 'has-error has-feedback' : '' }}">
    {{ Form::label('response_id', 'API Response') }}
    {{ Form::select('response_id', [''=>'']+$api_responses, $stream['response_id'], array('class'=>'form-control')) }}
    <span class="help-block">Is there a filter for dividing this data or is it the same</span>
    {{ $errors->first('response_id', '<span class="help-block">:message</span>') }}
</div>

{{ Form::submit('Save', array('class'=>'btn btn-primary')) }}


{{ Form::close() }}