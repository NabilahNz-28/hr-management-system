@extends('layouts.app')


<style>
    /* Styles khusus untuk absensi pulang */
    .camera-container {
        position: relative;
        width: 100%;
        height: 300px;
        background: #000;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 15px;
    }
    
    #webcamPulang {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scaleX(-1);
    }
    
    .capture-btn {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: white;
        border: 4px solid #ef4444;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .capture-btn:hover {
        transform: translateX(-50%) scale(1.05);
    }
    
    .captured-photo {
        width: 100%;
        max-height: 300px;
        object-fit: contain;
        border-radius: 8px;
        border: 2px solid #10b981;
        background: #000;
    }
    
    .map-container {
        height: 300px;
        width: 100%;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        margin-bottom: 15px;
    }
    
    .gps-status {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border-radius: 8px;
        margin: 15px 0;
        border: 1px solid #e5e7eb;
    }
    
    .location-info {
        padding: 15px;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        margin-top: 15px;
    }
    
    .btn {
        padding: 10px 20px;
        border-radius: 6px;
        border: none;
        font-weight: 500;
        cursor: pointer;
        margin-right: 10px;
        transition: all 0.2s;
    }
    
    .btn-success {
        background: #10b981;
        color: white;
    }
    
    .btn-success:hover {
        background: #059669;
    }
    
    .btn-danger {
        background: #ef4444;
        color: white;
    }
    
    .btn-danger:hover {
        background: #dc2626;
    }
    
    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .alert-box {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .alert-info {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
    }
    
    .alert-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
    }
    
    .alert-warning {
        background: #fef3c7;
        border: 1px solid #fde68a;
    }
    
    .content-title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #1f2937;
    }
    
    .content-description {
        color: #6b7280;
        margin-bottom: 20px;
    }
    
    .attendance-info {
        background: #f8fafc;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        margin-bottom: 20px;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-top: 10px;
    }
    
    .info-item {
        font-size: 14px;
    }
    
    .info-label {
        color: #6b7280;
        font-weight: 500;
    }
    
    .info-value {
        color: #1f2937;
        font-weight: 600;
    }
    
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        display: none;
    }
    
    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #3b82f6;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 15px;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="page-content" id="absensi-pulang">
        <div class="content-title">Absensi Pulang</div>
        <p class="content-description">Lakukan absensi pulang dengan foto wajah dan verifikasi lokasi</p>
        
        <!-- Attendance Info Card -->
        <div class="attendance-info">
            <div style="font-weight: 500; font-size: 16px; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                <span style="color: #10b981;">✓</span> Status Absensi Hari Ini
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Absensi Masuk:</div>
                    <div class="info-value" id="displayWaktuMasuk">Belum ada data</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status:</div>
                    <div class="info-value" style="color: #10b981;" id="displayStatusMasuk">-</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Lama Bekerja:</div>
                    <div class="info-value" id="displayLamaKerja">-</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Waktu Sekarang:</div>
                    <div class="info-value" id="displayWaktuSekarang">-</div>
                </div>
            </div>
        </div>
        
        <!-- Alert Box -->
        <div class="alert-box alert-success">
            <div style="font-size: 24px;">✅</div>
            <div>
                <div style="font-weight: 500;">Mode Uji Coba Aktif</div>
                <div style="font-size: 13px;">Anda dapat melakukan absensi pulang dari lokasi mana pun.</div>
                <div style="font-size: 13px; margin-top: 4px; color: #6b7280;">
                    Waktu kerja normal: 08:00 - 17:00 | Absen pulang mulai 16:30
                </div>
            </div>
        </div>
        
        <!-- Grid Layout -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 20px;">
            <!-- Kolom Kiri: Kamera -->
            <div>
                <div class="content-title" style="font-size: 16px;">Foto Wajah</div>
                <div class="camera-container">
                    <div id="webcamContainerPulang">
                        <video id="webcamPulang" autoplay playsinline></video>
                        <div id="cameraStatusPulang" style="text-align: center; margin-top: 10px; font-size: 12px; color: #6b7280;">
                            Menunggu kamera...
                        </div>
                    </div>
                    <button class="capture-btn" id="captureBtnPulang" onclick="ambilFoto('pulang')">
                        <div style="width: 24px; height: 24px; border-radius: 50%; background-color: #ef4444;"></div>
                    </button>
                </div>
                
                <!-- Photo Preview -->
                <div id="photoPreviewPulang" style="display: none; margin-top: 16px;">
                    <div class="content-title" style="font-size: 16px;">Foto yang diambil:</div>
                    <img id="capturedPhotoPulang" class="captured-photo" alt="Captured Photo">
                    
                    <!-- Timer Display -->
                    <div id="timerDisplayPulang" style="margin-top: 10px; padding: 8px; background: #f3f4f6; border-radius: 4px; text-align: center; font-weight: bold; color: #3b82f6;">
                        Timer: 0 detik
                    </div>
                    
                    <!-- Action Buttons -->
                    <div style="margin-top: 12px;">
                        <button class="btn btn-success" id="submitBtnPulang" onclick="submitAbsensi('pulang')" disabled>
                            Submit Absensi Pulang
                        </button>
                        <button class="btn btn-danger" onclick="retakePhoto('pulang')">
                            Ambil Ulang
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Kolom Kanan: Peta & Lokasi -->
            <div>
                <div class="content-title" style="font-size: 16px;">Lokasi GPS</div>
                <div class="map-container">
                    <div id="mapPulang" style="height: 100%; width: 100%;"></div>
                </div>
                
                <!-- GPS Status -->
                <div class="gps-status" id="gpsStatusPulang">
                    <div>⏳</div>
                    <div>
                        <div style="font-weight: 500;">Memuat GPS...</div>
                        <div style="font-size: 12px;">Harap tunggu</div>
                    </div>
                </div>
                
                <!-- Location Info -->
                <div class="location-info">
                    <div style="font-weight: 500; margin-bottom: 8px;">Detail Lokasi:</div>
                    <div style="font-size: 14px;">
                        <div>📍 <span id="locationAddressPulang">Mendeteksi alamat...</span></div>
                        <div style="margin-top: 8px;">📍 Koordinat: <span id="locationCoordsPulang">-</span></div>
                        <div style="margin-top: 8px;">📍 Status: <span id="locationDistancePulang" style="color: #10b981;">Mode Uji Coba</span></div>
                    </div>
                    <button onclick="refreshGPS('pulang')" style="margin-top: 15px; padding: 8px 16px; background: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; width: 100%; transition: background 0.2s;">
                        🔄 Refresh Lokasi GPS
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlayPulang">
    <div class="loading-spinner"></div>
    <div id="loadingTextPulang" style="font-weight: 500; color: #1f2937;">Menyimpan absensi...</div>
