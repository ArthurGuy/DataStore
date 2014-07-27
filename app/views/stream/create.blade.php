

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



    {{ Form::submit('Save', array('class'=>'btn btn-primary')) }}


{{ Form::close() }}