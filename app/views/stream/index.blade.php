
<div class="page-header">
    <h1>Data Streams</h1>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th></th>
            <th>Filter</th>
            <th>Fields</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody
    @foreach ($streams as $stream)
        <tr>
            <td>
                <strong>{{ $stream['name'] }}</strong><br />
                <small>{{ $stream['id'] }}</small>
            </td>
            <td><a href="{{ route('stream.data.index', $stream['id']) }}" class="btn btn-primary">View Data</a></td>
            <td>{{ $stream['filter_field'] }}</td>
            <td>
                @foreach ($stream['current_values'] as $location => $current_values)
                <div class="panel panel-default">
                    <div class="panel-heading">{{ $location }}: {{ $stream->lookupFilterName($location) }}</div>
                    <table class="table">
                        @foreach ($stream['current_values'][$location] as $field => $value)
                        <tr>
                            <td>{{ $field }}</td>
                            <td>{{ $value }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                @endforeach
            </td>
            <td></td>
            <td>

                {{ Form::open(array('route' => array('stream.destroy', $stream['id']), 'method'=>'DELETE')) }}

                <a href="{{ route('stream.show', $stream['id']) }}" class="btn btn-sm btn-default">Manage</a> |
                <a href="{{ route('stream.edit', $stream['id']) }}" class="btn btn-sm btn-default">Edit</a> |
                {{ Form::submit('Delete', array('class'=>'btn btn-danger btn-sm')) }}

                {{ Form::close() }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<a href="{{ route('stream.create') }}" class="btn btn-info">Create</a>