</div>
@endsection

@section('scripts')
<script>
// ===================== KONFIGURASI =====================
const TIMEOUT = 30; // detik
const KANTOR_LAT = -6.058908;
const KANTOR_LNG = 106.653040;

// ===================== VARIABEL =====================
let kameraPulang = null;
let lokasiPulang = null;
let petaPulang = null;
let timerPulang = null;
let hitungDetikPulang = 0;
let fotoDiambilPulang = false;
let markerPulang = null;

// Data absensi masuk hari ini (dari localStorage/session)
let absensiMasukHariIni = null;

// ===================== INISIALISASI =====================
document.addEventListener('DOMContentLoaded', function() {
    console.log('Sistem Absensi Pulang dimulai...');
    
    // Load data absensi masuk
    loadAbsensiMasukData();
    
    // Update info absensi masuk setiap detik
    setInterval(updateAttendanceInfo, 1000);
    
    // Setup kamera
    startKamera('pulang');
    
    // Setup peta
    initPetaPulang();
    
    // Auto refresh GPS setiap 30 detik
    setInterval(() => {
        updateGPS('pulang');
    }, 30000);
});

// ===================== LOAD DATA ABSENSI MASUK =====================
function loadAbsensiMasukData() {
    try {
        // Coba ambil dari sessionStorage (dari halaman masuk)
        const waktuMasuk = sessionStorage.getItem('waktu_masuk');
        if (waktuMasuk) {
            document.getElementById('displayWaktuMasuk').textContent = waktuMasuk;
            document.getElementById('displayStatusMasuk').textContent = 'Sudah Absen';
        }
        
        // Coba ambil dari localStorage
        const absensiData = JSON.parse(localStorage.getItem('absensi_data') || '[]');
        
        // Cari absensi masuk hari ini
        const today = new Date().toDateString();
        absensiMasukHariIni = absensiData.find(item => {
            const itemDate = new Date(item.created_at).toDateString();
            return item.type === 'masuk' && itemDate === today;
        });
        
        if (absensiMasukHariIni) {
            const waktu = new Date(absensiMasukHariIni.created_at).toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
            
            document.getElementById('displayWaktuMasuk').textContent = waktu;
            document.getElementById('displayStatusMasuk').textContent = 'Sudah Absen';
            
            // Update sessionStorage
            sessionStorage.setItem('waktu_masuk', waktu);
        }
        
    } catch (error) {
        console.error('Error loading attendance data:', error);
    }
}

