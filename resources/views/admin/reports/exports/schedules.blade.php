<table>
    <thead>
        <tr>
            <th>Scheduled Date</th>
            <th>Customer</th>
            <th>Vehicle</th>
            <th>Reminder Date</th>
            <th>Status</th>
            <th>Notification Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($schedules as $s)
        <tr>
            <td>{{ $s->scheduled_date->format('Y-m-d') }}</td>
            <td>{{ $s->vehicle->customer->customer_name ?? '-' }}</td>
            <td>{{ $s->vehicle->police_number ?? '-' }}</td>
            <td>{{ $s->reminder_date ? $s->reminder_date->format('Y-m-d') : '-' }}</td>
            <td>{{ ucfirst($s->status) }}</td>
            <td>{{ ucfirst($s->notification_status) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
