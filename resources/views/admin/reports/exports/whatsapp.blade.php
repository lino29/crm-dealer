<table>
    <thead>
        <tr>
            <th>Created At</th>
            <th>Customer</th>
            <th>Phone</th>
            <th>Message</th>
            <th>Status</th>
            <th>Sent At</th>
            <th>Gateway Response</th>
        </tr>
    </thead>
    <tbody>
        @foreach($notifications as $n)
        <tr>
            <td>{{ $n->created_at->format('Y-m-d H:i:s') }}</td>
            <td>{{ $n->customer->customer_name ?? '-' }}</td>
            <td>{{ $n->phone }}</td>
            <td>{{ $n->message }}</td>
            <td>{{ ucfirst($n->send_status) }}</td>
            <td>{{ $n->sent_at ? $n->sent_at->format('Y-m-d H:i:s') : '-' }}</td>
            <td>{{ $n->gateway_response }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
