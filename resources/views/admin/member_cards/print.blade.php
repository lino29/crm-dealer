<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Member Card {{ $memberCard->member_code }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }
        .card {
            width: 100%;
            height: 100%;
            border-radius: 10px;
            background: linear-gradient(to right, #3b82f6, #4f46e5);
            color: white;
            position: relative;
            overflow: hidden;
            box-sizing: border-box;
        }
        .left-col {
            float: left;
            width: 65%;
            padding: 15px;
            box-sizing: border-box;
        }
        .right-col {
            float: right;
            width: 35%;
            background-color: white;
            height: 100%;
            text-align: center;
            padding-top: 15px;
            box-sizing: border-box;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 10px;
            margin-top: 2px;
            opacity: 0.8;
        }
        .details {
            margin-top: 30px;
        }
        .member-code {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 2px;
            margin: 0;
        }
        .customer-name {
            font-size: 14px;
            text-transform: uppercase;
            margin-top: 5px;
        }
        .qr-wrapper {
            display: inline-block;
            background-color: white;
            padding: 5px;
            border-radius: 5px;
        }
        .scan-text {
            color: black;
            font-size: 10px;
            font-weight: bold;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="left-col">
            <div>
                <p class="company-name">{{ config('app.name', 'PT. Trijaya Motor') }}</p>
                <p class="subtitle">CRM Member Card</p>
            </div>
            <div class="details">
                <p class="member-code">{{ $memberCard->member_code }}</p>
                <p class="customer-name">{{ $customer->customer_name }}</p>
            </div>
        </div>
        <div class="right-col">
            <div class="qr-wrapper">
                <img src="data:image/svg+xml;base64,{!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::size(90)->generate($memberCard->qr_token)) !!}" alt="QR Code" width="90" height="90">
            </div>
            <p class="scan-text">Scan Me</p>
        </div>
    </div>
</body>
</html>
