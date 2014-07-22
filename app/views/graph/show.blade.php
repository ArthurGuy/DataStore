<h1>{{ $graph['name'] }}</h1>


<div id="graphdiv" style="width:600px; height:300px;"></div>
<div id="graphdiv2" style="width:600px; height:300px;"></div>
<script>

    google.load('visualization', '1', {packages: ['linechart']});


    function createDataTable(dateType) {
        data = new google.visualization.DataTable();
        data.addColumn(dateType, 'Date');
        data.addColumn('number', 'Column A');
        data.addRows({{ count($data) }});
        <?php $i = 0 ?>
        @foreach ($data as $row)
            @if (isset($row[$graph['field']]))
            data.setCell({{ $i }}, 0, new Date({{ $row['time'] }} * 1000));
            data.setCell({{ $i }}, 1, {{ $row[$graph['field']] }});
            <?php $i++ ?>
            @endif
        @endforeach
        return data;
    }

    function drawVisualization() {
        data = createDataTable('date');

        var chart1 = new Dygraph.GVizChart(
            document.getElementById('graphdiv')).draw(data, {
            });

        data = createDataTable('datetime');
        var chart2 = new Dygraph.GVizChart(
            document.getElementById('graphdiv2')).draw(data, {
            });


    }
    google.setOnLoadCallback(drawVisualization);
</script>




<br /><br />
<a href="{{ route('graph.edit', $graph['id']) }}" class="btn btn-default">Edit</a><br />
<br />


{{ Form::open(array('route' => array('graph.destroy', $graph['id']), 'method'=>'DELETE')) }}

{{ Form::submit('Delete', array('class'=>'btn btn-danger btn-sm')) }}

{{ Form::close() }}