<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bulk Member Cards - A4 Sheet</title>
    <style>
        /*
         * A4 paper in portrait:  595.28pt × 841.89pt
         * CR-80 card size:       242.64pt × 153.01pt
         *
         * Grid layout (2 columns × 5 rows):
         *   - Card width  : 242.64pt
         *   - Card height : 153.01pt
         *   - Gap H       : ~10pt  → total width  = 2×242.64 + 10 = 495.28pt  (within 595.28pt, centered)
         *   - Gap V       : ~9pt   → total height = 5×153.01 + 4×9 = 801.05pt (within 841.89pt)
         *   - Margin top  : (841.89 - 801.05) / 2 ≈ 20pt
         *   - Margin side : (595.28 - 495.28) / 2 = 50pt
         */
        @page {
            margin: 20pt 50pt 20pt 50pt;
            size: A4 portrait;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }

        .grid {
            /* 2 equal columns with a 10pt gap */
            display: table;
            width: 495.28pt;
            border-collapse: separate;
            border-spacing: 0 9pt;
        }

        .grid-row {
            display: table-row;
        }

        .grid-cell {
            display: table-cell;
            width: 242.64pt;
            padding-right: 10pt;
            vertical-align: top;
        }

        .grid-cell:last-child {
            padding-right: 0;
        }

        /* ---- The actual card ---- */
        .card {
            width: 242.64pt;
            height: 153.01pt;
            background-image: url('{{ public_path('images/template_kosong.png') }}');
            background-size: 100% 100%;
            background-repeat: no-repeat;
            position: relative;
            overflow: hidden;
            box-sizing: border-box;
        }

        .qr-container {
            position: absolute;
            left: 9.65%;
            top: 59.16%;
            width: 18.50%;
            height: 29.54%;
            background-color: white;
            padding: 1pt;
            border-radius: 1.5pt;
            box-sizing: border-box;
            text-align: center;
        }

        .qr-container img {
            width: 100%;
            height: 100%;
            display: block;
        }

        .text-container {
            position: absolute;
            left: 9.25%;
            top: 89.5%;
            width: 45%;
        }

        .member-code {
            font-size: 6.5pt;
            font-weight: bold;
            color: #ffffff;
            margin: 0;
            line-height: 1;
            letter-spacing: 0.5px;
        }

        .customer-name {
            font-size: 6.5pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #ffffff;
            margin: 1.5pt 0 0 0;
            line-height: 1;
        }

        /* ---- Placeholder cell (empty slot on last row) ---- */
        .card-empty {
            width: 242.64pt;
            height: 153.01pt;
        }

        /* ---- Page break between every 10 cards (after 2 rows of 5 = 10 cells) ---- */
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
@php
    /*
     * We chunk the customers into groups of 10 (one A4 page).
     * Each page holds 5 rows × 2 columns.
     */
    $pages   = $customers->chunk(10);
    $pageCount = $pages->count();
    $pageIndex = 0;
@endphp

@foreach($pages as $page)
@php $pageIndex++; @endphp
<div class="{{ $pageIndex < $pageCount ? 'page-break' : '' }}">
    @php
        // Pair customers into rows of 2
        $rows = $page->chunk(2);
    @endphp

    <table class="grid">
        @foreach($rows as $row)
        <tr class="grid-row">
            @foreach($row as $customer)
            @php $memberCard = $customer->memberCard; @endphp
            <td class="grid-cell">
                <div class="card">
                    <div class="qr-container">
                        <img src="data:image/svg+xml;base64,{!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::size(45)->margin(1)->generate($memberCard->qr_token)) !!}"
                             alt="QR Code" width="45" height="45">
                    </div>
                    <div class="text-container">
                        <p class="member-code">{{ $memberCard->member_code }}</p>
                        <p class="customer-name">{{ $customer->customer_name }}</p>
                    </div>
                </div>
            </td>
            @endforeach

            {{-- Fill empty cell if row has only 1 card --}}
            @if($row->count() < 2)
            <td class="grid-cell">
                <div class="card-empty"></div>
            </td>
            @endif
        </tr>
        @endforeach
    </table>
</div>
@endforeach
</body>
</html>
