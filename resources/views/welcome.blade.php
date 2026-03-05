<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .feature-card {
            transition: transform 0.3s;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-10px);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-chart-line"></i> HR Management System
            </a>
            <div class="navbar-nav ms-auto">
                <a href="{{ route('login') }}" class="nav-link">Login</a>
                <a href="{{ route('register') }}" class="nav-link">Register</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">HR Management System</h1>
            <p class="lead mb-5">Sistem Terintegrasi untuk Absensi, Penggajian, dan Inventory Gudang</p>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="card-body text-center">
                            <div class="feature-icon text-primary">
                                <i class="fas fa-fingerprint"></i>
                            </div>
                            <h5>Sistem Absensi</h5>
                            <p>Digital attendance tracking with check-in/out system, leave management, and real-time reporting.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="card-body text-center">
                            <div class="feature-icon text-success">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <h5>Penggajian Otomatis</h5>
                            <p>Automated payroll calculation, tax deductions, allowances, and salary slip generation.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="card-body text-center">
                            <div class="feature-icon text-warning">
                                <i class="fas fa-warehouse"></i>
                            </div>
                            <h5>Inventory Gudang</h5>
                            <p>Warehouse management with stock tracking, item categorization, and low stock alerts.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5">
                <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 me-3">
                    <i class="fas fa-user-plus"></i> Get Started
                </a>
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-5">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2>Role-Based Access Control</h2>
                    <p class="lead">Different roles with specific permissions:</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <i class="fas fa-user-shield text-primary"></i> 
                            <strong>Admin</strong> - Full system access
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-warehouse text-warning"></i> 
                            <strong>Admin Gudang</strong> - Inventory management
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-user-tie text-info"></i> 
                            <strong>PIC</strong> - Designated item responsibility
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-user text-success"></i> 
                            <strong>Karyawan</strong> - Attendance and salary view
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <img src="https://via.placeholder.com/500x300/667eea/ffffff?text=Dashboard+Preview" 
                         alt="Dashboard Preview" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} HR Management System. All rights reserved.</p>
            <small>Built with Laravel 10 & Bootstrap 5</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>