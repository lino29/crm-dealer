<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'dealer_id',
        'customer_name',
        'gender',
        'birth_date',
        'phone',
        'address',
        'usk_month',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class, 'dealer_id', 'dealer_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'customer_id', 'customer_id');
    }

    public function memberCard()
    {
        return $this->hasOne(MemberCard::class, 'customer_id', 'customer_id')->where('status', 'active');
    }

    public function memberCards()
    {
        return $this->hasMany(MemberCard::class, 'customer_id', 'customer_id');
    }

    public function serviceHistories()
    {
        return $this->hasMany(ServiceHistory::class, 'customer_id', 'customer_id');
    }

    public function serviceSchedules()
    {
        return $this->hasManyThrough(ServiceSchedule::class, Vehicle::class, 'customer_id', 'vehicle_id', 'customer_id', 'vehicle_id');
    }

    public function scanLogs()
    {
        return $this->hasMany(ScanLog::class, 'customer_id', 'customer_id');
    }

    public function whatsappNotifications()
    {
        return $this->hasMany(WhatsAppNotification::class, 'customer_id', 'customer_id');
    }
}
