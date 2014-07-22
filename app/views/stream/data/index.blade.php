
<h1>{{ $stream['name'] }}</h1>
<h3>{{ $stream['id'] }}</h3>
<table class="table table-striped" id="data">
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

<script id="dataRow" type="text/html">
    <tr>
        <td>@{{ date }}</td>
        @foreach($stream['fields'] as $field)
            <td>
            @if ($field['key'] == 'location')
                <a href="{{ route('stream.data.index', $stream['id']) }}?location={{ <?php echo $field['key']; ?> }}">{{ <?php echo $field['key']; ?> }}</a>
            @else
                {{ <?php echo $field['key']; ?> }}
            @endif
            </td>
        @endforeach
        <td>
            <form method="POST" action="{{ route('stream.data.index', $stream['id']) }}/@{{ time }}" accept-charset="UTF-8">
                <input name="_method" type="hidden" value="DELETE">

            {{ Form::submit('Delete', array('class'=>'btn btn-danger btn-xs')) }}

            {{ Form::close() }}
        </td>
    </tr>
</script>


<script>

    var pusher = new Pusher('{{ $_ENV['PUSHER_APP_KEY'] }}');
    var channel = pusher.subscribe('{{ $pusherChannelName }}');
    channel.bind('new', function(data) {
        //console.log(data.data);
        var row = ich.dataRow(JSON.parse(data.data));
        console.log(row);
        $('#data tbody').prepend(row);
    });

</script>