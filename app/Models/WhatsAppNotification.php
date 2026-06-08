<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppNotification extends Model
{
    protected $table = 'whatsapp_notifications';
    protected $primaryKey = 'notification_id';

    protected $fillable = [
        'schedule_id',
        'customer_id',
        'phone',
        'message',
        'send_status',
        'sent_at',
        'gateway_response',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    public function schedule()
    {
        return $this->belongsTo(ServiceSchedule::class, 'schedule_id', 'schedule_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }
}
