

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Fields</th>
            <th></th>
        </tr>
    </thead>
    <tbody
    @foreach ($streams as $stream)
        <tr>
            <td><a href="{{ route('stream.show', $stream['id']) }}">{{ $stream['id'] }}</a></td>
            <td>{{ $stream['name'] }}</td>
            <td>
                <table>
                    <tr>
                        <th>Key</th>
                        <th>Name</th>
                        <th>Type</th>
                    </tr>
                @foreach ($stream['fields'] as $field)
                    <tr>
                        <td>{{ $field['key'] }}</td>
                        <td>{{ $field['name'] }}</td>
                        <td>{{ $field['type'] }}</td>
                    </tr>
                @endforeach
                </table>
            </td>
            <td><a href="{{ route('stream.data.index', $stream['id']) }}">View Data</a></td>
        </tr>
    @endforeach
    </tbody>
</table>