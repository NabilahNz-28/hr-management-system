// js/script.js - FIXED VERSION
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard loaded!');
    
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn') || sidebarToggle;
    const menuItems = document.querySelectorAll('.menu-item');
    const pageContents = document.querySelectorAll('.page-content');
    const mainPageTitle = document.getElementById('mainPageTitle');
    const mainPageSubtitle = document.getElementById('mainPageSubtitle');
    
    // Create overlay for mobile
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    overlay.style.cssText = `
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0,0,0,0.5);
        z-index: 999;
        backdrop-filter: blur(2px);
    `;
    document.body.appendChild(overlay);
    
    function openMobileSidebar() {
        if (window.innerWidth > 768) return;
        if (sidebar) sidebar.classList.add('mobile-open');
        document.body.classList.add('sidebar-open');
        overlay.style.display = 'block';
    }

    function closeMobileSidebar() {
        if (sidebar) sidebar.classList.remove('mobile-open');
        document.body.classList.remove('sidebar-open');
        overlay.style.display = 'none';
    }

    function toggleMobileSidebar(e) {
        if (e) e.stopPropagation();
        if (sidebar && sidebar.classList.contains('mobile-open')) {
            closeMobileSidebar();
        } else {
            openMobileSidebar();
        }
    }

    // Toggle sidebar - DESKTOP & MOBILE
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            if (window.innerWidth <= 768) {
                toggleMobileSidebar(e);
            } else {
                if (sidebar) sidebar.classList.toggle('collapsed');
                if (mainContent) mainContent.classList.toggle('expanded');
            }
        });
    }

    if (mobileMenuBtn && mobileMenuBtn !== sidebarToggle) {
        mobileMenuBtn.addEventListener('click', toggleMobileSidebar);
    }

overlay.addEventListener('click', function() {
    closeMobileSidebar();
});

