@extends('layouts.app')

@section('title', 'Absensi Pulang')

@section('styles')
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

    .btn-success { background: #10b981; color: white; }
    .btn-success:hover { background: #059669; }

    .btn-danger { background: #ef4444; color: white; }
    .btn-danger:hover { background: #dc2626; }

    .btn:disabled { opacity: 0.5; cursor: not-allowed; }

    .alert-box {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; }

    .content-title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #1f2937;
    }

    .content-description { color: #6b7280; margin-bottom: 20px; }

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

    .info-item { font-size: 14px; }
    .info-label { color: #6b7280; font-weight: 500; }
    .info-value { color: #1f2937; font-weight: 600; }

    .loading-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
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

    /* Face status */
    #faceStatusPulang {
        margin-top: 10px;
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: #f8fafc;
        font-size: 13px;
        color: #374151;
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

                <!-- Face status -->
                <div id="faceStatusPulang">⏳ Menyiapkan face detector...</div>

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
<!-- MediaPipe Face Detection -->
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/face_detection/face_detection.js"></script>

<script>
/**
 * ===================== KONFIGURASI =====================
 */
const TIMEOUT = 30; // detik
const KANTOR_LAT = -6.058908;
const KANTOR_LNG = 106.653040;

/**
 * ===================== VARIABEL =====================
 */
let kameraPulang = null;
let lokasiPulang = null;
let petaPulang = null;
let timerPulang = null;
let hitungDetikPulang = 0;
let fotoDiambilPulang = false;
let markerPulang = null;

// Data absensi masuk hari ini (dari session/localStorage)
let absensiMasukHariIni = null;

// Interval info card
let attendanceInterval = null;

// Face detection
let faceDetector = null;
let faceDetectorReady = false;

/**
 * ===================== UTIL DURASI =====================
 */
function pad2(n) { return String(n).padStart(2, '0'); }
function formatHHMMSS(ms) {
  const total = Math.floor(ms / 1000);
  const h = Math.floor(total / 3600);
  const m = Math.floor((total % 3600) / 60);
  const s = total % 60;
  return `${pad2(h)}:${pad2(m)}:${pad2(s)}`;
}

/**
 * ===================== FACE DETECTION =====================
 */
function setFaceStatus(text, ok = null) {
  const el = document.getElementById('faceStatusPulang');
  if (!el) return;
  el.textContent = text;

  if (ok === true) {
    el.style.background = '#f0fdf4';
    el.style.borderColor = '#bbf7d0';
    el.style.color = '#065f46';
  } else if (ok === false) {
    el.style.background = '#fef2f2';
    el.style.borderColor = '#fecaca';
    el.style.color = '#991b1b';
  } else {
    el.style.background = '#f8fafc';
    el.style.borderColor = '#e5e7eb';
    el.style.color = '#374151';
  }
}

async function initFaceDetector() {
  try {
    setFaceStatus('⏳ Menyiapkan face detector...', null);

    if (typeof FaceDetection === 'undefined') {
      setFaceStatus('❌ Library FaceDetection tidak termuat (cek koneksi/CDN).', false);
      faceDetectorReady = false;
      return;
    }

    faceDetector = new FaceDetection({
      locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/face_detection/${file}`,
    });

    faceDetector.setOptions({
      model: 'short', // 'short' lebih cepat; ganti 'full' kalau butuh lebih akurat
      minDetectionConfidence: 0.6,
    });

    faceDetector.onResults(() => {});
    faceDetectorReady = true;

    setFaceStatus('✅ Face detector siap. Ambil foto dengan wajah terlihat jelas.', true);
  } catch (e) {
    console.error('initFaceDetector error:', e);
    faceDetectorReady = false;
    setFaceStatus('❌ Gagal inisialisasi face detector.', false);
  }
}

function detectFaceFromCanvas(canvas) {
  return new Promise(async (resolve) => {
    if (!faceDetectorReady || !faceDetector) {
      setFaceStatus('⚠️ Face detector belum siap. Coba refresh halaman.', false);
      return resolve(false);
    }

    const prev = faceDetector.onResults;
    faceDetector.onResults((results) => {
      const hasFace = !!(results && results.detections && results.detections.length > 0);
      setFaceStatus(hasFace ? '✅ Wajah terdeteksi.' : '❌ Wajah tidak terdeteksi. Dekatkan wajah & perbaiki pencahayaan.', hasFace);
      faceDetector.onResults = prev;
      resolve(hasFace);
    });

    try {
      await faceDetector.send({ image: canvas });
    } catch (e) {
      console.error('detectFaceFromCanvas error:', e);
      faceDetector.onResults = prev;
      setFaceStatus('❌ Error saat deteksi wajah.', false);
      resolve(false);
    }
  });
}

/**
 * ===================== INISIALISASI =====================
 */
document.addEventListener('DOMContentLoaded', async function() {
  console.log('Sistem Absensi Pulang dimulai...');

  await initFaceDetector();

  // Load data absensi masuk (integrasi)
  loadAbsensiMasukData();

  // Update info absensi masuk setiap detik
  if (attendanceInterval) clearInterval(attendanceInterval);
  attendanceInterval = setInterval(updateAttendanceInfo, 1000);
  updateAttendanceInfo();

  // Setup kamera
  startKamera('pulang');

  // Setup peta
  initPetaPulang();

  // Auto refresh GPS setiap 30 detik
  setInterval(() => updateGPS('pulang'), 30000);
});

/**
 * ===================== LOAD DATA ABSENSI MASUK (INTEGRASI) =====================
 * Prioritas:
 * 1) sessionStorage.waktu_masuk_iso (dari halaman absen masuk)
 * 2) localStorage.absensi_data (fallback kalau buka pulang langsung)
 */
function loadAbsensiMasukData() {
    if(sessionStorage.getItem('absensi_pulang_done') === 'true'){
        absensiMasukHariIni = null;
        
        document.getElementById('displayWaktuMasuk').textContent = 'Belum ada data'; 
        document.getElementById('displayLamaKerja').textContent = '00:00:00'; 
        document.getElementById('displayStatusMasuk').innerHTML = '<span style="color: #10b981"> Absensi selesai hari ini</span>'; 
        return;
    }
  try {
    // 1) Prioritas ISO dari halaman masuk
    const masukIso = sessionStorage.getItem('waktu_masuk_iso');
    if (masukIso) {
      absensiMasukHariIni = { created_at: masukIso, type: 'masuk' };

      const t = new Date(masukIso);
      document.getElementById('displayWaktuMasuk').textContent =
        t.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

      document.getElementById('displayStatusMasuk').textContent = 'Sudah Absen';
      return;
    }

    // 2) Fallback: cari di localStorage
    const absensiData = JSON.parse(localStorage.getItem('absensi_data') || '[]');
    const today = new Date().toDateString();

    const found = absensiData.find(item => {
      const itemDate = new Date(item.created_at).toDateString();
      return item.type === 'masuk' && itemDate === today;
    });

    if (found) {
      absensiMasukHariIni = found;

      const waktu = new Date(found.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
      document.getElementById('displayWaktuMasuk').textContent = waktu;
      document.getElementById('displayStatusMasuk').textContent = 'Sudah Absen';

      // simpan ke session biar konsisten (integrasi)
      sessionStorage.setItem('waktu_masuk_iso', found.created_at);
    }
  } catch (error) {
    console.error('Error loading attendance data:', error);
  }
}

/**
 * ===================== UPDATE INFO ABSENSI (DINAMIS) =====================
 */
function updateAttendanceInfo() {
  const now = new Date();

  // Waktu sekarang
  document.getElementById('displayWaktuSekarang').textContent =
    now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

  // Lama kerja + status
  if (absensiMasukHariIni) {
    const masukTime = new Date(absensiMasukHariIni.created_at);
    const diffMs = Math.max(0, now - masukTime);
    document.getElementById('displayLamaKerja').textContent = formatHHMMSS(diffMs);

    // Rule absen pulang mulai 16:30 (logika benar)
    const bisaAbsenPulang = (now.getHours() > 16) || (now.getHours() === 16 && now.getMinutes() >= 30);

    // kalau sudah absen pulang hari ini, tampilkan lengkap
    const pulangDone = sessionStorage.getItem('absensi_pulang_done') === 'true';
    if (pulangDone) {
      document.getElementById('displayStatusMasuk').innerHTML =
        '<span style="color: #10b981;">✓ Absensi lengkap hari ini</span>';
      return;
    }

    document.getElementById('displayStatusMasuk').innerHTML = bisaAbsenPulang
      ? '<span style="color: #10b981;">✓ Bisa absen pulang</span>'
      : '<span style="color: #f59e0b;">⚠ Tunggu sampai 16:30</span>';
  }
}

/**
 * ===================== KAMERA =====================
 */
async function startKamera(tipe) {
  const video = document.getElementById('webcamPulang');
  const status = document.getElementById('cameraStatusPulang');
  if (!video) return;

  try {
    if (status) status.textContent = 'Mengakses kamera...';

    if (kameraPulang) {
      kameraPulang.getTracks().forEach(track => track.stop());
    }

    const stream = await navigator.mediaDevices.getUserMedia({
      video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } },
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

/**
 * ===================== PETA =====================
 */
function initPetaPulang() {
  if (typeof L === 'undefined') {
    console.log('Leaflet belum dimuat, menunggu...');
    setTimeout(initPetaPulang, 500);
    return;
  }

  try {
    petaPulang = L.map('mapPulang').setView([KANTOR_LAT, KANTOR_LNG], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors',
      maxZoom: 19
    }).addTo(petaPulang);

    L.marker([KANTOR_LAT, KANTOR_LNG])
      .addTo(petaPulang)
      .bindPopup('<b>📍 Lokasi Kantor</b><br>Kawasan Multi Guna Estate')
      .openPopup();

    L.circle([KANTOR_LAT, KANTOR_LNG], {
      color: '#3b82f6',
      fillColor: '#3b82f6',
      fillOpacity: 0.1,
      radius: 100
    }).addTo(petaPulang);

    console.log('Peta pulang berhasil diinisialisasi');
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

/**
 * ===================== GPS & LOKASI =====================
 */
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
    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
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
  switch (err.code) {
    case err.PERMISSION_DENIED: errorMessage = 'Izin lokasi ditolak'; break;
    case err.POSITION_UNAVAILABLE: errorMessage = 'Informasi lokasi tidak tersedia'; break;
    case err.TIMEOUT: errorMessage = 'Timeout mendapatkan lokasi'; break;
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

    if (markerPulang) petaPulang.removeLayer(markerPulang);

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
    .then(r => r.json())
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
    .catch(err => {
      console.error('Error reverse geocoding:', err);
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

/**
 * ===================== AMBIL FOTO (FACE DETECTION) =====================
 */
async function ambilFoto(tipe) {
  const video = document.getElementById('webcamPulang');
  const status = document.getElementById('cameraStatusPulang');

  if (!video || !video.srcObject) {
    alert('Kamera belum siap! Silakan refresh halaman jika kamera tidak muncul.');
    return;
  }

  // Optional: kalau belum ada absen masuk, konfirmasi
  if (!absensiMasukHariIni) {
    const konfirm = confirm(
      'Anda belum melakukan absensi masuk hari ini.\n' +
      'Apakah Anda ingin tetap melanjutkan absensi pulang?'
    );
    if (!konfirm) return;
  }

  if (!faceDetectorReady) {
    alert('Face detector belum siap. Tunggu sebentar atau refresh halaman.');
    return;
  }

  // Capture ke canvas
  const canvas = document.createElement('canvas');
  canvas.width = video.videoWidth;
  canvas.height = video.videoHeight;
  const ctx = canvas.getContext('2d');

  // Mirror effect
  ctx.translate(canvas.width, 0);
  ctx.scale(-1, 1);
  ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

  // Face detect (beneran)
  setFaceStatus('⏳ Mengecek wajah...', null);
  const wajahTerdeteksi = await detectFaceFromCanvas(canvas);
  if (!wajahTerdeteksi) {
    alert('Wajah tidak terdeteksi! Pastikan wajah terlihat jelas, dekatkan kamera, dan pencahayaan cukup.');
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
  const tanggal = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
  ctx.fillText(`Absensi Pulang - ${tanggal} ${waktu}`, 10, canvas.height - 20);

  // Preview
  document.getElementById('photoPreviewPulang').style.display = 'block';
  document.getElementById('capturedPhotoPulang').src = canvas.toDataURL('image/jpeg', 0.9);

  if (status) {
    status.textContent = '✅ Foto berhasil diambil';
    status.style.color = '#10b981';
  }

  mulaiTimer(tipe);
  cekValidasiSubmit(tipe);
}

/**
 * ===================== TIMER FOTO =====================
 */
function mulaiTimer(tipe) {
  if (timerPulang) clearInterval(timerPulang);

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

/**
 * ===================== VALIDASI & SUBMIT =====================
 */
function cekValidasiSubmit(tipe) {
  const submitBtn = document.getElementById('submitBtnPulang');
  if (!submitBtn) return;

  submitBtn.disabled = !fotoDiambilPulang;
  submitBtn.title = fotoDiambilPulang ? 'Klik untuk submit absensi pulang' : 'Belum mengambil foto';
}

async function submitAbsensi(tipe) {
  if (!fotoDiambilPulang) {
    alert('Silakan ambil foto terlebih dahulu!');
    return;
  }

  // Optional: enforce rule 16:30 (kalau mau benar-benar dikunci)
  const now = new Date();
  const bisaAbsenPulang = (now.getHours() > 16) || (now.getHours() === 16 && now.getMinutes() >= 30);
  // Kalau mau strict:
  // if (!bisaAbsenPulang) { alert('Belum bisa absen pulang. Tunggu sampai 16:30.'); return; }

  showLoading('Menyimpan absensi...');

  try {
    const photoBase64 = document.getElementById('capturedPhotoPulang').src;
    const coordinates = lokasiPulang ? `${lokasiPulang.lat.toFixed(6)}, ${lokasiPulang.lng.toFixed(6)}` : 'Tidak terdeteksi';
    const address = document.getElementById('locationAddressPulang').textContent;
    const waktu = new Date().toLocaleTimeString('id-ID');
    const tanggal = new Date().toLocaleDateString('id-ID');

    // waktu masuk & lama kerja (pakai ISO integrasi)
    let waktuMasuk = 'Tidak tercatat';
    let lamaKerja = '-';
    if (absensiMasukHariIni) {
      const masukTime = new Date(absensiMasukHariIni.created_at);
      waktuMasuk = masukTime.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
      lamaKerja = formatHHMMSS(Math.max(0, new Date() - masukTime));
    }

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

    const submitBtn = document.getElementById('submitBtnPulang');
    submitBtn.innerHTML = '⏳ Menyimpan...';
    submitBtn.disabled = true;

    await simpanKeDatabase(tipe, {
      photo: photoBase64,
      latitude: lokasiPulang?.lat,
      longitude: lokasiPulang?.lng,
      address: address,
      timestamp: new Date().toISOString(),
      waktu_masuk_iso: absensiMasukHariIni?.created_at || null,
      waktu_masuk: waktuMasuk,
      lama_kerja: lamaKerja
    });

    if (timerPulang) clearInterval(timerPulang);

    hideLoading();
    alert('✅ Absensi pulang berhasil disimpan!');

    retakePhoto(tipe);
    updateUIAfterSubmit();
  } catch (error) {
    console.error('Error submit absensi pulang:', error);
    hideLoading();
    alert('❌ Gagal menyimpan absensi. Silakan coba lagi.');

    const submitBtn = document.getElementById('submitBtnPulang');
    submitBtn.innerHTML = 'Submit Absensi Pulang';
    submitBtn.disabled = false;
  }
}

async function simpanKeDatabase(tipe, data) {
  // Simulasi penyimpanan ke localStorage seperti halaman masuk
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

        const existingData = JSON.parse(localStorage.getItem('absensi_data') || '[]');
        existingData.push(absensiData);
        localStorage.setItem('absensi_data', JSON.stringify(existingData));

        sessionStorage.setItem('last_attendance_pulang', JSON.stringify(absensiData));
        resolve(absensiData);
      } catch (e) {
        reject(e);
      }
    }, 1500);
  });
}

function updateUIAfterSubmit() {
  // tandai pulang selesai
  sessionStorage.setItem('absensi_pulang_done', 'true');

  // optional: kalau ingin reset jam kerja setelah pulang, hapus waktu masuk
  sessionStorage.removeItem('waktu_masuk')
  sessionStorage.removeItem('waktu_masuk_iso');

    // reset state di halaman
    absensiMasukHariIni = null

    // stop update interval (lama kerja)
    if(attendanceInterval){
        clearInterval(attendanceInterval);
        attendanceInterval = null;
    }

    // reset UI
    document.getElementById('displayWaktuMasuk').textContent = 'Belum ada data'; 
    document.getElementById('displayLamaKerja').textContent = '00:00:00'; 
    
  // update status card
  document.getElementById('displayStatusMasuk').innerHTML =
    '<span style="color: #10b981;">✓ Absensi lengkap hari ini</span>';

  // notif sukses
  const successDiv = document.createElement('div');
  successDiv.innerHTML = `
    <div style="position: fixed; top: 20px; right: 20px; background: #10b981; color: white; padding: 12px 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 1000;">
      ✅ Absensi pulang berhasil! Selamat beristirahat.
    </div>
  `;
  document.body.appendChild(successDiv);
  setTimeout(() => successDiv.remove(), 3000);
}

/**
 * ===================== FUNGSI BANTU =====================
 */
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
    submitBtn.innerHTML = 'Submit Absensi Pulang';
  }

  if (faceDetectorReady) {
    setFaceStatus('✅ Face detector siap. Ambil foto dengan wajah terlihat jelas.', true);
  } else {
    setFaceStatus('⚠️ Face detector belum siap.', false);
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

// export
window.startKamera = startKamera;
window.updateGPS = updateGPS;
window.refreshGPS = refreshGPS;
</script>
@endsection