<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'setting_group', 'description'];

    public static function getWaTemplate()
    {
        $setting = self::where('key', 'wa_template')->first();
        return $setting ? $setting->value : 'Halo {customer_name}, ini adalah pengingat dari CRM Dealer. Kendaraan Anda {vehicle_info} memiliki jadwal servis pada {date}. Mohon segera kunjungi bengkel kami.';
    }

    public static function getReminderDays(): int
    {
        $setting = self::where('key', 'reminder_days')->first();
        return $setting ? (int) $setting->value : 3;
    }

    public static function getCompanyName(): string
    {
        $setting = self::where('key', 'company_name')->first();
        return $setting ? $setting->value : 'PT. Trijaya Motor';
    }
}
