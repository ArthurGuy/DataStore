

{{ Form::open(array('route' => 'graph.store')) }}

    <div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', null, array('class'=>'form-control')) }}
        {{ $errors->first('name', '<span class="help-block">:message</span>') }}
    </div>


    <div class="form-group {{ $errors->has('streamId') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('streamId', 'Stream') }}
        {{ Form::select('streamId', [""]+$streamDropdown, null, array('class'=>'form-control')) }}
        {{ $errors->first('streamId', '<span class="help-block">:message</span>') }}
    </div>

    <div class="form-group {{ $errors->has('field') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('field', 'Field') }}
        {{ Form::select('field', [], null, array('class'=>'form-control')) }}
        {{ $errors->first('field', '<span class="help-block">:message</span>') }}
    </div>

    <div class="form-group {{ $errors->has('filter_field') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('filter_field', 'Filter') }}
        <div class="row">
            <div class="col-xs-6">
                {{ Form::select('filter_field', [], null, array('class'=>'form-control')) }}
                {{ $errors->first('filter_field', '<span class="help-block">:message</span>') }}
            </div>
            <div class="col-xs-6">
                {{ Form::text('filter', null, array('class'=>'form-control')) }}
                {{ $errors->first('filter', '<span class="help-block">:message</span>') }}
            </div>
        </div>
    </div>

    <div class="form-group {{ $errors->has('time_period') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('time_period', 'Time Period') }}
        {{ Form::select('time_period', $timePeriods, null, array('class'=>'form-control')) }}
        {{ $errors->first('time_period', '<span class="help-block">:message</span>') }}
    </div>


    {{ Form::submit('Save', array('class'=>'btn btn-primary')) }}

    <script>

        var streams = {{ json_encode($streams) }};

        function updateFieldDropdown()
        {
            for(var i in streams) {
                if (streams[i].id == $("#streamId").find(":selected").val()) {
                    console.log(streams[i].fields);

                    $("#field").empty();
                    $("#filter_field").empty();
                    for (var x in streams[i].fields) {
                        //console.log(streams[i].fields[x]);
                        if (streams[i].fields[x].type == 'data') {
                            $("#field").append($("<option value=\""+streams[i].fields[x].key+"\">"+streams[i].fields[x].name+"</option>"));
                        } else if (streams[i].fields[x].type == 'filter') {
                            $("#filter_field").append($("<option value=\""+streams[i].fields[x].key+"\">"+streams[i].fields[x].name+"</option>"));
                        }
                    }

                }
            }
        }

        updateFieldDropdown();

        $("#streamId").change(function() {
            updateFieldDropdown();
        });

    </script>

{{ Form::close() }}