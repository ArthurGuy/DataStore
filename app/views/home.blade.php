@extends('layouts.main')

@section('content')


    <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2">

        {{ $forecast->summary }}<br />
        Inside:  | Out: {{ $outTemperature }}

        <pre>
            {{ print_r($forecast) }}
        </pre>

    </div>


@stop