// ===================== UPDATE INFO ABSENSI =====================
function updateAttendanceInfo() {
    const now = new Date();
    
    // Update waktu sekarang
    document.getElementById('displayWaktuSekarang').textContent = 
        now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    
    // Hitung lama kerja jika sudah absen masuk
    if (absensiMasukHariIni) {
        const masukTime = new Date(absensiMasukHariIni.created_at);
        const diffMs = now - masukTime;
        const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
        const diffMinutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
        
        document.getElementById('displayLamaKerja').textContent = 
            `${diffHours} jam ${diffMinutes} menit`;
            
        // Cek apakah sudah bisa absen pulang (setelah 16:30)
        const currentHour = now.getHours();
        const currentMinute = now.getMinutes();
        const bisaAbsenPulang = currentHour >= 16 && currentMinute >= 30;
        
        if (bisaAbsenPulang) {
            document.getElementById('displayStatusMasuk').innerHTML = 
                '<span style="color: #10b981;">✓ Bisa absen pulang</span>';
        } else {
            document.getElementById('displayStatusMasuk').innerHTML = 
                '<span style="color: #f59e0b;">⚠ Tunggu sampai 16:30</span>';
        }
    }
}

// ===================== KAMERA =====================
async function startKamera(tipe) {
    const video = document.getElementById('webcamPulang');
    const status = document.getElementById('cameraStatusPulang');
    
    if (!video) return;
    
    try {
        if (status) status.textContent = 'Mengakses kamera...';
        
        // Stop kamera sebelumnya jika ada
        if (kameraPulang) {
            kameraPulang.getTracks().forEach(track => track.stop());
        }
        
        // Request permission untuk kamera
        const stream = await navigator.mediaDevices.getUserMedia({ 
            video: { 
                facingMode: 'user',
                width: { ideal: 640 },
                height: { ideal: 480 }
            },
            audio: false
        });
        
        video.srcObject = stream;
        kameraPulang = stream;
        
        if (status) {
            status.textContent = '✅ Kamera siap';
            status.style.color = '#10b981';
        }
        
    } catch (error) {
        console.error('Gagal mengakses kamera:', error);
        if (status) {
            status.textContent = '❌ Gagal mengakses kamera';
            status.style.color = '#ef4444';
        }
        
        if (error.name === 'NotAllowedError') {
            alert('Izin kamera ditolak. Silakan berikan izin kamera di pengaturan browser Anda.');
        } else if (error.name === 'NotFoundError') {
            alert('Kamera tidak ditemukan. Pastikan perangkat memiliki kamera.');
        } else {
            alert('Gagal mengakses kamera. Pastikan izin diberikan dan kamera berfungsi.');
        }
    }
}