document.addEventListener('click', function(event) {
    if (
        window.innerWidth <= 768 &&
        sidebar.classList.contains('mobile-open') &&
        !sidebar.contains(event.target) &&
        !mobileMenuBtn.contains(event.target)
    ) {
        closeMobileSidebar();
    }
});


    // Handle menu item clicks
    function handleMenuItemClick(e) {
        const pageId = this.getAttribute('data-page');

        if (!pageId) {
            return;
        }

        e.preventDefault();
        
        // Logout
        if (pageId === 'logout') {
            if (typeof window.showFormalConfirm === 'function') {
                window.showFormalConfirm('Apakah Anda yakin ingin keluar dari sistem?', 'Konfirmasi Logout', 'Ya, Keluar', 'Batal').then(confirmed => {
                    if (confirmed) {
                        if (typeof window.showFormalAlert === 'function') {
                            window.showFormalAlert('Logout berhasil!', 'success', 'Berhasil');
                        }
                    }
                });
            } else if (confirm('Apakah Anda yakin ingin keluar dari sistem?')) {
                alert('Logout berhasil!');
            }
            return;
        }
        
        // Update active menu
        menuItems.forEach(menuItem => {
            menuItem.classList.remove('active');
        });
        this.classList.add('active');
        
        if (window.innerWidth <= 768) {
    closeMobileSidebar();
}
        
        // Show page content
        showPageContent(pageId);
    }
    
    menuItems.forEach(item => {
    item.addEventListener('click', function(e) {
        const pageId = this.getAttribute('data-page');

        if (window.innerWidth <= 768) {
            closeMobileSidebar();
        }

        // Kalau menu Laravel biasa, biarkan href jalan normal
        if (!pageId) {
            return;
        }

        handleMenuItemClick.call(this, e);
    });
});
    
    // Function to show page content
    function showPageContent(pageId) {
        // Hide all page contents
        pageContents.forEach(content => {
            content.classList.remove('active');
        });
        
        // Show selected page content
        const targetPage = document.getElementById(pageId);
        if (targetPage) {
            targetPage.classList.add('active');
            
            // Update page title and subtitle
            const pageData = {
                'dashboard-home': ['Dashboard Absensi', 'Selamat datang! Sistem absensi dengan GPS dan face recognition'],
                'absensi-masuk': ['Absensi Masuk', 'Lakukan absensi masuk dengan foto wajah dan GPS'],
                'absensi-pulang': ['Absensi Pulang', 'Lakukan absensi pulang dengan foto wajah dan GPS'],
                'absensi-izin': ['Pengajuan Izin', 'Ajukan izin tidak masuk kerja dengan alasan yang jelas'],
                'absensi-cuti': ['Pengajuan Cuti', 'Ajukan cuti tahunan, melahirkan, atau khusus'],
                'rekap-harian': ['Rekap Absensi Harian', 'Data absensi seluruh karyawan hari ini'],
                'rekap-bulanan': ['Rekap Absensi Bulanan', 'Statistik absensi bulan Desember 2025'],
                'monitoring-live': ['Monitoring Live', 'Pantau absensi karyawan secara real-time'],
                'laporan-absensi': ['Laporan Absensi', 'Laporan lengkap absensi karyawan'],
                'laporan-cuti': ['Laporan Cuti & Izin', 'Laporan cuti dan izin karyawan'],
                'lokasi-kantor': ['Lokasi Kantor', 'Kelola lokasi kantor untuk validasi absensi'],
                'jam-kerja': ['Jam Kerja', 'Atur jadwal jam kerja perusahaan'],
                'profile': ['Profile', 'Kelola profil dan pengaturan akun']
            };
            
            if (pageData[pageId]) {
                mainPageTitle.textContent = pageData[pageId][0];
                mainPageSubtitle.textContent = pageData[pageId][1];
            }
            
            // Initialize webcam if needed
            if (pageId === 'absensi-masuk') {
                setTimeout(() => initWebcam('webcam', 'captureBtn', 'capturedPhoto', 'photoPreview'), 100);
            } else if (pageId === 'absensi-pulang') {
                setTimeout(() => initWebcam('webcamPulang', 'captureBtnPulang', 'capturedPhotoPulang', 'photoPreviewPulang'), 100);
            }
        }
    }
    
    // Webcam functionality
    function initWebcam(videoId, captureBtnId, capturedPhotoId, previewId) {
        const video = document.getElementById(videoId);
        const captureBtn = document.getElementById(captureBtnId);
        
        if (!video || !captureBtn) return;
        
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function(stream) {
                    video.srcObject = stream;
                })
                .catch(function(error) {
                    console.error("Error accessing webcam:", error);
                    // Show placeholder
                    const container = video.parentElement;
                    if (container) {
                        container.innerHTML = `
                            <div style="height: 300px; display: flex; align-items: center; justify-content: center; background-color: #1e293b; color: white; border-radius: 12px;">
                                <div style="text-align: center;">
                                    <div style="font-size: 48px;">📷</div>
                                    <div style="margin-top: 16px; font-weight: 500;">Webcam tidak tersedia</div>
                                    <div style="margin-top: 8px; font-size: 14px;">Izinkan akses webcam untuk absensi</div>
                                </div>
                            </div>
                        `;
                    }
                });
        }
        
        captureBtn.onclick = function() {
            const capturedPhoto = document.getElementById(capturedPhotoId);
            const photoPreview = document.getElementById(previewId);
            
            if (!video || !capturedPhoto || !photoPreview) return;
            
            // Create canvas to capture photo
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Convert to data URL
            const dataURL = canvas.toDataURL('image/png');
            capturedPhoto.src = dataURL;
            
            // Show preview
            photoPreview.style.display = 'block';
            
            // Stop webcam stream
            const stream = video.srcObject;
            if (stream) {
                const tracks = stream.getTracks();
                tracks.forEach(track => track.stop());
            }
        };
    }
    
    // Konfirmasi sebelum submit (form izin/cuti tetap POST native ke server)
    const formIzin = document.getElementById('formIzin');
    if (formIzin) {
        formIzin.addEventListener('submit', async function(e) {
            e.preventDefault();
            const confirmed = typeof window.showFormalConfirm === 'function' ?
                await window.showFormalConfirm('Apakah Anda yakin ingin mengajukan izin ini?', 'Konfirmasi Pengajuan Izin', 'Ya, Ajukan', 'Batal') :
                confirm('Apakah Anda yakin ingin mengajukan izin ini?');
            if (confirmed) {
                this.submit();
            }
        });
    }

    const formCuti = document.getElementById('formCuti');
    if (formCuti) {
        formCuti.addEventListener('submit', async function(e) {
            e.preventDefault();
            const confirmed = typeof window.showFormalConfirm === 'function' ?
                await window.showFormalConfirm('Apakah Anda yakin ingin mengajukan cuti ini?', 'Konfirmasi Pengajuan Cuti', 'Ya, Ajukan', 'Batal') :
                confirm('Apakah Anda yakin ingin mengajukan cuti ini?');
            if (confirmed) {
                this.submit();
            }
        });
    }

    // Handle resize
    function handleResize() {
        closeMobileSidebar();
        if (window.innerWidth <= 768) {
            if (mobileMenuBtn && mobileMenuBtn !== sidebarToggle) mobileMenuBtn.style.display = 'flex';
            if (sidebar) sidebar.classList.remove('collapsed');
        } else {
            if (mobileMenuBtn && mobileMenuBtn !== sidebarToggle) mobileMenuBtn.style.display = 'none';
        }
    }
    
    // Initial check
    handleResize();
    
    // Resize listener
    window.addEventListener('resize', handleResize);
    
    // Initialize GPS data
    setTimeout(() => {
        if (typeof simulateGPSData === 'function') {
            simulateGPSData();
        }
    }, 1000);
});

