
<h1>{{ $stream['name'] }}</h1>
<h2>{{ $stream['id'] }}</h2>
<pre><?php print_r($stream['fields']) ?></pre><br />
{{ implode(', ', $stream['tags']) }}

<br /><br />
<a href="{{ route('stream.edit', $stream['id']) }}" class="btn btn-default">Edit</a><br />
<br />


{{ Form::open(array('route' => array('stream.destroy', $stream['id']), 'method'=>'DELETE')) }}

{{ Form::submit('Delete', array('class'=>'btn btn-danger btn-sm')) }}

{{ Form::close() }}