<table>
    <thead>
        <tr>
            <th>Plate Number</th>
            <th>Brand</th>
            <th>Model</th>
            <th>Year</th>
            <th>Owner</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($vehicles as $v)
        <tr>
            <td>{{ $v->police_number }}</td>
            <td>{{ $v->brand }}</td>
            <td>{{ $v->model }}</td>
            <td>{{ $v->production_year ?? '-' }}</td>
            <td>{{ $v->customer->customer_name ?? '-' }}</td>
            <td>{{ ucfirst($v->status) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
