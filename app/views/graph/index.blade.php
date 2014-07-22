

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
        </tr>
    </thead>
    <tbody
    @foreach ($graphs as $graph)
        <tr>
            <td><a href="{{ route('graph.show', $graph['id']) }}">{{ $graph['id'] }}</a></td>
            <td>{{ $graph['name'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>