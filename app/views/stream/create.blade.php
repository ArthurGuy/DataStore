

{{ Form::open(array('route' => 'stream.store')) }}

    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', null, array('class'=>'form-control')) }}
    </div>


    <div class="form-group">
        {{ Form::label('fields', 'Fields') }}
        {{ Form::text('fields', null, array('class'=>'form-control')) }}
        <span class="help-block">A comma separated list</span>
    </div>



    {{ Form::submit('Save', array('class'=>'btn btn-primary')) }}


{{ Form::close() }}