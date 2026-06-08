<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberCard extends Model
{
    protected $primaryKey = 'card_id';

    protected $fillable = [
        'customer_id',
        'dealer_id',
        'member_code',
        'member_code_base',
        'duplicate_sequence',
        'qr_token',
        'qr_payload',
        'issued_date',
        'expired_date',
        'print_count',
        'last_printed_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'last_printed_at' => 'datetime',
            'issued_date' => 'date',
            'expired_date' => 'date',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class, 'dealer_id', 'dealer_id');
    }

    public function scanLogs()
    {
        return $this->hasMany(ScanLog::class, 'card_id', 'card_id');
    }
}
