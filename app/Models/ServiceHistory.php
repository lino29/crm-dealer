<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceHistory extends Model
{
    protected $primaryKey = 'history_id';

    protected $fillable = [
        'vehicle_id',
        'customer_id',
        'service_date',
        'mileage',
        'complaint',
        'service_action',
        'service_type',
        'service_note',
        'description',
        'next_service_date',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'service_date' => 'date',
            'next_service_date' => 'date',
        ];
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'vehicle_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    public function schedule()
    {
        return $this->hasOne(ServiceSchedule::class, 'history_id', 'history_id');
    }
}
