<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\MemberCard;

class MemberCodeGeneratorService
{
    /**
     * Generate a unique member code for a customer.
     * Format SRS: [InisialNama]-[KodeDealer]-[BulanUSK]-[TanggalLahirBulanLahirTahunLahir]
     * Contoh: SW-TMS-01-031281
     * If duplicate exists, append -001, -002, etc.
     */
    public function generate(Customer $customer): array
    {
        $customer->loadMissing('dealer');
        
        // 1. Ambil inisial nama (maks 3 kata pertama)
        $nameParts = explode(' ', trim($customer->customer_name));
        $initials = '';
        foreach (array_slice($nameParts, 0, 3) as $part) {
            $initials .= strtoupper(substr($part, 0, 1));
        }
        
        // 2. Kode dealer
        $dealerCode = $customer->dealer ? strtoupper($customer->dealer->dealer_code) : 'XXX';
        
        // 3. Bulan USK (dari kolom usk_month)
        $uskMonth = str_pad($customer->usk_month, 2, '0', STR_PAD_LEFT);
        
        // 4. Format tanggal lahir: ddmmyy
        $birthDateCode = $customer->birth_date->format('dmy');
        
        // 5. Gabungkan: SW-TMS-01-031281
        $baseCode = sprintf('%s-%s-%s-%s', $initials, $dealerCode, $uskMonth, $birthDateCode);
        
        // 6. Cek duplikasi
        $sequence = 0;
        $memberCode = $baseCode;
        
        while (MemberCard::where('member_code', $memberCode)->exists()) {
            $sequence++;
            $memberCode = $baseCode . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
        }
        
        return [
            'member_code' => $memberCode,
            'member_code_base' => $baseCode,
            'duplicate_sequence' => $sequence,
        ];
    }
}
