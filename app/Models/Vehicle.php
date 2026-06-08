<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $primaryKey = 'vehicle_id';

    protected $fillable = [
        'customer_id',
        'police_number',
        'engine_number',
        'chassis_number',
        'brand',
        'model',
        'production_year',
        'color',
        'purchase_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function serviceHistories()
    {
        return $this->hasMany(ServiceHistory::class, 'vehicle_id', 'vehicle_id');
    }

    public function serviceSchedules()
    {
        return $this->hasMany(ServiceSchedule::class, 'vehicle_id', 'vehicle_id');
    }
}
