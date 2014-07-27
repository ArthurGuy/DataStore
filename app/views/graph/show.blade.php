<h1>{{ $graph['name'] }}</h1>


<div id="graphdiv" style="width:100%; height:400px;"></div>
<script>

    google.load('visualization', '1', {packages: ['linechart']});


    function createDataTable() {
        data = new google.visualization.DataTable();
        data.addColumn('date', 'Date');
        data.addColumn('number', '{{ $graph['field'] }}');
        data.addRows({{ count($data) }});
        <?php $i = 0 ?>
        @foreach ($data as $row)
            @if (isset($row[$graph['field']]))
                data.setCell({{ $i }}, 0, new Date('{{ $row['date'] }}'));
                data.setCell({{ $i }}, 1, {{ $row[$graph['field']] }});
                <?php $i++ ?>
            @endif
        @endforeach

        return data;
    }

    function drawVisualization() {
        data = createDataTable();

        var graph = new Dygraph.GVizChart(
            document.getElementById('graphdiv')).draw(data, {});
    }
    google.setOnLoadCallback(drawVisualization);
</script>



