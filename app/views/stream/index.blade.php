
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
                <table class="table table-hover">
                    <tr>
                        <th width="50%">Field</th>
                        <th>Last Value</th>
                    </tr>
                @foreach ($stream['fields'] as $field_key => $field_name)
                    <tr>
                        <td>{{ $field_name }}</td>
                        <td>{{{ $stream['current_values'][$field_key] or "No Data" }}}</td>
                    </tr>
                @endforeach
                </table>

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