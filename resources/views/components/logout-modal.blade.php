<!-- Custom Formal Logout Popup Modal -->
<div id="customLogoutModal" class="custom-logout-overlay" onclick="if(event.target === this) closeLogoutModal();">
    <div class="custom-logout-box">
        <div class="custom-logout-header">
            <div class="header-title-wrap">
                <div class="header-icon-box">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                </div>
                <h3 class="custom-logout-title">Konfirmasi Keluar</h3>
            </div>
            <button type="button" class="btn-close-modal" onclick="closeLogoutModal()" aria-label="Tutup">&times;</button>
        </div>
        <div class="custom-logout-body">
            <p class="custom-logout-desc">
                Apakah Anda yakin ingin keluar dari sistem?
            </p>
        </div>
        <div class="custom-logout-footer">
            <button type="button" class="btn-formal-cancel" onclick="closeLogoutModal()">Batal</button>
            <button type="button" class="btn-formal-confirm" onclick="proceedLogout()">Keluar</button>
        </div>
    </div>
</div>

<style>
.custom-logout-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    z-index: 999999;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.custom-logout-overlay.show {
    display: flex !important;
    opacity: 1;
}

.custom-logout-box {
    background: #ffffff;
    width: 90%;
    max-width: 440px;
    border-radius: 10px;
    text-align: left;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
    border: 1px solid #cbd5e1;
    border-top: 4px solid #b91c1c;
    overflow: hidden;
    transform: scale(0.96) translateY(10px);
    transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

.custom-logout-overlay.show .custom-logout-box {
    transform: scale(1) translateY(0);
}

.custom-logout-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 24px 14px;
}

.header-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
}

.header-icon-box {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: #fef2f2;
    border: 1px solid #fee2e2;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #b91c1c;
    flex-shrink: 0;
}

.custom-logout-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
}

.btn-close-modal {
    background: transparent;
    border: none;
    font-size: 1.5rem;
    line-height: 1;
    color: #94a3b8;
    cursor: pointer;
    padding: 0;
    transition: color 0.15s ease;
}

.btn-close-modal:hover {
    color: #334155;
}

.custom-logout-body {
    padding: 0 24px 24px;
}

.custom-logout-desc {
    font-size: 0.925rem;
    color: #475569;
    margin: 0;
    line-height: 1.6;
}

.custom-logout-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding: 16px 24px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
}

.btn-formal-cancel, .btn-formal-confirm {
    padding: 9px 18px;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
    font-family: inherit;
}

.btn-formal-cancel {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    color: #334155;
}

.btn-formal-cancel:hover {
    background: #f1f5f9;
    border-color: #94a3b8;
    color: #0f172a;
}

.btn-formal-confirm {
    background: #b91c1c;
    border: 1px solid #b91c1c;
    color: #ffffff;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.btn-formal-confirm:hover {
    background: #991b1b;
    border-color: #991b1b;
}
</style>

<script>
function confirmLogout() {
    const modal = document.getElementById('customLogoutModal');
    if (modal) {
        modal.classList.add('show');
    }
}

function closeLogoutModal() {
    const modal = document.getElementById('customLogoutModal');
    if (modal) {
        modal.classList.remove('show');
    }
}

function proceedLogout() {
    const form = document.getElementById('logout-form');
    if (form) {
        form.submit();
    }
}
</script>
