@extends('layouts.main')

@section('content')


<table class="table">
    <tr>
        <th>Name</th>
        <th>Num Items</th>
        <th>Item Name Size</th>
        <th>Attribute Name Size</th>
        <th>Attribute Value Size</th>
    </tr>
@foreach ($simpleDbDomains as $domain => $details)
    <tr>
        <td>{{ $domain }}</td>
        <td>{{ number_format($details['ItemCount']) }}</td>
        <td>{{ number_format($details['ItemNamesSizeBytes']) }}</td>
        <td>{{ number_format($details['AttributeNamesSizeBytes']) }}</td>
        <td>{{ number_format($details['AttributeValuesSizeBytes']) }}</td>
    </tr>
@endforeach
</table>

@stop