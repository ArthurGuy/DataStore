<h1>Trigger</h1>

{{ Form::open(array('route' => ['trigger.update', $trigger['id']], 'method'=>'PUT')) }}

<div class="form-group {{ $errors->has('name') ? 'has-error has-feedback' : '' }}">
    {{ Form::label('name', 'Name') }}
    {{ Form::text('name', $trigger['name'], array('class'=>'form-control')) }}
    {{ $errors->first('name', '<span class="help-block">:message</span>') }}
</div>


<div class="form-group {{ $errors->has('streamId') ? 'has-error has-feedback' : '' }}">
    {{ Form::label('streamId', 'Stream') }}
    {{ Form::select('streamId', [""]+$streamDropdown, $trigger['streamId'], array('class'=>'form-control')) }}
    {{ $errors->first('streamId', '<span class="help-block">:message</span>') }}
</div>

<div class="form-group">
    <div class="row">
        <div class="col-xs-6 {{ $errors->has('check_field') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('check_field', 'Check Field') }}
            {{ Form::select('check_field', [], Input::old('check_field', $trigger['check_field']), array('class'=>'form-control', 'data-existing'=>Input::old('check_field', $trigger['check_field']))) }}
            {{ $errors->first('check_field', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-xs-1 {{ $errors->has('check_operator') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('check_operator', 'Operator') }}
            {{ Form::select('check_operator', $operators, $trigger['check_operator'], array('class'=>'form-control')) }}
            {{ $errors->first('check_operator', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-xs-5 {{ $errors->has('check_value') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('check_value', 'Comparison') }}
            {{ Form::text('check_value', $trigger['check_value'], array('class'=>'form-control')) }}
            {{ $errors->first('check_value', '<span class="help-block">:message</span>') }}
        </div>
    </div>
</div>

<div class="form-group ">
    <div class="row">
        <div class="col-xs-6 {{ $errors->has('filter_field') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('filter_field', 'Filter Field') }}
            {{ Form::select('filter_field', [], Input::old('filter_field', $trigger['filter_field']), array('class'=>'form-control', 'data-existing'=>Input::old('filter_field', $trigger['filter_field']))) }}
            {{ $errors->first('filter_field', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-xs-6 {{ $errors->has('filter_value') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('filter_value', 'Filter') }}
            {{ Form::text('filter_value', $trigger['filter_value'], array('class'=>'form-control')) }}
            {{ $errors->first('filter_value', '<span class="help-block">:message</span>') }}
        </div>
    </div>
</div>

<div class="form-group {{ $errors->has('action') ? 'has-error has-feedback' : '' }}">
    {{ Form::label('action', 'Action') }}
    {{ Form::select('action', [''=>'']+$triggerActions, $trigger['action'], array('class'=>'form-control')) }}
    {{ $errors->first('action', '<span class="help-block">:message</span>') }}
</div>

<div class="form-group">
    <div class="row">
        <div class="col-xs-6 {{ $errors->has('push_subject') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('push_subject', 'Push Subject') }}
            {{ Form::text('push_subject', $trigger['push_subject'], array('class'=>'form-control')) }}
            {{ $errors->first('push_subject', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-xs-6 {{ $errors->has('push_message') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('push_message', 'Push Message') }}
            {{ Form::text('push_message', $trigger['push_message'], array('class'=>'form-control')) }}
            {{ $errors->first('push_message', '<span class="help-block">:message</span>') }}
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-xs-6 {{ $errors->has('variable_name') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('variable_name', 'Variable Name') }}
            {{ Form::text('variable_name', $trigger['variable_name'], array('class'=>'form-control')) }}
            {{ $errors->first('variable_name', '<span class="help-block">:message</span>') }}
        </div>
        <div class="col-xs-6 {{ $errors->has('variable_value') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('variable_value', 'Variable Value') }}
            {{ Form::text('variable_value', $trigger['variable_value'], array('class'=>'form-control')) }}
            {{ $errors->first('variable_value', '<span class="help-block">:message</span>') }}
        </div>
    </div>
</div>


    {{ Form::submit('Save', array('class'=>'btn btn-primary')) }}

    <script>

        var streams = {{ json_encode($streams) }};

        function updateFieldDropdown()
        {
            for(var i in streams) {
                if (streams[i].id == $("#streamId").find(":selected").val()) {

                    var $checkField = $("#check_field");
                    $checkField.empty();
                    $checkField.append($("<option value=\"\"></option>"));

                    var $filterField = $("#filter_field");
                    $filterField.empty();
                    $filterField.append($("<option value=\"\"></option>"));
                    for (var x in streams[i].fields) {
                        var selected = '';
                        if (streams[i].fields[x].type == 'data') {
                            if ($checkField.attr('data-existing') == streams[i].fields[x].key) {
                                selected = 'selected="selected"';
                            }
                            $checkField.append($("<option value=\""+streams[i].fields[x].key+"\" "+selected+">"+streams[i].fields[x].name+"</option>"));
                        } else if (streams[i].fields[x].type == 'filter') {
                            if ($filterField.attr('data-existing') == streams[i].fields[x].key) {
                                selected = 'selected="selected"';
                            }
                            $filterField.append($("<option value=\""+streams[i].fields[x].key+"\" "+selected+">"+streams[i].fields[x].name+"</option>"));
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