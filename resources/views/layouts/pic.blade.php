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
    </script>
    <style>
        /* Styling agar notifikasi & konfirmasi SweetAlert berukuran kompak, rapi, dan tidak terlalu besar */
        .swal2-popup.compact-swal-popup {
            border-radius: 12px !important;
            padding: 1.2rem !important;
            width: 360px !important;
            max-width: 90% !important;
        }
        .swal2-title.compact-swal-title {
            font-size: 1.05rem !important;
            font-weight: 600 !important;
            color: #1e293b !important;
            margin-bottom: 0.25rem !important;
            line-height: 1.3 !important;
        }
        .swal2-html-container.compact-swal-text {
            font-size: 0.825rem !important;
            line-height: 1.55 !important;
            color: #475569 !important;
            margin: 0.5rem 0 1rem 0 !important;
            white-space: pre-line !important;
            text-align: left !important;
        }
        .swal2-actions {
            margin-top: 0.5rem !important;
        }
        .swal2-confirm.compact-swal-btn, .swal2-cancel.compact-swal-btn {
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            padding: 7px 16px !important;
            border-radius: 6px !important;
        }

        /* Styling Container & Mini Toast Formal Kanan Atas (Role PIC/Inventory) */
        #pic-toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 9999999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
            max-width: 360px;
            width: calc(100% - 48px);
        }
        .pic-mini-toast {
            pointer-events: auto;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-left: 4px solid #0f172a;
            border-radius: 10px;
            box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.1), 0 4px 6px -4px rgba(15, 23, 42, 0.05);
            padding: 12px 16px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            opacity: 0;
            transform: translateX(30px);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }
        .pic-mini-toast.show {
            opacity: 1;
            transform: translateX(0);
        }
        .pic-mini-toast.hide {
            opacity: 0;
            transform: translateX(30px);
        }
        .pic-toast-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
            margin-top: 1px;
        }
        .pic-toast-icon.success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }
        .pic-toast-icon.error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        .pic-toast-icon.warning {
            background: #fffbeb;
            color: #d97706;
            border: 1px solid #fde68a;
        }
        .pic-toast-icon.info {
            background: #f0f9ff;
            color: #0284c7;
            border: 1px solid #bae6fd;
        }
        .pic-toast-content {
            flex: 1;
            min-width: 0;
        }
        .pic-toast-title {
            font-weight: 600;
            font-size: 13.5px;
            color: #0f172a;
            margin-bottom: 2px;
            line-height: 1.3;
        }
        .pic-toast-message {
            font-size: 12.5px;
            color: #475569;
            line-height: 1.45;
            word-wrap: break-word;
        }
        .pic-toast-close {
            background: transparent;
            border: none;
            color: #94a3b8;
            font-size: 16px;
            line-height: 1;
            padding: 2px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: all 0.15s ease;
            margin-left: 4px;
        }
        .pic-toast-close:hover {
            background: #f1f5f9;
            color: #475569;
        }
        .pic-toast-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 2.5px;
            background: #0f172a;
            width: 100%;
            opacity: 0.15;
        }
        .pic-toast-bar.success { background: #16a34a; opacity: 0.8; }
        .pic-toast-bar.error { background: #dc2626; opacity: 0.8; }
        .pic-toast-bar.warning { background: #d97706; opacity: 0.8; }
        .pic-toast-bar.info { background: #0284c7; opacity: 0.8; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Container creation helper untuk Mini Toast Kanan Atas
        function getToastContainer() {
            let container = document.getElementById('pic-toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'pic-toast-container';
                document.body.appendChild(container);
            }
            return container;
        }

        // Global Helper untuk Mini Toast formal di kanan atas (role PIC & Inventory)
        window.showFormalToast = function(message, type = 'info', title = null) {
            const cleanMessage = String(message).replace(/[\u2700-\u27BF]|[\uE000-\uF8FF]|\uD83C[\uDC00-\uDFFF]|\uD83D[\uDC00-\uDFFF]|[\u2011-\u26FF]|\uD83E[\uDD10-\uDDFF]/g, '').trim();
            if (!title) {
                title = type === 'success' ? 'Berhasil' : (type === 'error' ? 'Gagal' : (type === 'warning' ? 'Peringatan' : 'Pemberitahuan'));
            }

            const container = getToastContainer();
            const toast = document.createElement('div');
            toast.className = 'pic-mini-toast';

            let iconClass = 'bi-info-circle';
            if (type === 'success') iconClass = 'bi-check-lg';
            if (type === 'error') iconClass = 'bi-exclamation-triangle';
            if (type === 'warning') iconClass = 'bi-exclamation-circle';

            toast.innerHTML = `
                <div class="pic-toast-icon ${type}">
                    <i class="bi ${iconClass}"></i>
                </div>
                <div class="pic-toast-content">
                    <div class="pic-toast-title">${title}</div>
                    <div class="pic-toast-message">${cleanMessage}</div>
                </div>
                <button type="button" class="pic-toast-close" onclick="dismissPicToast(this)">
                    <i class="bi bi-x"></i>
                </button>
                <div class="pic-toast-bar ${type}"></div>
            `;

            container.appendChild(toast);

            // Animate in
            requestAnimationFrame(() => {
                setTimeout(() => {
                    toast.classList.add('show');
                }, 10);
            });

            // Auto dismiss setelah 3.8 detik
            const timer = setTimeout(() => {
                dismissPicToastElement(toast);
            }, 3800);

            toast.dataset.timer = timer;
        };

        window.dismissPicToast = function(btn) {
            const toast = btn.closest('.pic-mini-toast');
            if (toast) dismissPicToastElement(toast);
        };

        function dismissPicToastElement(toast) {
            if (toast.dataset.timer) clearTimeout(toast.dataset.timer);
            toast.classList.remove('show');
            toast.classList.add('hide');
            setTimeout(() => {
                if (toast && toast.parentNode) {
                    toast.remove();
                }
            }, 250);
        }

        // SEMUA notifikasi JADIKAN TOAST mini formal di kanan atas (kecuali konfirmasi & logout)
        window.showFormalAlert = window.showFormalToast;
        window.showToast = window.showFormalToast;

        // Untuk konfirmasi tindakan (pertanyaan Yes/No seperti Hapus atau Simpan form penting), tetap konfirmasi kompak
        window.showFormalConfirm = function(message, title = 'Konfirmasi Tindakan', confirmText = 'Ya, Lanjutkan', cancelText = 'Batal') {
            const cleanMessage = String(message).replace(/[\u2700-\u27BF]|[\uE000-\uF8FF]|\uD83C[\uDC00-\uDFFF]|\uD83D[\uDC00-\uDFFF]|[\u2011-\u26FF]|\uD83E[\uDD10-\uDDFF]/g, '').trim();
            return new Promise((resolve) => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: title,
                        text: cleanMessage,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: confirmText,
                        cancelButtonText: cancelText,
                        confirmButtonColor: '#0f172a',
                        cancelButtonColor: '#64748b',
                        reverseButtons: true,
                        customClass: {
                            popup: 'compact-swal-popup',
                            title: 'compact-swal-title',
                            htmlContainer: 'compact-swal-text',
                            confirmButton: 'compact-swal-btn',
                            cancelButton: 'compact-swal-btn'
                        }
                    }).then((result) => {
                        resolve(result.isConfirmed);
                    });
                } else {
                    resolve(confirm(cleanMessage));
                }
            });
        };

        window.confirmFormSubmit = async function(event, message, title = 'Konfirmasi Tindakan') {
            event.preventDefault();
            const form = event.target;
            const confirmed = await window.showFormalConfirm(message, title, 'Ya, Lanjutkan', 'Batal');
            if (confirmed) {
                form.submit();
            }
            return false;
        };
    </script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
          window.showFormalToast({!! json_encode(preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FA6F}\x{1FA70}-\x{1FAFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', session('success'))) !!}, 'success');
        @endif

        @if(session('error'))
          window.showFormalToast({!! json_encode(preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FA6F}\x{1FA70}-\x{1FAFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', session('error'))) !!}, 'error');
        @endif

        @if(session('warning'))
          window.showFormalToast({!! json_encode(preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FA6F}\x{1FA70}-\x{1FAFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', session('warning'))) !!}, 'warning');
        @endif

        @if(session('info'))
          window.showFormalToast({!! json_encode(preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FA6F}\x{1FA70}-\x{1FAFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', session('info'))) !!}, 'info');
        @endif

        @if($errors->any())
          @foreach($errors->all() as $err)
            window.showFormalToast({!! json_encode(preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FA6F}\x{1FA70}-\x{1FAFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', $err)) !!}, 'warning', 'Peringatan');
          @endforeach
        @endif
      });
    </script>
</body>
</html>
