// js/script.js - FIXED VERSION
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard loaded!');
    
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
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
    
    // Toggle sidebar - DESKTOP (toggle .collapsed)
    sidebarToggle.addEventListener('click', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }
    });
    
    // Toggle sidebar - MOBILE (toggle .mobile-open)
    function toggleMobileSidebar() {
        sidebar.classList.toggle('mobile-open');
        overlay.style.display = sidebar.classList.contains('mobile-open') ? 'block' : 'none';
        
        // Change icon
        mobileMenuBtn.innerHTML = sidebar.classList.contains('mobile-open') ? '✕' : '☰';
    }
    
    mobileMenuBtn.addEventListener('click', toggleMobileSidebar);
    
    // Close sidebar when overlay clicked
    overlay.addEventListener('click', function() {
        sidebar.classList.remove('mobile-open');
        overlay.style.display = 'none';
        mobileMenuBtn.innerHTML = '☰';
    });
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        if (window.innerWidth <= 768 && 
            !sidebar.contains(event.target) && 
            !mobileMenuBtn.contains(event.target)) {
            sidebar.classList.remove('mobile-open');
            overlay.style.display = 'none';
            mobileMenuBtn.innerHTML = '☰';
        }
    });
    
    // Handle menu item clicks
    function handleMenuItemClick(e) {
        e.preventDefault();
        
        const pageId = this.getAttribute('data-page');
        
        // Logout
        if (pageId === 'logout') {
            if (confirm('Apakah Anda yakin ingin logout?')) {
                alert('Logout berhasil!');
                // window.location.href = '/login';
            }
            return;
        }
        
        // Update active menu
        menuItems.forEach(menuItem => {
            menuItem.classList.remove('active');
        });
        this.classList.add('active');
        
        // Close sidebar on mobile after click
        if (window.innerWidth <= 768) {
            sidebar.classList.remove('mobile-open');
            overlay.style.display = 'none';
            mobileMenuBtn.innerHTML = '☰';
        }
        
        // Show page content
        showPageContent(pageId);
    }
    
    // Attach event listeners to menu items
    menuItems.forEach(item => {
        item.addEventListener('click', handleMenuItemClick);
        item.addEventListener('touchstart', function(e) {
            e.preventDefault();
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
                'laporan-keterlambatan': ['Laporan Keterlambatan', 'Laporan keterlambatan karyawan'],
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
    
    // Form submission handlers
    const formIzin = document.getElementById('formIzin');
    if (formIzin) {
        formIzin.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Ajukan izin?')) {
                alert('Izin berhasil diajukan! Menunggu persetujuan atasan.');
                this.reset();
            }
        });
    }
    
    const formCuti = document.getElementById('formCuti');
    if (formCuti) {
        formCuti.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Ajukan cuti?')) {
                alert('Cuti berhasil diajukan! Menunggu persetujuan atasan.');
                this.reset();
            }
        });
    }
    
    // Handle resize
    function handleResize() {
        if (window.innerWidth <= 768) {
            // Mobile - show mobile button, hide overlay
            mobileMenuBtn.style.display = 'block';
            overlay.style.display = 'none';
            sidebar.classList.remove('mobile-open');
            mobileMenuBtn.innerHTML = '☰';
            
            // Ensure sidebar uses mobile positioning
            sidebar.style.left = '-280px';
        } else {
            // Desktop - hide mobile button and overlay
            mobileMenuBtn.style.display = 'none';
            overlay.style.display = 'none';
            sidebar.classList.remove('mobile-open');
            
            // Reset sidebar position
            sidebar.style.left = '0';
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
        alert('Absensi masuk berhasil! Waktu: ' + new Date().toLocaleTimeString());
    } else if (type === 'pulang') {
        alert('Absensi pulang berhasil! Waktu: ' + new Date().toLocaleTimeString());
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
    alert(`Menampilkan foto absensi ${name}`);
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