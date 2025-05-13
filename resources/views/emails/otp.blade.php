<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kode OTP</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }
    .container {
      background-color: #ffffff;
      max-width: 600px;
      margin: 30px auto;
      padding: 30px;
      border-radius: 8px;
      text-align: center;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .logo {
      margin-bottom: 20px;
    }
    .otp {
      font-size: 32px;
      font-weight: bold;
      letter-spacing: 4px;
      color: #003366;
      margin: 20px 0;
    }
    .footer {
      font-size: 12px;
      color: #888;
      margin-top: 30px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo">
      <img src="{{ assets('img/logo-1.png') }}" alt="Rocker Tech Logo" width="200">
    </div>
    <h2>Verifikasi OTP Anda</h2>
    <p>Gunakan kode OTP berikut untuk melanjutkan proses Anda. Kode ini hanya berlaku selama <strong>5 menit</strong>:</p>
    <div class="otp">{{ $otp }}</div>
    <p>Jaga kerahasiaan kode ini dan jangan memberikannya kepada siapa pun.</p>
    <div class="footer">
      &copy; {{ now()->year }} Rocker Tech - IT Consultant. All rights reserved.
    </div>
  </div>
</body>
</html>
