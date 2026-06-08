<!DOCTYPE html>
<html>
<head>
    <title>Services Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Services Report</h2>
    <p>Generated at: {{ now()->format('Y-m-d H:i:s') }}</p>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Vehicle Police No</th>
                <th>Customer Name</th>
                <th>Dealer Name</th>
                <th>Service Type</th>
            </tr>
        </thead>
        <tbody>
            @foreach($services as $service)
            <tr>
                <td>{{ $service->service_date->format('Y-m-d') }}</td>
                <td>{{ $service->vehicle->police_number }}</td>
                <td>{{ $service->vehicle->customer->customer_name }}</td>
                <td>{{ $service->vehicle->dealer->dealer_name ?? '-' }}</td>
                <td>{{ $service->service_type }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