// ===================== PETA =====================
function initPetaPulang() {
    // Cek apakah Leaflet sudah dimuat
    if (typeof L === 'undefined') {
        console.log('Leaflet belum dimuat, menunggu...');
        setTimeout(initPetaPulang, 500);
        return;
    }
    
    try {
        // Inisialisasi peta
        petaPulang = L.map('mapPulang').setView([KANTOR_LAT, KANTOR_LNG], 13);
        
        // Tambah tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(petaPulang);
        
        // Tambah marker kantor
        L.marker([KANTOR_LAT, KANTOR_LNG])
            .addTo(petaPulang)
            .bindPopup('<b>📍 Lokasi Kantor</b><br>Kawasan Multi Guna Estate')
            .openPopup();
        
        // Tambah circle radius
        L.circle([KANTOR_LAT, KANTOR_LNG], {
            color: '#3b82f6',
            fillColor: '#3b82f6',
            fillOpacity: 0.1,
            radius: 100
        }).addTo(petaPulang);
        
        console.log('Peta pulang berhasil diinisialisasi');
        
        // Ambil lokasi GPS
        setTimeout(() => updateGPS('pulang'), 1000);
        
    } catch (error) {
        console.error('Error inisialisasi peta pulang:', error);
        document.getElementById('mapPulang').innerHTML = `
            <div style="text-align: center; padding: 50px 20px; color: #6b7280;">
                <div style="font-size: 48px;">❌</div>
                <div style="font-weight: 500; margin-top: 12px;">Gagal memuat peta</div>
                <button onclick="initPetaPulang()" style="margin-top: 15px; padding: 8px 16px; background: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    🔄 Coba Lagi
                </button>
            </div>
        `;
    }
}

