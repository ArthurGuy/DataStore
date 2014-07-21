
<h1>{{ $stream['name'] }}</h1>
<h3>{{ $stream['id'] }}</h3>
<table class="table table-striped">
    <thead>
    <tr>
        <th>Date</th>
        @foreach($stream['fields'] as $field)
        <th>{{ $field['name'] }}</th>
        @endforeach
        <th></th>
    </tr>
    </thead>
    <tbody
    @foreach ($data as $record)
    <tr>
        <td>{{ date("Y-m-d H:i:s", $record['time']) }}</td>
        @foreach($stream['fields'] as $field)
        <td>
            @if (isset($record[$field['key']]))
                @if ($field['key'] == 'location')
                    <a href="{{ route('stream.data.index', $stream['id']) }}?location={{ $record[$field['key']] }}">{{ $record[$field['key']] }}</a>
                @else
                    {{ $record[$field['key']] }}
                @endif
            @endif
        </td>
        @endforeach
        <td>
            {{ Form::open(array('route' => array('stream.data.destroy', $stream['id'], $record['time']), 'method'=>'DELETE')) }}

            {{ Form::submit('Delete', array('class'=>'btn btn-danger btn-xs')) }}

            {{ Form::close() }}
        </td>
    </tr>
    @endforeach
    </tbody>
</table>