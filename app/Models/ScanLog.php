<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScanLog extends Model
{
    protected $primaryKey = 'log_id';

    protected $fillable = [
        'card_id',
        'customer_id',
        'qr_token_scanned',
        'scanned_at',
        'status',
        'scanned_by',
        'device_info',
        'ip_address',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'scanned_at' => 'datetime',
        ];
    }

    public function memberCard()
    {
        return $this->belongsTo(MemberCard::class, 'card_id', 'card_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function scanner()
    {
        return $this->belongsTo(User::class, 'scanned_by', 'user_id');
    }
}
