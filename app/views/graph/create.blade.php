

{{ Form::open(array('route' => 'graph.store')) }}

    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', null, array('class'=>'form-control')) }}
    </div>


    <div class="form-group">
        {{ Form::label('streamId', 'Stream') }}
        {{ Form::select('streamId', $streamDropdown, null, array('class'=>'form-control')) }}
    </div>



    {{ Form::submit('Save', array('class'=>'btn btn-primary')) }}


{{ Form::close() }}