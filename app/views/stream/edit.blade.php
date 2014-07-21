

{{ Form::open(array('route' => array('stream.update', $stream['id']), 'method'=>'PUT')) }}

<div class="form-group">
    {{ Form::label('name', 'Name') }}
    {{ Form::text('name', $stream['name'], array('class'=>'form-control')) }}
</div>


<div class="form-group">
    {{ Form::label('fields', 'Fields') }}
    {{ Form::textarea('fields', $stream['fields'], array('class'=>'form-control')) }}
</div>

<div class="form-group">
    {{ Form::label('tags', 'Tags') }}
    {{ Form::text('tags', implode(',',$stream['tags']), array('class'=>'form-control')) }}
</div>



{{ Form::submit('Save', array('class'=>'btn btn-primary')) }}


{{ Form::close() }}