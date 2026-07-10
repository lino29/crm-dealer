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
    </style>
</head>
<body>
    <div class="card">
        <!-- QR Code inside the gray box at bottom-left -->
        <div class="qr-container">
            <img src="data:image/svg+xml;base64,{!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::size(45)->margin(1)->generate($memberCard->qr_token)) !!}" alt="QR Code" width="45" height="45">
        </div>
        
        <!-- Member Code and Customer Name below the QR Code (White text on dark blue background) -->
        <div class="text-container">
            <p class="member-code">{{ $memberCard->member_code }}</p>
            <p class="customer-name">{{ $customer->customer_name }}</p>
        </div>
    </div>
</body>
</html>
