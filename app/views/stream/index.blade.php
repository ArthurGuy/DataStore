

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
            <td>{{ implode(', ', $stream['fields']) }}</td>
            <td><a href="{{ route('stream.data.index', $stream['id']) }}">View Data</a></td>
        </tr>
    @endforeach
    </tbody>
</table>