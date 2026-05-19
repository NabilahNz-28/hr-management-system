<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard PIC Inventory')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom PIC CSS -->
    <link rel="stylesheet" href="{{ asset('css/pic.css') }}?v={{ time() }}">
    
    @yield('styles')
</head>
<body>
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" id="mobileMenuBtn">☰</button>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    @include('layouts.sidebar-pic')

    <!-- Main Content -->
    <div id="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <button class="toggle-btn" id="toggle-sidebar">
                <i class="bi bi-list"></i>
            </button>

            <div class="user-menu">
                <button class="notification-btn">
                    <i class="bi bi-bell"></i>
                    <div class="badge"></div>
                </button>

                <div class="user-avatar">
                    PIC
                </div>
            </div>
        </div>

        <div class="dashboard-content">
            @yield('content')
        </div>
    </div>

    @yield('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const toggleBtn = document.getElementById('toggle-sidebar');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            if (toggleBtn && sidebar && mainContent) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                });
            }

            if (mobileMenuBtn && sidebar && sidebarOverlay) {
                mobileMenuBtn.addEventListener('click', function() {
                    sidebar.classList.add('mobile-open');
                    sidebarOverlay.classList.add('active');
                });

                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('mobile-open');
                    sidebarOverlay.classList.remove('active');
                });
            }
        });
    </script>
</body>
</html>
