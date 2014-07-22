

<div class="page-header">
    <h1>Data Graphs</h1>
</div>

<table class="table table-hover">
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
<a href="{{ route('graph.create') }}" class="btn btn-info">Create</a>