// ===================== GPS & LOKASI =====================
function updateGPS(tipe) {
    if (!navigator.geolocation) {
        updateGPSStatus(tipe, false, 'Browser tidak mendukung GPS');
        updateLokasiFallback(tipe);
        return;
    }
    
    updateGPSStatus(tipe, false, 'Mendeteksi lokasi...');
    
    navigator.geolocation.getCurrentPosition(
        (pos) => successGPS(pos, tipe),
        (err) => errorGPS(err, tipe),
        { 
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
}

function refreshGPS(tipe) {
    const status = document.getElementById('gpsStatusPulang');
    
    if (status) {
        status.innerHTML = `
            <div>🔄</div>
            <div>
                <div style="font-weight: 500;">Memuat ulang GPS...</div>
                <div style="font-size: 12px;">Harap tunggu</div>
            </div>
        `;
        status.style.background = '#fef3c7';
    }
    
    updateGPS(tipe);
}

function successGPS(pos, tipe) {
    const lat = pos.coords.latitude;
    const lng = pos.coords.longitude;
    const accuracy = pos.coords.accuracy;
    
    lokasiPulang = { lat, lng, accuracy };
    updatePetaPulang(lat, lng);
    updateInfoLokasi(tipe, lat, lng);
    updateGPSStatus(tipe, true, `Akurasi: ±${Math.round(accuracy)}m`);
    cekValidasiSubmit(tipe);
}

function errorGPS(err, tipe) {
    console.error('GPS Error:', err.code, err.message);
    
    let errorMessage = 'Gagal mendapatkan lokasi';
    switch(err.code) {
        case err.PERMISSION_DENIED:
            errorMessage = 'Izin lokasi ditolak';
            break;
        case err.POSITION_UNAVAILABLE:
            errorMessage = 'Informasi lokasi tidak tersedia';
            break;
        case err.TIMEOUT:
            errorMessage = 'Timeout mendapatkan lokasi';
            break;
    }
    
    updateGPSStatus(tipe, false, errorMessage);
    updateLokasiFallback(tipe);
}

function updateLokasiFallback(tipe) {
    const fallbackLat = -6.2088 + (Math.random() * 0.1 - 0.05);
    const fallbackLng = 106.8456 + (Math.random() * 0.1 - 0.05);
    
    lokasiPulang = { lat: fallbackLat, lng: fallbackLng, accuracy: 1000 };
    updatePetaPulang(fallbackLat, fallbackLng);
    updateInfoLokasi(tipe, fallbackLat, fallbackLng);
    cekValidasiSubmit(tipe);
}

function updatePetaPulang(lat, lng) {
    if (!petaPulang) return;
    
    try {
        petaPulang.setView([lat, lng], 15);
        
        if (markerPulang) {
            petaPulang.removeLayer(markerPulang);
        }
        
        markerPulang = L.marker([lat, lng], {
            title: 'Lokasi Anda',
            icon: L.icon({
                iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34]
            })
        }).addTo(petaPulang);
        
        markerPulang.bindPopup(`
            <b>📍 Lokasi Anda</b><br>
            Lat: ${lat.toFixed(6)}<br>
            Lng: ${lng.toFixed(6)}<br>
            <small>${new Date().toLocaleTimeString('id-ID')}</small>
        `);
        
        markerPulang.openPopup();
        
    } catch (error) {
        console.error('Error update peta pulang:', error);
    }
}

function updateInfoLokasi(tipe, lat, lng) {
    document.getElementById('locationCoordsPulang').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    document.getElementById('locationDistancePulang').innerHTML = `<span style="color:#10b981;">✅ Mode Uji Coba - Bisa absen di mana saja</span>`;
    
    getAddressFromCoordinates(lat, lng);
}

function getAddressFromCoordinates(lat, lng) {
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
        .then(response => response.json())
        .then(data => {
            if (data.display_name) {
                const addressParts = [];
                if (data.address.road) addressParts.push(data.address.road);
                if (data.address.suburb) addressParts.push(data.address.suburb);
                if (data.address.city_district) addressParts.push(data.address.city_district);
                if (data.address.city) addressParts.push(data.address.city);
                if (data.address.state) addressParts.push(data.address.state);
                
                const formattedAddress = addressParts.join(', ') || data.display_name;
                document.getElementById('locationAddressPulang').textContent = formattedAddress;
            }
        })
        .catch(error => {
            console.error('Error reverse geocoding:', error);
            document.getElementById('locationAddressPulang').textContent = `Koordinat: ${lat.toFixed(4)}°, ${lng.toFixed(4)}°`;
        });
}

function updateGPSStatus(tipe, sukses, pesan) {
    const status = document.getElementById('gpsStatusPulang');
    
    if (!status) return;
    
    if (sukses) {
        status.innerHTML = `
            <div>✅</div>
            <div>
                <div style="font-weight: 500;">GPS Aktif</div>
                <div style="font-size: 12px;">${pesan}</div>
            </div>
        `;
        status.style.background = '#f0fdf4';
        status.style.borderColor = '#bbf7d0';
    } else {
        status.innerHTML = `
            <div>⚠️</div>
            <div>
                <div style="font-weight: 500;">Mode Simulasi</div>
                <div style="font-size: 12px;">${pesan}</div>
            </div>
        `;
        status.style.background = '#fef3c7';
        status.style.borderColor = '#fde68a';
    }
}

// ===================== AMBIL FOTO =====================
function ambilFoto(tipe) {
    const video = document.getElementById('webcamPulang');
    const status = document.getElementById('cameraStatusPulang');
    
    if (!video || !video.srcObject) {
        alert('Kamera belum siap! Silakan refresh halaman jika kamera tidak muncul.');
        return;
    }
    
    // Cek apakah sudah absen masuk hari ini
    if (!absensiMasukHariIni) {
        const konfirm = confirm(
            'Anda belum melakukan absensi masuk hari ini.\n' +
            'Apakah Anda ingin tetap melanjutkan absensi pulang?'
        );
        
        if (!konfirm) return;
    }
    
    // Buat canvas
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');
    
    // Mirror effect
    ctx.translate(canvas.width, 0);
    ctx.scale(-1, 1);
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    // Deteksi wajah
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const wajahTerdeteksi = cekWajah(imageData);
    
    if (!wajahTerdeteksi) {
        alert('Wajah tidak terdeteksi! Pastikan wajah terlihat jelas di kamera dengan pencahayaan yang baik.');
        return;
    }
    
    fotoDiambilPulang = true;
    
    // Watermark
    ctx.setTransform(1, 0, 0, 1, 0, 0);
    ctx.fillStyle = 'rgba(0, 0, 0, 0.7)';
    ctx.fillRect(0, canvas.height - 40, canvas.width, 40);
    ctx.fillStyle = 'white';
    ctx.font = '14px Arial, sans-serif';
    
    const now = new Date();
    const waktu = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    const tanggal = now.toLocaleDateString('id-ID', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
    
    ctx.fillText(`Absensi Pulang - ${tanggal} ${waktu}`, 10, canvas.height - 20);
    
    // Tampilkan preview
    document.getElementById('photoPreviewPulang').style.display = 'block';
    document.getElementById('capturedPhotoPulang').src = canvas.toDataURL('image/jpeg', 0.9);
    
    // Update status kamera
    if (status) {
        status.textContent = '✅ Foto berhasil diambil';
        status.style.color = '#10b981';
    }
    
    // Mulai timer
    mulaiTimer(tipe);
    
    // Cek validasi
    cekValidasiSubmit(tipe);
}

function cekWajah(imageData) {
    const data = imageData.data;
    let kontras = 0;
    let sampleCount = 0;
    
    for (let i = 0; i < data.length; i += 400) {
        const r = data[i];
        const g = data[i + 1];
        const b = data[i + 2];
        
        if (Math.abs(r - g) > 30 || Math.abs(r - b) > 30 || Math.abs(g - b) > 30) {
            kontras++;
        }
        sampleCount++;
    }
    
    return (kontras / sampleCount) > 0.1;
}

// ===================== TIMER =====================
function mulaiTimer(tipe) {
    if (timerPulang) {
        clearInterval(timerPulang);
    }
    
    hitungDetikPulang = 0;
    
    const timerElement = document.getElementById('timerDisplayPulang');
    
    timerPulang = setInterval(() => {
        hitungDetikPulang++;
        timerElement.textContent = `Timer: ${hitungDetikPulang} detik`;
        
        if (hitungDetikPulang >= TIMEOUT) {
            clearInterval(timerPulang);
            alert('⏰ Waktu habis! Foto akan kadaluarsa dalam 30 detik. Silakan ambil foto ulang.');
            retakePhoto(tipe);
        }
    }, 1000);
}

// ===================== VALIDASI & SUBMIT =====================
function cekValidasiSubmit(tipe) {
    const submitBtn = document.getElementById('submitBtnPulang');
    
    if (!submitBtn) return;
    
    if (fotoDiambilPulang) {
        submitBtn.disabled = false;
        submitBtn.title = 'Klik untuk submit absensi pulang';
    } else {
        submitBtn.disabled = true;
        submitBtn.title = 'Belum mengambil foto';
    }
}

async function submitAbsensi(tipe) {
    if (!fotoDiambilPulang) {
        alert('Silakan ambil foto terlebih dahulu!');
        return;
    }
    
    // Tampilkan loading
    showLoading('Menyimpan absensi...');
    
    try {
        // Ambil data
        const photoBase64 = document.getElementById('capturedPhotoPulang').src;
        const coordinates = lokasiPulang ? 
            `${lokasiPulang.lat.toFixed(6)}, ${lokasiPulang.lng.toFixed(6)}` : 
            'Tidak terdeteksi';
        
        const address = document.getElementById('locationAddressPulang').textContent;
        const waktu = new Date().toLocaleTimeString('id-ID');
        const tanggal = new Date().toLocaleDateString('id-ID');
        
        // Info tambahan
        const waktuMasuk = absensiMasukHariIni ? 
            new Date(absensiMasukHariIni.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) :
            'Tidak tercatat';
        
        // Hitung lama kerja
        let lamaKerja = '-';
        if (absensiMasukHariIni) {
            const masukTime = new Date(absensiMasukHariIni.created_at);
            const sekarang = new Date();
            const diffMs = sekarang - masukTime;
            const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
            const diffMinutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
            lamaKerja = `${diffHours} jam ${diffMinutes} menit`;
        }
        
        // Konfirmasi
        const konfirmasi = confirm(
            `Konfirmasi Absensi PULANG?\n\n` +
            `📅 Tanggal: ${tanggal}\n` +
            `⏰ Waktu: ${waktu}\n` +
            `📍 Lokasi: ${coordinates}\n` +
            `🏠 Alamat: ${address}\n` +
            `⏱️ Waktu Masuk: ${waktuMasuk}\n` +
            `⏳ Lama Bekerja: ${lamaKerja}\n\n` +
            `Mode: Uji Coba`
        );
        
        if (!konfirmasi) {
            hideLoading();
            return;
        }
        
        // Disable submit button
        const submitBtn = document.getElementById('submitBtnPulang');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '⏳ Menyimpan...';
        submitBtn.disabled = true;
        
        // Simpan ke database
        await simpanKeDatabase(tipe, {
            photo: photoBase64,
            latitude: lokasiPulang?.lat,
            longitude: lokasiPulang?.lng,
            address: address,
            timestamp: new Date().toISOString(),
            waktu_masuk: waktuMasuk,
            lama_kerja: lamaKerja
        });
        
        // Hentikan timer
        if (timerPulang) {
            clearInterval(timerPulang);
        }
        
        // Tampilkan sukses
        hideLoading();
        alert('✅ Absensi pulang berhasil disimpan!');
        
        // Reset form
        retakePhoto(tipe);
        
        // Update UI
        updateUIAfterSubmit();
        
    } catch (error) {
        console.error('Error submit absensi pulang:', error);
        hideLoading();
        alert('❌ Gagal menyimpan absensi. Silakan coba lagi.');
        
        // Reset button
        const submitBtn = document.getElementById('submitBtnPulang');
        submitBtn.innerHTML = 'Submit Absensi Pulang';
        submitBtn.disabled = false;
    }
}

async function simpanKeDatabase(tipe, data) {
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            try {
                const absensiData = {
                    id: Date.now(),
                    type: tipe,
                    ...data,
                    status: 'success',
                    created_at: new Date().toISOString()
                };
                
                // Ambil data absensi yang sudah ada
                const existingData = JSON.parse(localStorage.getItem('absensi_data') || '[]');
                existingData.push(absensiData);
                
                // Simpan ke localStorage
                localStorage.setItem('absensi_data', JSON.stringify(existingData));
                
                console.log('Data absensi pulang disimpan:', absensiData);
                
                // Simpan ke session
                sessionStorage.setItem('last_attendance_pulang', JSON.stringify(absensiData));
                
                resolve(absensiData);
                
            } catch (error) {
                reject(error);
            }
        }, 1500);
    });
}

