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
    <style>
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .toast-notification {
            transition: opacity 0.3s ease;
        }
    </style>
    <!-- Floating Top-Right Notification Toast -->
    <div id="toast-container" style="position: fixed; top: 24px; right: 24px; z-index: 99998; display: flex; flex-direction: column; gap: 10px;">
        @if(session('success'))
            <div class="toast-notification" style="background: #ffffff; border-left: 4px solid #10b981; box-shadow: 0 10px 25px rgba(0,0,0,0.12); padding: 14px 20px; border-radius: 8px; display: flex; align-items: center; gap: 12px; min-width: 280px; max-width: 380px; animation: slideInRight 0.3s cubic-bezier(0.16, 1, 0.3, 1);">
                <div style="background: #d1fae5; color: #059669; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div style="flex: 1;">
                    <div style="font-size: 13px; font-weight: 600; color: #1e293b;">Berhasil</div>
                    <div style="font-size: 12px; color: #64748b; margin-top: 2px;">{{ session('success') }}</div>
                </div>
                <button onclick="this.parentElement.remove()" style="background: transparent; border: none; color: #94a3b8; cursor: pointer; padding: 4px; font-size: 16px; line-height: 1;">&times;</button>
            </div>
        @endif
        @if(session('error'))
            <div class="toast-notification" style="background: #ffffff; border-left: 4px solid #ef4444; box-shadow: 0 10px 25px rgba(0,0,0,0.12); padding: 14px 20px; border-radius: 8px; display: flex; align-items: center; gap: 12px; min-width: 280px; max-width: 380px; animation: slideInRight 0.3s cubic-bezier(0.16, 1, 0.3, 1);">
                <div style="background: #fee2e2; color: #dc2626; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </div>
                <div style="flex: 1;">
                    <div style="font-size: 13px; font-weight: 600; color: #1e293b;">Gagal</div>
                    <div style="font-size: 12px; color: #64748b; margin-top: 2px;">{{ session('error') }}</div>
                </div>
                <button onclick="this.parentElement.remove()" style="background: transparent; border: none; color: #94a3b8; cursor: pointer; padding: 4px; font-size: 16px; line-height: 1;">&times;</button>
            </div>
        @endif
    </div>

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

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

            // Auto dismiss toast dari server setelah 4 detik
            setTimeout(() => {
                document.querySelectorAll('.toast-notification').forEach(t => {
                    t.style.opacity = '0';
                    setTimeout(() => t.remove(), 300);
                });
            }, 4000);
        });

        // Global Helper untuk memunculkan toast secara dinamis lewat JS
        window.showToast = function(message, type = 'success') {
            let container = document.getElementById('toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toast-container';
                container.style.cssText = 'position: fixed; top: 24px; right: 24px; z-index: 99998; display: flex; flex-direction: column; gap: 10px;';
                document.body.appendChild(container);
            }
            const color = type === 'success' ? '#10b981' : '#ef4444';
            const bgColor = type === 'success' ? '#d1fae5' : '#fee2e2';
            const iconColor = type === 'success' ? '#059669' : '#dc2626';
            const title = type === 'success' ? 'Berhasil' : 'Pemberitahuan';
            const toast = document.createElement('div');
            toast.className = 'toast-notification';
            toast.style.cssText = `background: #ffffff; border-left: 4px solid ${color}; box-shadow: 0 10px 25px rgba(0,0,0,0.12); padding: 14px 20px; border-radius: 8px; display: flex; align-items: center; gap: 12px; min-width: 280px; max-width: 380px; animation: slideInRight 0.3s cubic-bezier(0.16, 1, 0.3, 1); transition: opacity 0.3s;`;
            toast.innerHTML = `
                <div style="background: ${bgColor}; color: ${iconColor}; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div style="flex: 1;">
                    <div style="font-size: 13px; font-weight: 600; color: #1e293b;">${title}</div>
                    <div style="font-size: 12px; color: #64748b; margin-top: 2px;">${message}</div>
                </div>
                <button onclick="this.parentElement.remove()" style="background: transparent; border: none; color: #94a3b8; cursor: pointer; padding: 4px; font-size: 16px; line-height: 1;">&times;</button>
            `;
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        };
    </script>
</body>
</html>
