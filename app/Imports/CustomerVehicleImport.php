<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\Dealer;
use App\Models\MemberCard;
use App\Models\Vehicle;
use App\Services\MemberCodeGeneratorService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomerVehicleImport implements
    ToCollection,
    WithHeadingRow,
    WithValidation,
    WithChunkReading,
    SkipsOnFailure
{
    use SkipsFailures;

    protected int $dealerId;
    protected int $importedBy;
    protected int $successCount = 0;
    protected int $skippedCount = 0;

    public function __construct(int $dealerId, int $importedBy)
    {
        $this->dealerId   = $dealerId;
        $this->importedBy = $importedBy;
    }

    /**
     * Process each chunk of rows.
     */
    public function collection(Collection $rows): void
    {
        $generator = app(MemberCodeGeneratorService::class);

        foreach ($rows as $row) {
            DB::transaction(function () use ($row, $generator) {
                // ---- Normalize phone ----
                $phone = preg_replace('/[^0-9]/', '', (string) ($row['no_whatsapp'] ?? ''));

                // ---- Find or create Customer ----
                $isNew    = false;
                $customer = Customer::where('phone', $phone)->first();

                if (!$customer) {
                    $isNew    = true;
                    $customer = Customer::create([
                        'dealer_id'     => $this->dealerId,
                        'customer_name' => trim((string) ($row['nama_pelanggan'] ?? '')),
                        'phone'         => $phone,
                        'address'       => trim((string) ($row['alamat'] ?? '')),
                        'gender'        => 'other',
                        'birth_date'    => '2000-01-01',
                        'usk_month'     => now()->format('m'),
                        'status'        => 'active',
                        'created_by'    => $this->importedBy,
                    ]);
                }

                // ---- Auto-generate member card for new customers ----
                if ($isNew && !$customer->memberCard) {
                    $codeData = $generator->generate($customer);
                    $qrToken  = (string) Str::uuid();

                    MemberCard::create([
                        'customer_id'        => $customer->customer_id,
                        'dealer_id'          => $customer->dealer_id,
                        'member_code'        => $codeData['member_code'],
                        'member_code_base'   => $codeData['member_code_base'],
                        'duplicate_sequence' => $codeData['duplicate_sequence'],
                        'qr_token'           => $qrToken,
                        'qr_payload'         => json_encode(['token' => $qrToken, 'type' => 'member_card']),
                        'issued_date'        => now()->toDateString(),
                        'print_count'        => 0,
                        'status'             => 'active',
                    ]);
                }

                // ---- Find or create Vehicle ----
                $plateNumber = strtoupper(preg_replace('/\s+/', '', (string) ($row['no_polisi'] ?? '')));

                if ($plateNumber && !Vehicle::where('police_number', $plateNumber)->exists()) {
                    Vehicle::create([
                        'customer_id'     => $customer->customer_id,
                        'police_number'   => $plateNumber,
                        'brand'           => trim((string) ($row['merek_kendaraan'] ?? '')),
                        'model'           => trim((string) ($row['tipe_kendaraan'] ?? '')),
                        'production_year' => is_numeric($row['tahun_kendaraan'] ?? null)
                                              ? (int) $row['tahun_kendaraan']
                                              : null,
                        'status'          => 'active',
                    ]);
                }

                $this->successCount++;
            });
        }
    }

    /**
     * Validation rules applied per row before collection() is called.
     */
    public function rules(): array
    {
        return [
            'nama_pelanggan'  => ['required', 'string', 'max:255'],
            'no_whatsapp'     => ['required', 'string'],
            'no_polisi'       => ['required', 'string', 'max:20'],
            'merek_kendaraan' => ['required', 'string', 'max:100'],
            'tipe_kendaraan'  => ['required', 'string', 'max:100'],
        ];
    }

    /**
     * Human-readable field names for validation messages.
     */
    public function customValidationAttributes(): array
    {
        return [
            'nama_pelanggan'  => 'Nama Pelanggan',
            'no_whatsapp'     => 'No. WhatsApp',
            'no_polisi'       => 'No. Polisi',
            'merek_kendaraan' => 'Merek Kendaraan',
            'tipe_kendaraan'  => 'Tipe Kendaraan',
        ];
    }

    /**
     * Number of rows to process per chunk.
     */
    public function chunkSize(): int
    {
        return 500;
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }
}
