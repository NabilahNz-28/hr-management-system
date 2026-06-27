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

            function toggleMobile() {
                if (sidebar) sidebar.classList.toggle('mobile-open');
                if (sidebarOverlay) sidebarOverlay.classList.toggle('active');
            }

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (window.innerWidth <= 768) {
                        toggleMobile();
                    } else {
                        if (sidebar) sidebar.classList.toggle('collapsed');
                        if (mainContent) mainContent.classList.toggle('expanded');
                    }
                });
            }

            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleMobile();
                });
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    if (sidebar) sidebar.classList.remove('mobile-open');
                    sidebarOverlay.classList.remove('active');
                });
            }

            if (sidebar) {
                const links = sidebar.querySelectorAll('a');
                links.forEach(link => {
                    link.addEventListener('click', function() {
                        if (window.innerWidth <= 768) {
                            sidebar.classList.remove('mobile-open');
                            if (sidebarOverlay) sidebarOverlay.classList.remove('active');
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>
