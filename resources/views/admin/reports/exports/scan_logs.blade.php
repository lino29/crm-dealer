<table>
    <thead>
        <tr>
            <th>Scanned At</th>
            <th>Status</th>
            <th>Customer</th>
            <th>QR Token Scanned</th>
            <th>Device Info</th>
            <th>IP Address</th>
            <th>Note</th>
        </tr>
    </thead>
    <tbody>
        @foreach($logs as $log)
        <tr>
            <td>{{ $log->scanned_at->format('Y-m-d H:i:s') }}</td>
            <td>{{ ucfirst($log->status) }}</td>
            <td>{{ $log->customer->customer_name ?? '-' }}</td>
            <td>{{ $log->qr_token_scanned }}</td>
            <td>{{ $log->device_info }}</td>
            <td>{{ $log->ip_address }}</td>
            <td>{{ $log->note }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
