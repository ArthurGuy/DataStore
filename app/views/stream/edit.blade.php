

{{ Form::open(array('route' => array('stream.update', $stream['id']), 'method'=>'PUT')) }}

<div class="form-group">
    {{ Form::label('name', 'Name') }}
    {{ Form::text('name', $stream['name'], array('class'=>'form-control')) }}
</div>


<div class="form-group">
    {{ Form::label('fields', 'Fields') }}
    {{ Form::textarea('fields', json_encode($stream['fields'], JSON_PRETTY_PRINT), array('class'=>'form-control')) }}
    <span class="help-block">An array of json objects containing key, name and type. Types: data, filter, text</span>
</div>



{{ Form::submit('Save', array('class'=>'btn btn-primary')) }}


{{ Form::close() }}