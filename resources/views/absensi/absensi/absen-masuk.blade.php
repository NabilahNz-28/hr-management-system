<!-- resources/views/absensi/absen-masuk.blade.php -->
@extends('layouts.app')

@section('title', 'Absensi Masuk')

@section('styles')
<style>
    /* Styles khusus untuk absensi masuk */
    .camera-container {
        position: relative;
        width: 100%;
        height: 300px;
        background: #000;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 15px;
    }
    
    #webcam {
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
    <div class="page-content" id="absensi-masuk">
        <div class="content-title">Absensi Masuk</div>
        <p class="content-description">Lakukan absensi masuk dengan foto wajah dan verifikasi lokasi</p>
        
        <!-- Alert Info -->
        <div class="alert-box alert-info">
            <div style="font-size: 24px;">📍</div>
            <div>
                <div style="font-weight: 500;">Mode Uji Coba Aktif</div>
                <div style="font-size: 13px;">Untuk tahap pengembangan, absensi dapat dilakukan dari lokasi mana pun.</div>
                <div style="font-size: 13px; margin-top: 4px; color: #6b7280;">
                    Catatan: Dalam mode produksi, radius maksimal 100m dari lokasi kantor.
                </div>
            </div>
        </div>
        
        <!-- Grid Layout -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 20px;">
            <!-- Kolom Kiri: Kamera -->
            <div>
                <div class="content-title" style="font-size: 16px;">Foto Wajah</div>
                <div class="camera-container">
                    <div id="webcamContainer">
                        <video id="webcam" autoplay playsinline></video>
                        <div id="cameraStatus" style="text-align: center; margin-top: 10px; font-size: 12px; color: #6b7280;">
                            Menunggu kamera...
                        </div>
                    </div>
                    <button class="capture-btn" id="captureBtn" onclick="ambilFoto('masuk')">
                        <div style="width: 24px; height: 24px; border-radius: 50%; background-color: #ef4444;"></div>
                    </button>
                </div>
                
                <!-- Photo Preview -->
                <div id="photoPreview" style="display: none; margin-top: 16px;">
                    <div class="content-title" style="font-size: 16px;">Foto yang diambil:</div>
                    <img id="capturedPhoto" class="captured-photo" alt="Captured Photo">
                    
                    <!-- Timer Display -->
                    <div id="timerDisplay" style="margin-top: 10px; padding: 8px; background: #f3f4f6; border-radius: 4px; text-align: center; font-weight: bold; color: #3b82f6;">
                        Timer: 0 detik
                    </div>
                    
                    <!-- Action Buttons -->
                    <div style="margin-top: 12px;">
                        <button class="btn btn-success" id="submitBtnMasuk" onclick="submitAbsensi('masuk')" disabled>
                            Submit Absensi Masuk
                        </button>
                        <button class="btn btn-danger" onclick="retakePhoto('masuk')">
                            Ambil Ulang
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Kolom Kanan: Peta & Lokasi -->
            <div>
                <div class="content-title" style="font-size: 16px;">Lokasi GPS</div>
                <div class="map-container">
                    <div id="mapMasuk" style="height: 100%; width: 100%;"></div>
                </div>
                
                <!-- GPS Status -->
                <div class="gps-status" id="gpsStatusMasuk">
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
                        <div>📍 <span id="locationAddressMasuk">Mendeteksi alamat...</span></div>
                        <div style="margin-top: 8px;">📍 Koordinat: <span id="locationCoordsMasuk">-</span></div>
                        <div style="margin-top: 8px;">📍 Status: <span id="locationDistanceMasuk" style="color: #10b981;">Mode Uji Coba</span></div>
                    </div>
                    <button onclick="refreshGPS('masuk')" style="margin-top: 15px; padding: 8px 16px; background: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; width: 100%; transition: background 0.2s;">
                        🔄 Refresh Lokasi GPS
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
    <div id="loadingText" style="font-weight: 500; color: #1f2937;">Menyimpan absensi...</div>
</div>
@endsection

