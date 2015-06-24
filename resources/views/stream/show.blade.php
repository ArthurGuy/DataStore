@extends('layouts.main')

@section('content')


    <h1>{{ $stream['name'] }}</h1>
<h2>{{ $stream['id'] }}</h2>
<pre><?php print_r($stream['fields']) ?></pre><br />

<pre><?php print_r($stream['current_values']) ?></pre><br />

API Response: {{ $api_responses[$stream['response_id']] or 'None Set' }}

<br /><br />
<a href="{{ route('stream.edit', $stream['id']) }}" class="btn btn-default">Edit</a><br />
<br />


{!! Form::open(array('route' => array('stream.destroy', $stream['id']), 'method'=>'DELETE')) !!}

{!! Form::submit('Delete', array('class'=>'btn btn-danger btn-sm')) !!}

{!! Form::close() !!}

@stop