@extends('layouts.app')

@section('title')
{{ ucfirst($location->name) }} Dashboard
@stop

@section('content')

    <style>

        .daySummary {
            font-size: 25px;
            font-weight: 400;
            display: block;
            text-align: center;
        }
        .keyForcast {
            width: 400px;
            text-align: center;
        }
        .keyForcast .summary {
            font-size: 30px;
            font-weight: 600;
            display: block;
        }
        .keyForcast .primaryTemp {
            font-size: 30px;
            font-weight: 400;
            display: block;
        }
        .keyForcast .secondaryTemp {
            font-size: 20px;
            font-weight: 200;
            color: #6A6868;
            display: block;
        }
        .keyForcast .condition {
            font-size: 25px;
            font-weight: 500;
            display: block;
        }

        .room {
            background-color: #F5F5F5;
            padding: 15px;
        }
        .room.heater-on {
            background-color: #EB6D00;
        }
        .room.cooling-on {
            background-color: #05F;
        }
        .room .name {
            font-size: 20px;
            display: block;
            font-weight: 600;
        }
        .room .primaryTemp {
            float: right;
            font-size: 20px;
            font-weight: 200;
        }
        .room .condition {
            text-align: center;
            font-size: 25px;
            display: block;
            font-weight: 400;
        }
        .room .heater-status {
            text-align: center;
            display: block;
            font-size: 20px;
        }


    </style>


    <h1 class="daySummary">{{ $dayWeather['daySummary'] }}</h1>


    <div style="display: flex; justify-content: center; flex-wrap: wrap; margin: 30px 0;">
        <div class="keyForcast">
            <canvas id="future-weather-icon" width="200" height="200"></canvas>
            <span class="summary hidden">{{ $futureForecast->summary }}</span>
            <span class="primaryTemp">{{ $outsideWeather['temperature'] }}°C</span>
            <span class="secondaryTemp">{{ $dayWeather['dayMinTemperature'] }}°C - {{ $dayWeather['dayMaxTemperature'] }}°C</span>
            <span class="condition">{{ $outsideWeather['condition'] }}</span>
        </div>
    </div>

    <div style="display: flex; justify-content: center; flex-wrap: wrap;">
    @foreach ($rooms as $room)
        <div style="width: 300px; margin:10px;" class="room @if ($room->deviceOn('heater')) heater-on @endif">

            <span class="name">
                {{ $room->name }}
                @if ($room->last_updated->lt(\Carbon\Carbon::now()->subHours(2)))
                    <span class="glyphicon glyphicon-exclamation-sign" title="No Updates since {{ $room->last_updated }}" data-toggle="tooltip" data-placement="left" style="color:#FF7100;"></span>
                @endif
                <span class="primaryTemp">{{ $room->temperature }}°C | {{ $room->humidity }}%</span>
            </span>
            <span class="condition">{{ $room->condition }}</span>

            <!--{{ $room->target_temperature }}°C-->

            @if ($room->device('heater'))

                @if ($room->device('heater')->state)
                    <span class="heater-status">Heating to {{ $room->target_temperature }}°C</span>
                @else
                    Heater Off
                @endif
            @endif


        </div>
    @endforeach
    </div>

    @if ($location->home)
        <h2 style="text-align: center"><span class="glyphicon glyphicon-home"></span> Home</h2>
    @else
        <h2 style="text-align: center">Away</h2>
    @endif

<!--
    <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2">

        <pre>
            {{ print_r($forecast) }}
        </pre>

    </div>
-->

    <script>

        var skycons = new Skycons({"color": "black"});
        skycons.add("future-weather-icon", '{{ $futureForecast->icon }}');
        skycons.play();

    </script>

@stop