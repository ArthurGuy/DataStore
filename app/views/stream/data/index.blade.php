
<h1>{{ $stream['name'] }}</h1>
<h3>{{ $stream['id'] }}</h3>
<table class="table table-hover table-condensed" id="data">
    <thead>
    <tr>
        <th>Date</th>
        <th>{{ $stream['filter_field'] }} <small>[Filter]</small></th>
        @foreach($stream['fields'] as $field)
        <th>{{ $field }}</th>
        @endforeach
        <th></th>
    </tr>
    </thead>
    <tbody
    @foreach ($data as $record)
    <tr>
        <td>{{ $record['date'] }}</td>
        <td>
            @if (isset($record[$stream['filter_field']]))
            {{ $record[$stream['filter_field']] }}: {{ $stream->lookupFilterName($record[$stream['filter_field']]) }}
            @else
            -
            @endif
        </td>
        @foreach($stream['fields'] as $field)
        <td>
            @if (isset($record[$field]))
                {{ $record[$field] }}
            @endif
        </td>
        @endforeach
        <td>
            @if ( ! Auth::guest())
                {{ Form::open(array('route' => array('stream.data.destroy', $stream['id'], $record['id']), 'method'=>'DELETE')) }}

                {{ Form::submit('Delete', array('class'=>'btn btn-danger btn-xs')) }}

                {{ Form::close() }}
            @endif
        </td>
    </tr>
    @endforeach
    </tbody>
</table>

<a href="?nextToken={{ $paginationNextToken }}">Next</a>

<script id="dataRow" type="text/html">
    <tr>
        <td>@{{ date }}</td>
        <td>{{ <?php echo $stream['filter_field']; ?> }}</td>
        @foreach($stream['fields'] as $field)
            <td>
            @if ($field == 'location')
                <a href="{{ route('stream.data.index', $stream['id']) }}?location={{ <?php echo $field; ?> }}">{{ <?php echo $field; ?> }}</a>
            @else
                {{ <?php echo $field; ?> }}
            @endif
            </td>
        @endforeach
        <td>
            @if ( ! Auth::guest())
                <form method="POST" action="{{ route('stream.data.index', $stream['id']) }}/@{{ time }}" accept-charset="UTF-8">
                    <input name="_method" type="hidden" value="DELETE">

                {{ Form::submit('Delete', array('class'=>'btn btn-danger btn-xs')) }}

                {{ Form::close() }}
            @endif
        </td>
    </tr>
</script>


<script>

    var pusher = new Pusher('{{ $_SERVER['PUSHER_APP_KEY'] }}');
    var channel = pusher.subscribe('{{ $pusherChannelName }}');
    channel.bind('{{ $stream['id'] }}', function(data) {
        //console.log(data.data);
        var row = ich.dataRow(JSON.parse(data.data));
        $('#data tbody').prepend(row);
    });

</script>