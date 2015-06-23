@extends('layouts.main')

@section('content')


    <h1 style="text-align: center">{{ $outTemperature }}°C | {{ $forecast->summary }}</h1>

    <div style="margin:auto; width: 300px;">
        <canvas id="icon1" width="300" height="300"></canvas>
    </div>

    @foreach ($rooms as $room)
        <h2 style="text-align: center">
            {{ $room->name }} {{ $room->temperature }}°C | {{ round($room->humidity) }}%
            @if ($room->last_updated->lt(\Carbon\Carbon::now()->subHours(2)))
                <span class="glyphicon glyphicon-exclamation-sign" title="No Updates since {{ $room->last_updated }}" data-toggle="tooltip" data-placement="right" style="color:#FF7100;"></span>
            @endif
        </h2>
    @endforeach

<!--
    <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2">

        <pre>
            {{ print_r($forecast) }}
        </pre>

    </div>
-->

    <script>

        var skycons = new Skycons({"color": "black"});
        skycons.add("icon1", '{{ $forecast->icon }}');
        skycons.play();

    </script>

@stop