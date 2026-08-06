<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      background-color: #f1f5f9;
      font-family: 'Segoe UI', Arial, sans-serif;
      color: #1e293b;
    }

    .wrapper {
      max-width: 580px;
      margin: 40px auto;
      padding: 0 16px 40px;
    }

    /* ===== HEADER ===== */
    .header {
      background: linear-gradient(135deg, #cc0000 0%, #990000 100%);
      border-radius: 16px 16px 0 0;
      padding: 36px 40px 32px;
      text-align: center;
    }

    .header-logo {
      font-size: 28px;
      font-weight: 900;
      color: #ffffff;
      letter-spacing: -0.5px;
    }

    .header-logo span {
      color: #ffd700;
    }

    .header-subtitle {
      color: rgba(255,255,255,0.80);
      font-size: 13px;
      margin-top: 4px;
      letter-spacing: 0.5px;
      text-transform: uppercase;
    }

    /* ===== BODY CARD ===== */
    .card {
      background: #ffffff;
      padding: 40px;
      border-left: 1px solid #e2e8f0;
      border-right: 1px solid #e2e8f0;
    }

    .greeting {
      font-size: 20px;
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 12px;
    }

    .body-text {
      font-size: 14.5px;
      line-height: 1.7;
      color: #475569;
      margin-bottom: 28px;
    }

    /* ===== TOMBOL RESET ===== */
    .btn-container {
      text-align: center;
      margin: 28px 0;
    }

    .btn-reset {
      display: inline-block;
      background: linear-gradient(135deg, #cc0000 0%, #990000 100%);
      color: #ffffff !important;
      text-decoration: none;
      font-size: 15px;
      font-weight: 700;
      padding: 14px 40px;
      border-radius: 10px;
      letter-spacing: 0.3px;
      box-shadow: 0 4px 14px rgba(204,0,0,0.35);
    }

    /* ===== INFO EXPIRE ===== */
    .info-box {
      background: #fff7ed;
      border: 1px solid #fed7aa;
      border-radius: 10px;
      padding: 14px 18px;
      margin: 24px 0;
      display: flex;
      align-items: flex-start;
      gap: 10px;
    }

    .info-icon {
      font-size: 18px;
      flex-shrink: 0;
      margin-top: 1px;
    }

    .info-text {
      font-size: 13px;
      color: #92400e;
      line-height: 1.6;
    }

    /* ===== DIVIDER ===== */
    .divider {
      border: none;
      border-top: 1px solid #e2e8f0;
      margin: 28px 0;
    }

    /* ===== URL FALLBACK ===== */
    .url-section p {
      font-size: 12.5px;
      color: #64748b;
      margin-bottom: 8px;
    }

    .url-box {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 11px;
      color: #3b82f6;
      word-break: break-all;
      line-height: 1.6;
    }

    /* ===== SECURITY NOTE ===== */
    .security-note {
      font-size: 12.5px;
      color: #94a3b8;
      line-height: 1.6;
      margin-top: 20px;
    }

    /* ===== FOOTER ===== */
    .footer {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-top: none;
      border-radius: 0 0 16px 16px;
      padding: 24px 40px;
      text-align: center;
    }

    .footer-company {
      font-size: 13px;
      font-weight: 700;
      color: #334155;
      margin-bottom: 4px;
    }

    .footer-addr {
      font-size: 11.5px;
      color: #94a3b8;
      line-height: 1.6;
    }

    .footer-note {
      font-size: 11px;
      color: #cbd5e1;
      margin-top: 14px;
    }
  </style>
</head>
<body>
  <div class="wrapper">

    <!-- CARD CONTENT -->
    <div class="card">

      <p class="greeting">Halo, {{ $userName }} 👋</p>

      <p class="body-text">
        Kami menerima permintaan untuk mereset password akun Anda di
        <strong>HR Management System J&T Express</strong>.<br><br>
        Klik tombol di bawah ini untuk membuat password baru. Tautan ini
        hanya bisa digunakan <strong>satu kali</strong> dan akan
        <strong>kedaluwarsa dalam {{ $expireMin }} menit</strong>.
      </p>

      <!-- TOMBOL RESET -->
      <div class="btn-container">
        <a href="{{ $resetUrl }}" class="btn-reset">
          🔐 &nbsp; Reset Password Sekarang
        </a>
      </div>

      <!-- INFO EXPIRE -->
      <div class="info-box">
        <div class="info-icon">⏰</div>
        <div class="info-text">
          <strong>Penting:</strong> Tautan reset password ini akan kedaluwarsa
          dalam <strong>{{ $expireMin }} menit</strong> sejak email ini dikirim.
          Jika sudah melewati waktu tersebut, silakan ajukan permintaan reset password baru.
        </div>
      </div>

      <hr class="divider">

      <!-- URL FALLBACK -->
      <div class="url-section">
        <p>Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut ke browser Anda:</p>
        <div class="url-box">{{ $resetUrl }}</div>
      </div>

      <!-- SECURITY NOTE -->
      <p class="security-note">
        🛡️ Jika Anda tidak merasa meminta reset password, abaikan email ini.
        Akun Anda tetap aman dan tidak akan berubah selama Anda tidak mengklik
        tautan di atas.
      </p>

    </div>

    <!-- FOOTER -->
    <div class="footer">
      <div class="footer-company">HR Management System</div>
      <div class="footer-addr">
        J&T Express Indonesia &bull; {{ date('Y') }}
      </div>
      <div class="footer-note">
        Email ini dikirim secara otomatis, mohon tidak membalas email ini.
      </div>
    </div>

  </div>
</body>
</html>
