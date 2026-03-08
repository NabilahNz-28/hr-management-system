<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HR Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Sedikit custom untuk background aplikasi */
        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .bg-login-image {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            /* Jika nanti punya gambar ilustrasi, bisa masukkan di sini:
               background-image: url('link-gambar');
               background-size: cover; */
        }
    </style>
</head>
<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center min-vh-100">

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="max-width: 900px; width: 100%;">
            <div class="row g-0">

                <div class="col-lg-6 d-none d-lg-flex bg-login-image flex-column justify-content-center align-items-center text-white p-5">
                    <i class="fas fa-network-wired fa-4x mb-4 text-light opacity-75"></i>
                    <h2 class="fw-bold text-center">HR System ERP</h2>
                    <p class="text-center mt-3 opacity-75">
                        Portal manajemen sumber daya manusia terintegrasi. Kelola absensi, cuti, dan penggajian dalam satu platform yang aman.
                    </p>
                </div>

                <div class="col-lg-6 p-5 bg-white">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold text-dark">Selamat Datang</h4>
                        <p class="text-muted">Silakan login ke akun Anda</p>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                                <input type="email" class="form-control bg-light @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}"
                                       placeholder="admin@perusahaan.com" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="password" class="form-label fw-semibold mb-0">Password</label>
                                <a href="#" class="text-decoration-none small">Lupa password?</a>
                            </div>
                            <div class="input-group mt-2">
                                <span class="input-group-text bg-light"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" class="form-control bg-light @error('password') is-invalid @enderror"
                                       id="password" name="password" placeholder="••••••••" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label text-muted" for="remember">Ingat Saya</label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold">
                                Login Akses <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>

                        <div class="text-center mt-4 text-muted">
                            Belum mendaftarkan akun Karyawan? <br>
                            <a href="{{ route('register') }}" class="fw-bold text-decoration-none">Hubungi HRD atau Daftar di sini</a>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
