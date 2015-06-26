@extends('layouts.app')

@section('title')
{{ ucfirst($location->name) }} Dashboard
@stop

@section('content')

    <div id="dashboard" location="{{ $location->id }}">

        <h1 class="daySummary">{{ $dayWeather['daySummary'] }}</h1>


        <div style="display: flex; justify-content: center; flex-wrap: wrap; margin: 30px 0;">
            <div class="keyForcast">
                <canvas id="future-weather-icon" width="200" height="200" data-icon="{{ $futureForecast->icon }}"></canvas>
                <span class="summary hidden">{{ $futureForecast->summary }}</span>
                <span class="primaryTemp">{{ $outsideWeather['temperature'] }}°C</span>
                <span class="secondaryTemp">{{ $dayWeather['dayMinTemperature'] }}°C - {{ $dayWeather['dayMaxTemperature'] }}°C</span>
                <span class="condition">{{ $outsideWeather['condition'] }}</span>
            </div>
        </div>

        <div class="room-list">

            <room v-repeat="rooms"></room>

        </div>

        <h2 style="text-align: center">
        @if ($location->home)
            <span class="glyphicons glyphicons-home"></span> Home
        @else
            <span class="glyphicons glyphicons-person-running"></span> Away
        @endif
        </h2>

    </div>

<script id="room-template" type="x-template">
    <div v-class="heater-on : heater && heater.state != '0'" class="room">

        <div class="heading">

                    <span class="name">
                        @{{ name }}
                        <span v-if="hasWarning" class="glyphicons glyphicons-warning-sign" title="No Updates since @{{ last_updated }}" data-toggle="tooltip" data-placement="left" style="color:#FF7100;"></span>
                    </span>
            <span class="primaryTemp">@{{ temperature }}°C | @{{ humidity }}%</span>

        </div>

        <span class="condition">@{{ condition }}</span>

        <span class="heater-status">Heating to @{{ target_temperature }}°C</span>

        <div class="action-row">

            <div class="action">
                <span v-class="button-active : mode == 'auto'" v-on="click: modeToggle" class="button-icon glyphicons glyphicons-repeat mode-toggle"></span>
            </div>

            <div v-if="heater" class="action" data-device="@{{ heater.id }}" data-state="@{{ heater.state }}">
                <span v-class="button-active : heater.state != '0'" v-on="click: heaterToggle" class="button-icon glyphicons glyphicons-heat device-toggle"></span>
            </div>

            <div class="action" data-device="@{{ cooler.id} }}" data-state="@{{ cooler.state }}" v-if="cooler">
                <span v-class="button-active : cooler.state" class="button-icon glyphicons glyphicons-snowflake device-toggle"></span>
            </div>

        </div>

    </div>
</script>

@stop