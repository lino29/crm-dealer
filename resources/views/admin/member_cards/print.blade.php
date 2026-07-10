<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Member Card {{ $memberCard->member_code }}</title>
    <style>
        @page {
            margin: 0;
            size: 242.64pt 153.01pt;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }
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
        .left-col {
            position: absolute;
            left: 18pt;
            bottom: 18pt;
            width: 140pt;
        }
        .right-col {
            position: absolute;
            right: 18pt;
            top: 22pt;
            width: 60pt;
            text-align: center;
        }
        .company-name {
            position: absolute;
            left: 18pt;
            top: 15pt;
            font-size: 13pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            color: #111827;
            letter-spacing: 0.5px;
        }
        .subtitle {
            position: absolute;
            left: 18pt;
            top: 30pt;
            font-size: 7pt;
            font-weight: bold;
            margin: 0;
            color: #4b5563;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .member-code {
            font-size: 11pt;
            font-weight: bold;
            letter-spacing: 1.5px;
            margin: 0;
            color: #312e81; /* Indigo 900 */
        }
        .customer-name {
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 2pt 0 0 0;
            color: #1f2937;
        }
        .qr-wrapper {
            display: inline-block;
            background-color: white;
            padding: 4pt;
            border-radius: 4pt;
            border: 1px solid #f3f4f6;
        }
        .scan-text {
            color: #111827;
            font-size: 6pt;
            font-weight: bold;
            margin: 3pt 0 0 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>
    <div class="card">
        <p class="company-name">{{ config('app.name', 'PT. Trijaya Motor') }}</p>
        <p class="subtitle">CRM Member Card</p>
        
        <div class="left-col">
            <p class="member-code">{{ $memberCard->member_code }}</p>
            <p class="customer-name">{{ $customer->customer_name }}</p>
        </div>
        
        <div class="right-col">
            <div class="qr-wrapper">
                <img src="data:image/svg+xml;base64,{!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::size(50)->generate($memberCard->qr_token)) !!}" alt="QR Code" width="50" height="50">
            </div>
            <p class="scan-text">Scan Me</p>
        </div>
    </div>
</body>
</html>