@section('scripts')
<script>
// ===================== KONFIGURASI =====================
const TIMEOUT = 30; // detik
const KANTOR_LAT = -6.058908; // Untuk referensi saja
const KANTOR_LNG = 106.653040;

// ===================== VARIABEL =====================
let kameraMasuk = null;
let lokasiMasuk = null;
let petaMasuk = null;
let timerMasuk = null;
let hitungDetikMasuk = 0;
let fotoDiambilMasuk = false;
let markerMasuk = null;

// ===================== INISIALISASI =====================
document.addEventListener('DOMContentLoaded', function() {
    console.log('Sistem Absensi Masuk dimulai...');
    
    // Setup kamera
    startKamera('masuk');
    
    // Setup peta
    initPetaMasuk();
    
    // Auto refresh GPS setiap 30 detik
    setInterval(() => {
        updateGPS('masuk');
    }, 30000);
});

// ===================== KAMERA =====================
async function startKamera(tipe) {
    const video = document.getElementById('webcam');
    const status = document.getElementById('cameraStatus');
    
    if (!video) return;
    
    try {
        if (status) status.textContent = 'Mengakses kamera...';
        
        // Stop kamera sebelumnya jika ada
        if (kameraMasuk) {
            kameraMasuk.getTracks().forEach(track => track.stop());
        }
        
        // Request permission untuk kamera
        const stream = await navigator.mediaDevices.getUserMedia({ 
            video: { 
                facingMode: 'user', // Menggunakan kamera depan
                width: { ideal: 640 },
                height: { ideal: 480 }
            },
            audio: false
        });
        
        video.srcObject = stream;
        kameraMasuk = stream;
        
        // Update status kamera
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
        
        // Tampilkan instruksi troubleshooting
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
function initPetaMasuk() {
    // Cek apakah Leaflet sudah dimuat
    if (typeof L === 'undefined') {
        console.log('Leaflet belum dimuat, menunggu...');
        setTimeout(initPetaMasuk, 500);
        return;
    }
    
    try {
        // Inisialisasi peta
        petaMasuk = L.map('mapMasuk').setView([KANTOR_LAT, KANTOR_LNG], 13);
        
        // Tambah tile layer dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(petaMasuk);
        
        // Tambah marker kantor (hanya sebagai referensi)
        L.marker([KANTOR_LAT, KANTOR_LNG])
            .addTo(petaMasuk)
            .bindPopup('<b>📍 Lokasi Kantor</b><br>Kawasan Multi Guna Estate')
            .openPopup();
        
        // Tambah circle radius (hanya visual)
        L.circle([KANTOR_LAT, KANTOR_LNG], {
            color: '#3b82f6',
            fillColor: '#3b82f6',
            fillOpacity: 0.1,
            radius: 100 // 100 meter
        }).addTo(petaMasuk);
        
        console.log('Peta berhasil diinisialisasi');
        
        // Ambil lokasi GPS setelah peta siap
        setTimeout(() => updateGPS('masuk'), 1000);
        
    } catch (error) {
        console.error('Error inisialisasi peta:', error);
        document.getElementById('mapMasuk').innerHTML = `
            <div style="text-align: center; padding: 50px 20px; color: #6b7280;">
                <div style="font-size: 48px;">❌</div>
                <div style="font-weight: 500; margin-top: 12px;">Gagal memuat peta</div>
                <div style="margin-top: 8px; font-size: 14px;">${error.message}</div>
                <button onclick="initPetaMasuk()" style="margin-top: 15px; padding: 8px 16px; background: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer;">
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
    
    // Update status loading
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
    const status = document.getElementById('gpsStatusMasuk');
    
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
    
    console.log('GPS Success:', { lat, lng, accuracy });
    
    // Simpan lokasi
    lokasiMasuk = { lat, lng, accuracy };
    
    // Update peta
    updatePetaMasuk(lat, lng);
    
    // Update info lokasi
    updateInfoLokasi(tipe, lat, lng);
    
    // Update status GPS
    updateGPSStatus(tipe, true, `Akurasi: ±${Math.round(accuracy)}m`);
    
    // Cek validasi untuk submit button
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
    // Fallback: gunakan lokasi browser/IP atau lokasi default
    const fallbackLat = -6.2088 + (Math.random() * 0.1 - 0.05);
    const fallbackLng = 106.8456 + (Math.random() * 0.1 - 0.05);
    
    lokasiMasuk = { lat: fallbackLat, lng: fallbackLng, accuracy: 1000 };
    updatePetaMasuk(fallbackLat, fallbackLng);
    updateInfoLokasi(tipe, fallbackLat, fallbackLng);
    cekValidasiSubmit(tipe);
}

function updatePetaMasuk(lat, lng) {
    if (!petaMasuk) return;
    
    try {
        // Update view peta ke lokasi user
        petaMasuk.setView([lat, lng], 15);
        
        // Hapus marker lama jika ada
        if (markerMasuk) {
            petaMasuk.removeLayer(markerMasuk);
        }
        
        // Buat marker baru untuk lokasi user
        markerMasuk = L.marker([lat, lng], {
            title: 'Lokasi Anda',
            icon: L.icon({
                iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34]
            })
        }).addTo(petaMasuk);
        
        // Bind popup dengan informasi
        markerMasuk.bindPopup(`
            <b>📍 Lokasi Anda</b><br>
            Lat: ${lat.toFixed(6)}<br>
            Lng: ${lng.toFixed(6)}<br>
            <small>${new Date().toLocaleTimeString('id-ID')}</small>
        `);
        
        // Buka popup otomatis
        markerMasuk.openPopup();
        
    } catch (error) {
        console.error('Error update peta:', error);
    }
}

function updateInfoLokasi(tipe, lat, lng) {
    // Update koordinat
    document.getElementById('locationCoordsMasuk').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    document.getElementById('locationDistanceMasuk').innerHTML = `<span style="color:#10b981;">✅ Mode Uji Coba - Bisa absen di mana saja</span>`;
    
    // Reverse geocoding untuk mendapatkan alamat
    getAddressFromCoordinates(lat, lng);
}

function getAddressFromCoordinates(lat, lng) {
    // Gunakan Nominatim OpenStreetMap untuk reverse geocoding
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
        .then(response => response.json())
        .then(data => {
            if (data.display_name) {
                // Ambil bagian alamat yang penting
                const addressParts = [];
                if (data.address.road) addressParts.push(data.address.road);
                if (data.address.suburb) addressParts.push(data.address.suburb);
                if (data.address.city_district) addressParts.push(data.address.city_district);
                if (data.address.city) addressParts.push(data.address.city);
                if (data.address.state) addressParts.push(data.address.state);
                
                const formattedAddress = addressParts.join(', ') || data.display_name;
                document.getElementById('locationAddressMasuk').textContent = formattedAddress;
            }
        })
        .catch(error => {
            console.error('Error reverse geocoding:', error);
            document.getElementById('locationAddressMasuk').textContent = `Koordinat: ${lat.toFixed(4)}°, ${lng.toFixed(4)}°`;
        });
}

function updateGPSStatus(tipe, sukses, pesan) {
    const status = document.getElementById('gpsStatusMasuk');
    
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
    const video = document.getElementById('webcam');
    const status = document.getElementById('cameraStatus');
    
    if (!video || !video.srcObject) {
        alert('Kamera belum siap! Silakan refresh halaman jika kamera tidak muncul.');
        return;
    }
    
    // Buat canvas untuk capture
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');
    
    // Mirror effect (seperti selfie)
    ctx.translate(canvas.width, 0);
    ctx.scale(-1, 1);
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    // Deteksi wajah sederhana
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const wajahTerdeteksi = cekWajah(imageData);
    
    if (!wajahTerdeteksi) {
        alert('Wajah tidak terdeteksi! Pastikan wajah terlihat jelas di kamera dengan pencahayaan yang baik.');
        return;
    }
    
    // Set flag foto diambil
    fotoDiambilMasuk = true;
    
    // Tambah watermark/waktu
    ctx.setTransform(1, 0, 0, 1, 0, 0);
    
    // Watermark background
    ctx.fillStyle = 'rgba(0, 0, 0, 0.7)';
    ctx.fillRect(0, canvas.height - 40, canvas.width, 40);
    
    // Text watermark
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
    
    ctx.fillText(`Absensi Masuk - ${tanggal} ${waktu}`, 10, canvas.height - 20);
    
    // Tampilkan preview
    document.getElementById('photoPreview').style.display = 'block';
    document.getElementById('capturedPhoto').src = canvas.toDataURL('image/jpeg', 0.9);
    
    // Update status kamera
    if (status) {
        status.textContent = '✅ Foto berhasil diambil';
        status.style.color = '#10b981';
    }
    
    // Mulai timer
    mulaiTimer(tipe);
    
    // Cek validasi untuk submit button
    cekValidasiSubmit(tipe);
}

function cekWajah(imageData) {
    // Deteksi sederhana: cek kontras dan variasi warna
    const data = imageData.data;
    let kontras = 0;
    let sampleCount = 0;
    
    // Sample setiap 100 pixel untuk performa
    for (let i = 0; i < data.length; i += 400) { // 4 channels per pixel
        const r = data[i];
        const g = data[i + 1];
        const b = data[i + 2];
        
        // Cek apakah pixel memiliki warna yang berarti (bukan grayscale murni)
        if (Math.abs(r - g) > 30 || Math.abs(r - b) > 30 || Math.abs(g - b) > 30) {
            kontras++;
        }
        sampleCount++;
    }
    
    // Jika minimal 10% pixel memiliki kontras, anggap ada wajah
    return (kontras / sampleCount) > 0.1;
}

// ===================== TIMER =====================
function mulaiTimer(tipe) {
    // Hentikan timer sebelumnya
    if (timerMasuk) {
        clearInterval(timerMasuk);
    }
    
    // Reset counter
    hitungDetikMasuk = 0;
    
    const timerElement = document.getElementById('timerDisplay');
    
    // Update timer setiap detik
    timerMasuk = setInterval(() => {
        hitungDetikMasuk++;
        timerElement.textContent = `Timer: ${hitungDetikMasuk} detik`;
        
        // Jika timeout, reset foto
        if (hitungDetikMasuk >= TIMEOUT) {
            clearInterval(timerMasuk);
            alert('⏰ Waktu habis! Foto akan kadaluarsa dalam 30 detik. Silakan ambil foto ulang.');
            retakePhoto(tipe);
        }
    }, 1000);
}

// ===================== VALIDASI & SUBMIT =====================
function cekValidasiSubmit(tipe) {
    const submitBtn = document.getElementById('submitBtnMasuk');
    
    if (!submitBtn) return;
    
    // Untuk mode uji coba, hanya perlu foto
    if (fotoDiambilMasuk) {
        submitBtn.disabled = false;
        submitBtn.title = 'Klik untuk submit absensi masuk';
    } else {
        submitBtn.disabled = true;
        submitBtn.title = 'Belum mengambil foto';
    }
}

async function submitAbsensi(tipe) {
    // Validasi data
    if (!fotoDiambilMasuk) {
        alert('Silakan ambil foto terlebih dahulu!');
        return;
    }
    
    // Tampilkan loading
    showLoading('Menyimpan absensi...');
    
    try {
        // Ambil data yang diperlukan
        const photoBase64 = document.getElementById('capturedPhoto').src;
        const coordinates = lokasiMasuk ? 
            `${lokasiMasuk.lat.toFixed(6)}, ${lokasiMasuk.lng.toFixed(6)}` : 
            'Tidak terdeteksi';
        
        const address = document.getElementById('locationAddressMasuk').textContent;
        const waktu = new Date().toLocaleTimeString('id-ID');
        const tanggal = new Date().toLocaleDateString('id-ID');
        
        // Konfirmasi sebelum submit
        const konfirmasi = confirm(
            `Konfirmasi Absensi MASUK?\n\n` +
            `📅 Tanggal: ${tanggal}\n` +
            `⏰ Waktu: ${waktu}\n` +
            `📍 Lokasi: ${coordinates}\n` +
            `🏠 Alamat: ${address}\n\n` +
            `Mode: Uji Coba`
        );
        
        if (!konfirmasi) {
            hideLoading();
            return;
        }
        
        // Disable submit button
        const submitBtn = document.getElementById('submitBtnMasuk');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '⏳ Menyimpan...';
        submitBtn.disabled = true;
        
        // Simulasi API call (ganti dengan API real nanti)
        await simpanKeDatabase(tipe, {
            photo: photoBase64,
            latitude: lokasiMasuk?.lat,
            longitude: lokasiMasuk?.lng,
            address: address,
            timestamp: new Date().toISOString()
        });
        
        // Hentikan timer
        if (timerMasuk) {
            clearInterval(timerMasuk);
        }
        
        // Tampilkan sukses
        hideLoading();
        alert('✅ Absensi masuk berhasil disimpan!');
        
        // Reset form
        retakePhoto(tipe);
        
        // Redirect atau update UI
        updateUIAfterSubmit();
        
    } catch (error) {
        console.error('Error submit absensi:', error);
        hideLoading();
        alert('❌ Gagal menyimpan absensi. Silakan coba lagi.');
        
        // Reset button
        const submitBtn = document.getElementById('submitBtnMasuk');
        submitBtn.innerHTML = 'Submit Absensi Masuk';
        submitBtn.disabled = false;
    }
}

async function simpanKeDatabase(tipe, data) {
    // Simulasi penyimpanan ke database
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            try {
                // Simpan ke localStorage untuk sementara (development only)
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
                
                // Log untuk debugging
                console.log('Data absensi disimpan:', absensiData);
                
                // Simulasi juga ke session untuk akses cepat
                sessionStorage.setItem('last_attendance', JSON.stringify(absensiData));
                
                resolve(absensiData);
                
            } catch (error) {
                reject(error);
            }
        }, 1500); // Simulasi delay network
    });
}

function updateUIAfterSubmit() {
    // Update jam masuk di halaman pulang nanti
    const waktuMasuk = new Date().toLocaleTimeString('id-ID', { 
        hour: '2-digit', 
        minute: '2-digit' 
    });
    
    // Simpan di sessionStorage untuk halaman pulang
    sessionStorage.setItem('waktu_masuk', waktuMasuk);
    
    // Tampilkan notifikasi sukses
    const successDiv = document.createElement('div');
    successDiv.innerHTML = `
        <div style="position: fixed; top: 20px; right: 20px; background: #10b981; color: white; padding: 12px 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 1000; animation: slideIn 0.3s ease-out;">
            ✅ Absensi masuk berhasil dicatat!
        </div>
    `;
    document.body.appendChild(successDiv);
    
    // Hapus notifikasi setelah 3 detik
    setTimeout(() => {
        if (successDiv.parentNode) {
            successDiv.parentNode.removeChild(successDiv);
        }
    }, 3000);
}

// ===================== FUNGSI BANTU =====================
function retakePhoto(tipe) {
    // Reset semua state foto
    fotoDiambilMasuk = false;
    
    // Hentikan timer
    if (timerMasuk) {
        clearInterval(timerMasuk);
        timerMasuk = null;
    }
    
    // Sembunyikan preview
    document.getElementById('photoPreview').style.display = 'none';
    
    // Reset timer display
    document.getElementById('timerDisplay').textContent = 'Timer: 0 detik';
    
    // Update camera status
    const status = document.getElementById('cameraStatus');
    if (status) {
        status.textContent = '✅ Kamera siap';
        status.style.color = '#10b981';
    }
    
    // Reset submit button
    const submitBtn = document.getElementById('submitBtnMasuk');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.title = 'Belum mengambil foto';
    }
}

function showLoading(text) {
    const overlay = document.getElementById('loadingOverlay');
    const textEl = document.getElementById('loadingText');
    
    if (textEl) textEl.textContent = text;
    if (overlay) overlay.style.display = 'flex';
}

function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) overlay.style.display = 'none';
}

// ===================== EXPORT FUNGSI UNTUK SIDEBAR =====================
window.startKamera = startKamera;
window.updateGPS = updateGPS;
window.refreshGPS = refreshGPS;
</script>
