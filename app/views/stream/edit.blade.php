

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


{{ Form::submit('Save', array('class'=>'btn btn-primary')) }}


{{ Form::close() }}