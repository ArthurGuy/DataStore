@extends('layouts.app')

@section('title')
{{ ucfirst($location->name) }} Dashboard
@stop

@section('content')

    <div id="dashboard" location="{{ $location->id }}">

        <section class="forecast" v-class="show-forecast : forecastAvailable">

            <h1 class="daySummary" v-text="forecast.dayWeather.daySummary"></h1>

            <div style="display: flex; justify-content: center; flex-wrap: wrap; margin: 30px 0;">
                <div class="keyForecast">
                    <weather-icon width="200" height="200" icon="@{{ forecast.futureForecast.icon }}"></weather-icon>
                    <span class="primaryTemp"><temperature value="@{{ forecast.temperature }}"></temperature></span>
                    <span class="secondaryTemp"><temperature value="@{{ forecast.dayWeather.dayMinTemperature }}"></temperature> - <temperature value="@{{ forecast.dayWeather.dayMaxTemperature }}"></temperature></span>
                    <span v-text="forecast.condition" class="condition"></span>
                </div>
            </div>

        </section>

        <div class="room-list">

            <room v-repeat="rooms"></room>

        </div>

        <h2 style="text-align: center">
        @if ($location->home)
            <span class="glyphicon glyphicon-home"></span> Home
        @else
            <span class="glyphicons glyphicons-person-running"></span> Away
        @endif
        </h2>

    </div>

<script id="room-template" type="x-template">
    <div v-class="heater-on : heater && heater.state" class="room">

        <div class="heading">

            <span class="name">
                @{{ name }}
                <span v-if="hasWarning" class="glyphicons glyphicons-warning-sign" title="No Updates since @{{ last_updated }}" data-toggle="tooltip" data-placement="left" style="color:#FF7100;"></span>
            </span>
            <span class="primaryTemp"><temperature value="@{{ temperature }}"></temperature> | @{{ humidity }}%</span>

        </div>

        <span class="condition">@{{ condition }}</span>

        <span class="heater-status">Heating to <temperature value="@{{ target_temperature }}"></temperature></span>

        <div class="action-row">

            <div class="action">
                <span v-class="button-active : mode == 'auto'" v-on="click: modeToggle" class="button-icon glyphicons glyphicons-repeat mode-toggle"></span>
            </div>

            <div v-if="heater" class="action">
                <span v-class="button-active : heater.state" v-on="click: heaterToggle" class="button-icon glyphicons glyphicons-heat device-toggle"></span>
            </div>

            <div v-if="cooler" class="action">
                <span v-class="button-active : cooler.state" class="button-icon glyphicons glyphicons-snowflake device-toggle"></span>
            </div>

        </div>

    </div>
</script>

@stop