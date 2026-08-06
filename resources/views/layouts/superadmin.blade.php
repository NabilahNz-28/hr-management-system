<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Superadmin')</title>
  <link rel="icon" type="image/jpeg" href="{{ asset('photos/LOGO.jpeg') }}">

  <!-- Bootstrap 5 CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
  @yield('styles')
</head>
<body>

  <!-- Mobile Menu Button -->
  <button class="mobile-menu-btn" id="mobileMenuBtn">☰</button>

  <!-- Sidebar Overlay -->
  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  @include('layouts.sidebar-superadmin')
  <div id="main-content" class="main-content">
    @include('layouts.topbar-absen')

    <div class="dashboard-content">
      @yield('content')
    </div>
  </div>

  <!-- Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
      border: none !important;
    }
    .swal2-confirm:focus, .swal2-cancel:focus, .swal2-close:focus {
      box-shadow: none !important;
      outline: none !important;
    }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{{ asset('js/script.js') }}"></script>
  <script>
    window.showFormalAlert = function(message, icon = 'info', title = null) {
        const cleanMessage = String(message).replace(/[\u2700-\u27BF]|[\uE000-\uF8FF]|\uD83C[\uDC00-\uDFFF]|\uD83D[\uDC00-\uDFFF]|[\u2011-\u26FF]|\uD83E[\uDD10-\uDDFF]/g, '').trim();
        if (!title) {
            title = icon === 'success' ? 'Berhasil' : (icon === 'error' ? 'Gagal' : (icon === 'warning' ? 'Peringatan' : 'Pemberitahuan'));
        }
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: title,
                text: cleanMessage,
                icon: icon,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#0f172a',
                customClass: {
                    popup: 'compact-swal-popup',
                    title: 'compact-swal-title',
                    htmlContainer: 'compact-swal-text',
                    confirmButton: 'compact-swal-btn'
                }
            });
        } else {
            alert(cleanMessage);
        }
    };
    window.showToast = window.showFormalAlert;

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

    document.addEventListener('DOMContentLoaded', function() {
      @if(session('success'))
        if (typeof Swal !== 'undefined') {
          const successMsg = {!! json_encode(preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FA6F}\x{1FA70}-\x{1FAFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', session('success'))) !!};
          const isToast = {!! json_encode(session('is_toast') || false) !!};

          if (isToast) {
            Swal.fire({
              toast: true,
              position: 'top-end',
              icon: 'success',
              title: successMsg,
              showConfirmButton: false,
              timer: 3500,
              timerProgressBar: true
            });
          } else {
            Swal.fire({
              title: 'Berhasil',
              text: successMsg,
              icon: 'success',
              confirmButtonText: 'Tutup',
              confirmButtonColor: '#0f172a',
              customClass: {
                popup: 'compact-swal-popup',
                title: 'compact-swal-title',
                htmlContainer: 'compact-swal-text',
                confirmButton: 'compact-swal-btn'
              }
            });
          }
        }
      @endif

      @if(session('error'))
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            title: 'Gagal',
            text: {!! json_encode(preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FA6F}\x{1FA70}-\x{1FAFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', session('error'))) !!},
            icon: 'error',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#0f172a',
            customClass: {
              popup: 'compact-swal-popup',
              title: 'compact-swal-title',
              htmlContainer: 'compact-swal-text',
              confirmButton: 'compact-swal-btn'
            }
          });
        }
      @endif

      @if($errors->any())
        if (typeof Swal !== 'undefined') {
          let errorList = [
            @foreach($errors->all() as $err)
              {!! json_encode(preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FA6F}\x{1FA70}-\x{1FAFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', $err)) !!},
            @endforeach
          ];
          Swal.fire({
            title: 'Peringatan',
            html: '<div style="text-align: center; margin: 0; padding: 0;">' + errorList.join('<br>') + '</div>',
            icon: 'warning',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#0f172a',
            customClass: {
              popup: 'compact-swal-popup',
              title: 'compact-swal-title',
              htmlContainer: 'compact-swal-text',
              confirmButton: 'compact-swal-btn'
            }
          });
        }
      @endif
    });
  </script>
  <script>
    // Mobile sidebar toggle — matches PIC layout behavior
    document.addEventListener('DOMContentLoaded', function() {
        var sidebar = document.getElementById('sidebar');
        var mainContent = document.getElementById('main-content');
        var toggleBtn = document.getElementById('sidebarToggle') || document.getElementById('toggle-sidebar');
        var mobileMenuBtn = document.getElementById('mobileMenuBtn');
        var sidebarOverlay = document.getElementById('sidebarOverlay');

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
            var links = sidebar.querySelectorAll('a');
            links.forEach(function(link) {
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
  @yield('scripts')
</body>
</html>
