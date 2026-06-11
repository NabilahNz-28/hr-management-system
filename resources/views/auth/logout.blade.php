<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Logout</title>
</head>
<body>
    <form id="logout-form" action="{{ route('logout') }}" method="POST">
        @csrf
    </form>

    <!-- Modal Konfirmasi Logout -->
<div id="logoutModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-icon warning">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                <line x1="12" y1="9" x2="12" y2="13"></line>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
        </div>
        <h3 id="modalTitle">Konfirmasi Logout</h3>
        <p id="modalMessage">Apakah Anda yakin ingin keluar?</p>
        <div class="modal-actions">
            <button type="button" class="btn btn-cancel" onclick="closeModal()">Batal</button>
            <button type="button" class="btn btn-danger" id="confirmLogoutBtn">Ya, Logout</button>
        </div>
    </div>
</div>

<!-- Form tersembunyi untuk proses Laravel -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
    <script>
        if (confirm('Apakah Anda yakin ingin logout?')) {
            document.getElementById('logout-form').submit();
        } else {
            window.history.back();
        }
    </script>
</body>
</html>