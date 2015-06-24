@extends('layouts.main')

@section('content')


    <table>
    <tr>
        <th>Name</th>
        <th>Num Items</th>
        <th>Size</th>
    </tr>
@foreach ($simpleDbDomains as $domain => $details)
    <tr>
        <td>{{ $domain }}</td>
        <td>{{ $details['ItemCount'] }}</td>
        <td>{{ $details['ItemNamesSizeBytes'] }} | {{ $details['AttributeNamesSizeBytes'] }} | {{ $details['AttributeValuesSizeBytes'] }}</td>
    </tr>
@endforeach
</table>

@stop