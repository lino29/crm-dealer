<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function index()
    {
        return view('admin.scan.index');
    }

    public function validateToken(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'qr_token' => 'required|string',
        ]);

        $token = $request->input('qr_token');
        $memberCard = \App\Models\MemberCard::with('customer')->where('qr_token', $token)->first();

        if ($memberCard && $memberCard->status === 'active') {
            \App\Models\ScanLog::create([
                'card_id' => $memberCard->card_id,
                'customer_id' => $memberCard->customer_id,
                'qr_token_scanned' => $token,
                'scanned_at' => now(),
                'status' => 'success',
                'scanned_by' => auth()->id(),
                'device_info' => $request->header('User-Agent'),
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data pelanggan ditemukan.',
                'redirect_url' => route('admin.customers.show', $memberCard->customer_id)
            ]);
        }

        // Handle inactive card
        if ($memberCard && $memberCard->status !== 'active') {
            \App\Models\ScanLog::create([
                'card_id' => $memberCard->card_id,
                'customer_id' => $memberCard->customer_id,
                'qr_token_scanned' => $token,
                'scanned_at' => now(),
                'status' => 'failed',
                'scanned_by' => auth()->id(),
                'device_info' => $request->header('User-Agent'),
                'ip_address' => $request->ip(),
                'note' => 'Member card nonaktif.',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Member card nonaktif.'
            ], 404);
        }

        // Handle token not found
        \App\Models\ScanLog::create([
            'card_id' => null,
            'customer_id' => null,
            'qr_token_scanned' => $token,
            'scanned_at' => now(),
            'status' => 'invalid',
            'scanned_by' => auth()->id(),
            'device_info' => $request->header('User-Agent'),
            'ip_address' => $request->ip(),
            'note' => 'Token member card tidak ditemukan.',
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Token member card tidak ditemukan.'
        ], 404);
    }
}
