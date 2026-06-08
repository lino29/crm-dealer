<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceSchedule extends Model
{
    protected $primaryKey = 'schedule_id';

    protected $fillable = [
        'vehicle_id',
        'history_id',
        'customer_id',
        'scheduled_date',
        'reminder_date',
        'status',
        'notification_status',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'reminder_date' => 'date',
        ];
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'vehicle_id');
    }

    public function history()
    {
        return $this->belongsTo(ServiceHistory::class, 'history_id', 'history_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function whatsappNotifications()
    {
        return $this->hasMany(WhatsAppNotification::class, 'schedule_id', 'schedule_id');
    }
}
