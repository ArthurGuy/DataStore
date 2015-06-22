@extends('layouts.main')

@section('content')

    <h1>Create A New Trigger</h1>

    {{ Form::open(array('route' => 'trigger.store')) }}

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

    <div class="form-group">
        <div class="row">
            <div class="col-xs-6 {{ $errors->has('check_field') ? 'has-error has-feedback' : '' }}">
                {{ Form::label('check_field', 'Check Field') }}
                {{ Form::select('check_field', [], Input::old('check_field'), array('class'=>'form-control', 'data-existing'=>Input::old('check_field'))) }}
                {{ $errors->first('check_field', '<span class="help-block">:message</span>') }}
            </div>
            <div class="col-xs-1 {{ $errors->has('check_operator') ? 'has-error has-feedback' : '' }}">
                {{ Form::label('check_operator', 'Operator') }}
                {{ Form::select('check_operator', $operators, null, array('class'=>'form-control')) }}
                {{ $errors->first('check_operator', '<span class="help-block">:message</span>') }}
            </div>
            <div class="col-xs-5 {{ $errors->has('check_value') ? 'has-error has-feedback' : '' }}">
                {{ Form::label('check_value', 'Comparison') }}
                {{ Form::text('check_value', null, array('class'=>'form-control')) }}
                {{ $errors->first('check_value', '<span class="help-block">:message</span>') }}
            </div>
        </div>
    </div>

    <div class="form-group ">
        <div class="row">
            <div class="col-xs-6 {{ $errors->has('filter_field') ? 'has-error has-feedback' : '' }}">
                {{ Form::label('filter_field', 'Filter Field') }}
                {{ Form::select('filter_field', [], Input::old('filter_field'), array('class'=>'form-control', 'data-existing'=>Input::old('filter_field'))) }}
                {{ $errors->first('filter_field', '<span class="help-block">:message</span>') }}
            </div>
            <div class="col-xs-6 {{ $errors->has('filter_value') ? 'has-error has-feedback' : '' }}">
                {{ Form::label('filter_value', 'Filter') }}
                {{ Form::text('filter_value', null, array('class'=>'form-control')) }}
                {{ $errors->first('filter_value', '<span class="help-block">:message</span>') }}
            </div>
        </div>
    </div>

    <div class="form-group {{ $errors->has('action') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('action', 'Action') }}
        {{ Form::select('action', [''=>'']+$triggerActions, null, array('class'=>'form-control')) }}
        {{ $errors->first('action', '<span class="help-block">:message</span>') }}
    </div>

    <h4>Push Message</h4>

    <div class="form-group well">
        <div class="row">
            <div class="col-xs-6 {{ $errors->has('push_subject') ? 'has-error has-feedback' : '' }}">
                {{ Form::label('push_subject', 'Push Subject') }}
                {{ Form::text('push_subject', null, array('class'=>'form-control')) }}
                {{ $errors->first('push_subject', '<span class="help-block">:message</span>') }}
            </div>
            <div class="col-xs-6 {{ $errors->has('push_message') ? 'has-error has-feedback' : '' }}">
                {{ Form::label('push_message', 'Push Message') }}
                {{ Form::text('push_message', null, array('class'=>'form-control')) }}
                {{ $errors->first('push_message', '<span class="help-block">:message</span>') }}
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 {{ $errors->has('push_when') ? 'has-error has-feedback' : '' }}">
                {{ Form::label('push_when', 'Send message when') }}
                {{ Form::select('push_when', $pushWhenOptions, null, array('class'=>'form-control')) }}
                {{ $errors->first('push_when', '<span class="help-block">:message</span>') }}
            </div>
        </div>
    </div>

    <h4>Variable</h4>

    <div class="form-group well">
        <div class="row">
            <div class="col-xs-6 {{ $errors->has('variable_name') ? 'has-error has-feedback' : '' }}">
                {{ Form::label('variable_name', 'Variable Name') }}
                {{ Form::select('variable_name', [''=>'']+$variables, null, array('class'=>'form-control')) }}
                {{ $errors->first('variable_name', '<span class="help-block">:message</span>') }}
            </div>
            <div class="col-xs-6 {{ $errors->has('variable_value') ? 'has-error has-feedback' : '' }}">
                {{ Form::label('variable_value', 'Variable Value') }}
                {{ Form::text('variable_value', null, array('class'=>'form-control')) }}
                {{ $errors->first('variable_value', '<span class="help-block">:message</span>') }}
            </div>
        </div>
    </div>

    <h4>Nest</h4>

    <div class="form-group well">
        <div class="row">
            <div class="col-xs-6 {{ $errors->has('nest_api_key') ? 'has-error has-feedback' : '' }}">
                {{ Form::label('nest_api_key', 'Nest API Key') }}
                {{ Form::text('nest_api_key', null, array('class'=>'form-control')) }}
                {{ $errors->first('nest_api_key', '<span class="help-block">:message</span>') }}
            </div>
            <div class="col-xs-6 {{ $errors->has('nest_structure') ? 'has-error has-feedback' : '' }}">
                {{ Form::label('nest_structure', 'Nest Structure ID') }}
                {{ Form::text('nest_structure', null, array('class'=>'form-control')) }}
                {{ $errors->first('nest_structure', '<span class="help-block">:message</span>') }}
            </div>
            <div class="col-xs-6 {{ $errors->has('nest_property') ? 'has-error has-feedback' : '' }}">
                {{ Form::label('nest_property', 'Nest Property') }}
                {{ Form::text('nest_property', null, array('class'=>'form-control')) }}
                {{ $errors->first('nest_property', '<span class="help-block">:message</span>') }}
            </div>
            <div class="col-xs-6 {{ $errors->has('nest_value') ? 'has-error has-feedback' : '' }}">
                {{ Form::label('nest_value', 'Nest Value') }}
                {{ Form::text('nest_value', null, array('class'=>'form-control')) }}
                {{ $errors->first('nest_value', '<span class="help-block">:message</span>') }}
            </div>
        </div>
    </div>

    <h4>Location</h4>

    <div class="form-group well">
        <div class="row">
            <div class="col-xs-6 {{ $errors->has('location_id') ? 'has-error has-feedback' : '' }}">
                {{ Form::label('location_id', 'Location Name') }}
                {{ Form::select('location_id', [''=>'']+$locations, null, array('class'=>'form-control')) }}
                {{ $errors->first('location_id', '<span class="help-block">:message</span>') }}
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

                        var $filterDropdown = $("#filter_field");
                        $filterDropdown.empty();
                        $filterDropdown.append($("<option value=\"\"></option>"));
                        var selected = '';
                        if ($filterDropdown.attr('data-existing') == streams[i].filter_field) {
                            selected = 'selected="selected"';
                        }
                        $filterDropdown.append($("<option value=\""+streams[i].filter_field+"\" "+selected+">"+streams[i].filter_field+"</option>"));


                        for (var x in streams[i].fields) {
                            var selected = '';
                            if ($checkField.attr('data-existing') == streams[i].fields[x]) {
                                selected = 'selected="selected"';
                            }
                            $checkField.append($("<option value=\""+streams[i].fields[x]+"\" "+selected+">"+streams[i].fields[x]+"</option>"));
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

@stop