<?php

namespace App\Http\Controllers;

use App\Models\MemberCard;
use Illuminate\Http\Request;

class MemberCardController extends Controller
{
    public function generate(\Illuminate\Http\Request $request, \App\Models\Customer $customer, \App\Services\MemberCodeGeneratorService $generator)
    {
        if ($customer->memberCard) {
            return redirect()->route('admin.customers.show', $customer)->with('error', 'Customer already has a member card.');
        }

        $codeData = $generator->generate($customer);
        $qrToken = (string) \Illuminate\Support\Str::uuid();

        \App\Models\MemberCard::create([
            'customer_id' => $customer->customer_id,
            'dealer_id' => $customer->dealer_id,
            'member_code' => $codeData['member_code'],
            'member_code_base' => $codeData['member_code_base'],
            'duplicate_sequence' => $codeData['duplicate_sequence'],
            'qr_token' => $qrToken,
            'qr_payload' => json_encode(['token' => $qrToken, 'type' => 'member_card']),
            'issued_date' => now()->toDateString(),
            'print_count' => 0,
        ]);

        return redirect()->route('admin.member_cards.preview', $customer)->with('success', 'Member card generated successfully.');
    }

    public function preview(\App\Models\Customer $customer)
    {
        $memberCard = $customer->memberCard;
        if (!$memberCard) {
            return redirect()->route('admin.customers.show', $customer)->with('error', 'Member card not found.');
        }

        return view('admin.member_cards.preview', compact('customer', 'memberCard'));
    }

    public function print(\App\Models\Customer $customer)
    {
        $memberCard = $customer->memberCard;
        if (!$memberCard) {
            return redirect()->route('admin.customers.show', $customer)->with('error', 'Member card not found.');
        }

        $memberCard->increment('print_count');
        $memberCard->update(['last_printed_at' => now()]);

        // CR-80 standard card size in points (85.60 mm x 53.98 mm)
        $paperSize = [0, 0, 242.64, 153.01];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.member_cards.print', compact('customer', 'memberCard'))
                ->setPaper($paperSize, 'portrait');
        
        return $pdf->stream('MemberCard_' . $memberCard->member_code . '.pdf');
    }

    /**
     * S3: Regenerate token for lost/replaced card
     */
    public function regenerate(\Illuminate\Http\Request $request, \App\Models\Customer $customer)
    {
        $memberCard = $customer->memberCard;
        if (!$memberCard) {
            return redirect()->route('admin.customers.show', $customer)->with('error', 'Member card not found.');
        }

        // Nonaktifkan token lama
        $memberCard->update([
            'status' => 'inactive',
        ]);

        // Buat kartu baru dengan token baru
        $newQrToken = (string) \Illuminate\Support\Str::uuid();
        
        \App\Models\MemberCard::create([
            'customer_id' => $customer->customer_id,
            'dealer_id' => $customer->dealer_id,
            'member_code' => $memberCard->member_code,
            'member_code_base' => $memberCard->member_code_base,
            'duplicate_sequence' => $memberCard->duplicate_sequence,
            'qr_token' => $newQrToken,
            'qr_payload' => json_encode(['token' => $newQrToken, 'type' => 'member_card']),
            'issued_date' => now()->toDateString(),
            'print_count' => 0,
            'status' => 'active',
        ]);

        return redirect()->route('admin.member_cards.preview', $customer)->with('success', 'Token regenerated. Old card deactivated.');
    }

    /**
     * Bulk print member cards for multiple customers as a single combined PDF.
     */
    public function bulkPrint(\Illuminate\Http\Request $request)
    {
        $ids = $request->input('customer_ids', []);

        if (empty($ids)) {
            return redirect()->route('admin.customers.index')->with('error', 'Pilih minimal satu pelanggan untuk mencetak kartu.');
        }

        $customers = \App\Models\Customer::with('memberCard')
            ->whereIn('customer_id', $ids)
            ->whereHas('memberCard', fn($q) => $q->where('status', 'active'))
            ->get();

        if ($customers->isEmpty()) {
            return redirect()->route('admin.customers.index')->with('error', 'Tidak ada pelanggan terpilih yang memiliki kartu member aktif.');
        }

        // Increment print count for all selected cards
        foreach ($customers as $customer) {
            $customer->memberCard->increment('print_count');
            $customer->memberCard->update(['last_printed_at' => now()]);
        }

        // CR-80 standard card size in points (85.60mm x 53.98mm)
        $paperSize = [0, 0, 242.64, 153.01];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.member_cards.bulk_print', compact('customers'))
            ->setPaper($paperSize, 'portrait');

        return $pdf->stream('MemberCards_Bulk_' . now()->format('Ymd_His') . '.pdf');
    }
}
