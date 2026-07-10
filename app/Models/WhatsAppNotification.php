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
        'api_response_id',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    // Accessors for Indonesian aliases aligning with the thesis proposal
    public function getIdNotifikasiAttribute()
    {
        return $this->notification_id;
    }

    public function getIdServisAttribute()
    {
        return $this->schedule_id;
    }

    public function getTanggalKirimAttribute()
    {
        return $this->sent_at;
    }

    public function getPesanAttribute()
    {
        return $this->message;
    }

    public function getStatusKirimAttribute()
    {
        return $this->send_status;
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
