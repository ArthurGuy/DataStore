
<div class="page-header">
    <h1>Data Streams</h1>
</div>

<table class="table table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Fields</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody
    @foreach ($streams as $stream)
        <tr>
            <td><small>{{ $stream['id'] }}</small></td>
            <td><a href="{{ route('stream.data.index', $stream['id']) }}" class="btn btn-primary">{{ $stream['name'] }}</a></td>
            <td>
                @foreach ($stream['fields'] as $field)
                    {{ $field['name'] }},
                @endforeach
            </td>
            <td></td>
            <td>

                {{ Form::open(array('route' => array('stream.destroy', $stream['id']), 'method'=>'DELETE')) }}

                <a href="{{ route('stream.show', $stream['id']) }}" class="btn btn-xs btn-default">Edit</a> |
                {{ Form::submit('Delete', array('class'=>'btn btn-danger btn-xs')) }}

                {{ Form::close() }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<a href="{{ route('stream.create') }}" class="btn btn-info">Create</a>