// GLOBAL FUNCTIONS

function submitAttendance(type) {
    if (type === 'masuk') {
        if (typeof window.showFormalAlert === 'function') {
            window.showFormalAlert('Absensi masuk berhasil! Waktu: ' + new Date().toLocaleTimeString('id-ID'), 'success', 'Absensi Masuk Berhasil');
        } else {
            alert('Absensi masuk berhasil! Waktu: ' + new Date().toLocaleTimeString());
        }
    } else if (type === 'pulang') {
        if (typeof window.showFormalAlert === 'function') {
            window.showFormalAlert('Absensi pulang berhasil! Waktu: ' + new Date().toLocaleTimeString('id-ID'), 'success', 'Absensi Pulang Berhasil');
        } else {
            alert('Absensi pulang berhasil! Waktu: ' + new Date().toLocaleTimeString());
        }
    }
    
    // Simulate redirect to dashboard
    const dashboardBtn = document.querySelector('[data-page="dashboard-home"]');
    if (dashboardBtn) dashboardBtn.click();
}

function retakePhoto() {
    const preview = document.getElementById('photoPreview');
    if (preview) preview.style.display = 'none';
}

function retakePhotoPulang() {
    const preview = document.getElementById('photoPreviewPulang');
    if (preview) preview.style.display = 'none';
}

function showPhoto(name) {
    if (typeof window.showFormalAlert === 'function') {
        window.showFormalAlert(`Menampilkan foto absensi ${name}`, 'info', 'Foto Absensi');
    } else {
        alert(`Menampilkan foto absensi ${name}`);
    }
}

// Simulate GPS data
function simulateGPSData() {
    const locations = [
        { address: "Kantor Pusat, Jl. Sudirman No. 123, Jakarta", coords: "-6.2088, 106.8456", distance: "0.2 km" },
        { address: "Kantor Cabang, Jl. Thamrin No. 45, Jakarta", coords: "-6.1865, 106.8232", distance: "1.5 km" },
        { address: "Kantor Cabang 2, Jl. Gatot Subroto No. 67, Jakarta", coords: "-6.2212, 106.8193", distance: "2.1 km" }
    ];
    
    const randomLocation = locations[Math.floor(Math.random() * locations.length)];
    
    // Update location info
    document.querySelectorAll('#locationAddress, #locationAddressPulang').forEach(el => {
        if (el) el.textContent = randomLocation.address;
    });
    
    document.querySelectorAll('#locationCoords, #locationCoordsPulang').forEach(el => {
        if (el) el.textContent = randomLocation.coords;
    });
    
    document.querySelectorAll('#locationDistance, #locationDistancePulang').forEach(el => {
        if (el) el.textContent = randomLocation.distance;
    });
}

// Update GPS status periodically
setInterval(() => {
    const gpsElements = document.querySelectorAll('.gps-status');
    gpsElements.forEach(el => {
        if (el) {
            el.className = 'gps-status gps-active';
            el.innerHTML = `
                <div>✅</div>
                <div>
                    <div style="font-weight: 500;">GPS Aktif</div>
                    <div style="font-size: 12px;">Lokasi terdeteksi</div>
                </div>
            `;
        }
    });
}, 30000);

// Global Password Toggle Helper (Hide/Unhide)
window.togglePasswordVisibility = function(inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        if (icon) {
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    } else {
        input.type = 'password';
        if (icon) {
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
};