function updateUIAfterSubmit() {
    // Update session
    sessionStorage.setItem('absensi_pulang_done', 'true');
    
    // Tampilkan notifikasi sukses
    const successDiv = document.createElement('div');
    successDiv.innerHTML = `
        <div style="position: fixed; top: 20px; right: 20px; background: #10b981; color: white; padding: 12px 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 1000; animation: slideIn 0.3s ease-out;">
            ✅ Absensi pulang berhasil! Selamat beristirahat.
        </div>
    `;
    document.body.appendChild(successDiv);
    
    // Update info card
    document.getElementById('displayStatusMasuk').innerHTML = 
        '<span style="color: #10b981;">✓ Absensi lengkap hari ini</span>';
    
    // Hapus notifikasi setelah 3 detik
    setTimeout(() => {
        if (successDiv.parentNode) {
            successDiv.parentNode.removeChild(successDiv);
        }
    }, 3000);
}

// ===================== FUNGSI BANTU =====================
function retakePhoto(tipe) {
    fotoDiambilPulang = false;
    
    if (timerPulang) {
        clearInterval(timerPulang);
        timerPulang = null;
    }
    
    document.getElementById('photoPreviewPulang').style.display = 'none';
    document.getElementById('timerDisplayPulang').textContent = 'Timer: 0 detik';
    
    const status = document.getElementById('cameraStatusPulang');
    if (status) {
        status.textContent = '✅ Kamera siap';
        status.style.color = '#10b981';
    }
    
    const submitBtn = document.getElementById('submitBtnPulang');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.title = 'Belum mengambil foto';
    }
}

function showLoading(text) {
    const overlay = document.getElementById('loadingOverlayPulang');
    const textEl = document.getElementById('loadingTextPulang');
    
    if (textEl) textEl.textContent = text;
    if (overlay) overlay.style.display = 'flex';
}

function hideLoading() {
    const overlay = document.getElementById('loadingOverlayPulang');
    if (overlay) overlay.style.display = 'none';
}

// ===================== EXPORT FUNGSI =====================
window.startKamera = startKamera;
window.updateGPS = updateGPS;
window.refreshGPS = refreshGPS;
</script>
