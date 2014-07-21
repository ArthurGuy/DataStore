
<h1>{{ $stream['name'] }}</h1>
<h3>{{ $stream['id'] }}</h3>
<table class="table table-striped">
    <thead>
    <tr>
        <th>Date</th>
        @foreach($stream['fields'] as $field)
        <th>{{ $field }}</th>
        @endforeach
        <th></th>
    </tr>
    </thead>
    <tbody
    @foreach ($data as $record)
    <tr>
        <td>{{ $record['time'] }}</td>
        @foreach($stream['fields'] as $field)
        <td>
            @if (isset($record[$field]))
                {{ $record[$field] }}
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