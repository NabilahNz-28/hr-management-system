@extends('layouts.absen')

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
    <div class="page-content active" id="absensi-pulang">
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
        <div class="alert-box alert-info">
            <i class="bi bi-geo-alt-fill text-primary" style="font-size: 24px;"></i>
            <div>
                <div style="font-weight: 500;">Verifikasi Lokasi GPS</div>
                <div style="font-size: 13px;">Pastikan Anda berada di dalam radius 100m dari lokasi kantor untuk dapat melakukan absensi.</div>
            </div>
        </div>

        <!-- Grid Layout -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 20px;">
            <!-- Kolom Kiri: Kamera -->
            <div>
                <div class="content-title" style="font-size: 16px;">Foto Wajah</div>

                <div id="alreadyAttendedBoxPulang" style="display: none; margin-bottom: 16px; padding: 16px; background: #ecfdf5; border: 1.5px solid #10b981; border-radius: 12px; color: #065f46; text-align: center; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.1);">
                    <div id="alreadyAttendedIconPulang"><i class="bi bi-check-circle-fill text-success" style="font-size: 28px; display: inline-block; margin-bottom: 4px;"></i></div>
                    <div style="font-weight: 700; font-size: 15px;" id="alreadyAttendedTitlePulang">Anda sudah melakukan Absensi Pulang hari ini</div>
                    <div style="font-size: 13px; margin-top: 4px; color: #047857;" id="alreadyAttendedTextPulang">Tidak dapat melakukan absen pulang kembali di hari yang sama.</div>
                </div>

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
                <div id="faceStatusPulang">Menyiapkan pendeteksi wajah...</div>

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
                        <div style="font-weight: 500;">Mencari Lokasi GPS Anda...</div>
                        <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">Pastikan Anda mengizinkan akses lokasi pada browser</div>
                    </div>
                </div>

                <!-- GPS Success Info -->
                <div id="gpsSuccessInfoPulang" style="display: none; margin-bottom: 16px; padding: 12px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; font-size: 13px; color: #166534;">
                    <div style="font-weight: 600; margin-bottom: 4px;">Informasi Lokasi:</div>
                    <div>Lokasi: <span id="locationAddressPulang">Mendeteksi alamat...</span></div>
                    <div style="margin-top: 8px;">Koordinat: <span id="locationCoordsPulang">-</span></div>
                    <div style="margin-top: 8px;">Status: <span id="locationDistancePulang" style="color: #6b7280;">Menghitung jarak...</span></div>
                    <button onclick="refreshGPS('pulang')" style="margin-top: 15px; padding: 8px 16px; background: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; width: 100%; transition: background 0.2s;">
                        Refresh Lokasi GPS
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
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/face_detection/face_detection.js"></script>

<script>
(function() {
    const currentUserId = String(@json(auth()->id()));
    const ATT_KEYS = ['waktu_masuk_iso', 'waktu_masuk', 'absensi_pulang_done',
                      'last_attendance', 'last_attendance_pulang'];
    if (sessionStorage.getItem('attendance_owner_id') !== currentUserId) {
        ATT_KEYS.forEach(k => sessionStorage.removeItem(k));
        localStorage.removeItem('absensi_data');
        sessionStorage.setItem('attendance_owner_id', currentUserId);
    }
})();

const TIMEOUT = 30;
const KANTOR_LAT = -6.058908;
const KANTOR_LNG = 106.653040;

// GPS Accuracy Config
const GPS_ACCURACY_THRESHOLD = 100;
const GPS_READINGS_NEEDED = 8;
const GPS_READING_INTERVAL = 2000;
let gpsReadings = [];
let gpsReadingTimer = null;

let kameraPulang = null;
let lokasiPulang = null;
let petaPulang = null;
let timerPulang = null;
let hitungDetikPulang = 0;
let fotoDiambilPulang = false;
let markerPulang = null;
let absensiMasukHariIni = null;
let attendanceInterval = null;
let faceDetector = null;
let faceDetectorReady = false;

function pad2(n) { return String(n).padStart(2, '0'); }
function formatHHMMSS(ms) {
  const total = Math.floor(ms / 1000);
  const h = Math.floor(total / 3600);
  const m = Math.floor((total % 3600) / 60);
  const s = total % 60;
  return `${pad2(h)}:${pad2(m)}:${pad2(s)}`;
}

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
    setFaceStatus('Menyiapkan pendeteksi wajah...', null);
    if (typeof FaceDetection === 'undefined') {
      setFaceStatus('Library FaceDetection tidak termuat (cek koneksi/CDN).', false);
      faceDetectorReady = false;
      return;
    }
    faceDetector = new FaceDetection({
      locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/face_detection/${file}`,
    });
    faceDetector.setOptions({ model: 'short', minDetectionConfidence: 0.6 });
    faceDetector.onResults(() => {});
    faceDetectorReady = true;
    setFaceStatus('Pendeteksi wajah siap. Ambil foto dengan wajah terlihat jelas.', true);
  } catch (e) {
    console.error('initFaceDetector error:', e);
    faceDetectorReady = false;
    setFaceStatus('Gagal inisialisasi pendeteksi wajah.', false);
  }
}

function detectFaceFromCanvas(canvas) {
  return new Promise(async (resolve) => {
    if (!faceDetectorReady || !faceDetector) {
      setFaceStatus('Pendeteksi wajah belum siap. Coba refresh halaman.', false);
      return resolve(false);
    }
    const prev = faceDetector.onResults;
    faceDetector.onResults((results) => {
      const hasFace = !!(results && results.detections && results.detections.length > 0);
      setFaceStatus(hasFace ? 'Wajah terdeteksi.' : 'Wajah tidak terdeteksi. Dekatkan wajah & perbaiki pencahayaan.', hasFace);
      faceDetector.onResults = prev;
      resolve(hasFace);
    });
    try {
      await faceDetector.send({ image: canvas });
    } catch (e) {
      console.error('detectFaceFromCanvas error:', e);
      faceDetector.onResults = prev;
      setFaceStatus('Error saat deteksi wajah.', false);
      resolve(false);
    }
  });
}

document.addEventListener('DOMContentLoaded', async function() {
  await initFaceDetector();
  await loadAbsensiMasukData();
  if (attendanceInterval) clearInterval(attendanceInterval);
  attendanceInterval = setInterval(updateAttendanceInfo, 1000);
  updateAttendanceInfo();
  startKamera('pulang');
  initPetaPulang();
  setInterval(() => updateGPS('pulang'), 30000);
});

async function loadAbsensiMasukData() {
  try {
    const res = await fetch("{{ route('absensi.cek') }}?attendance_type=masuk", {
      headers: { 'Accept': 'application/json' }
    });
    const data = await res.json();
    const elWaktu  = document.getElementById('displayWaktuMasuk');
    const elLama   = document.getElementById('displayLamaKerja');
    const elStatus = document.getElementById('displayStatusMasuk');
    if (data.has_pulang) {
      absensiMasukHariIni = null;
      sessionStorage.setItem('absensi_pulang_done', 'true');
      elWaktu.textContent  = data.masuk_time || '—';
      elLama.textContent   = '00:00:00';
      elStatus.innerHTML   = 'Absensi selesai hari ini';
      const btn = document.getElementById('captureBtnPulang');
      if (btn) btn.style.display = 'none';
      const box = document.getElementById('alreadyAttendedBoxPulang');
      if (box) box.style.display = 'block';
      return;
    }
    if (data.has_masuk && data.masuk_iso) {
      absensiMasukHariIni = { created_at: data.masuk_iso, type: 'masuk' };
      sessionStorage.setItem('waktu_masuk_iso', data.masuk_iso);
      sessionStorage.removeItem('absensi_pulang_done');
      const t = new Date(data.masuk_iso);
      elWaktu.textContent  = t.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
      elStatus.textContent = 'Sudah Absen';
      return;
    }
    absensiMasukHariIni = null;
    sessionStorage.removeItem('waktu_masuk_iso');
    sessionStorage.removeItem('absensi_pulang_done');
    elWaktu.textContent  = 'Belum absen masuk';
    elLama.textContent   = '00:00:00';
    elStatus.innerHTML   = 'Belum absen masuk';
    const btn = document.getElementById('captureBtnPulang');
    if (btn) btn.style.display = 'none';
    const box = document.getElementById('alreadyAttendedBoxPulang');
    if (box) {
      box.style.display = 'block';
      box.style.background = '#fef2f2';
      box.style.borderColor = '#ef4444';
      box.style.color = '#991b1b';
      document.getElementById('alreadyAttendedTitlePulang').textContent = 'Anda belum melakukan Absensi Masuk hari ini';
      document.getElementById('alreadyAttendedTextPulang').textContent = 'Silakan lakukan absen masuk terlebih dahulu sebelum absen pulang.';
    }
  } catch (error) {
    console.error('Error loading attendance data:', error);
  }
}

function updateAttendanceInfo() {
  const now = new Date();
  document.getElementById('displayWaktuSekarang').textContent =
    now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
  if (absensiMasukHariIni) {
    const masukTime = new Date(absensiMasukHariIni.created_at);
    const diffMs = Math.max(0, now - masukTime);
    document.getElementById('displayLamaKerja').textContent = formatHHMMSS(diffMs);
    const bisaAbsenPulang = (now.getHours() > 16) || (now.getHours() === 16 && now.getMinutes() >= 30);
    const pulangDone = sessionStorage.getItem('absensi_pulang_done') === 'true';
    if (pulangDone) {
      document.getElementById('displayStatusMasuk').innerHTML = 'Absensi lengkap hari ini';
      return;
    }
    document.getElementById('displayStatusMasuk').innerHTML = bisaAbsenPulang
      ? 'Bisa absen pulang'
      : 'Tunggu sampai 16:30';
  }
}

async function startKamera(tipe) {
  const video = document.getElementById('webcamPulang');
  const status = document.getElementById('cameraStatusPulang');
  if (!video) return;
  try {
    if (status) status.textContent = 'Mengakses kamera...';
    if (kameraPulang) kameraPulang.getTracks().forEach(track => track.stop());
    const stream = await navigator.mediaDevices.getUserMedia({
      video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } },
      audio: false
    });
    video.srcObject = stream;
    kameraPulang = stream;
    if (status) {
      status.textContent = 'Kamera siap';
      status.style.color = '#10b981';
    }
  } catch (error) {
    if (status) {
      status.textContent = 'Gagal mengakses kamera';
      status.style.color = '#ef4444';
    }
  }
}

function initPetaPulang() {
  if (typeof L === 'undefined') {
    setTimeout(initPetaPulang, 500);
    return;
  }
  try {
    petaPulang = L.map('mapPulang').setView([KANTOR_LAT, KANTOR_LNG], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap contributors' }).addTo(petaPulang);
    setTimeout(() => updateGPS('pulang'), 1000);
  } catch (error) {
    console.error('Error inisialisasi peta pulang:', error);
  }
}

function updateGPS(tipe) {
  if (!navigator.geolocation) {
    updateGPSStatus(tipe, false, 'Browser tidak mendukung GPS');
    updateLokasiFallback(tipe, 'browser');
    return;
  }
  gpsReadings = [];
  if (gpsReadingTimer) clearInterval(gpsReadingTimer);
  updateGPSStatus(tipe, false, `Mengambil ${GPS_READINGS_NEEDED} pembacaan GPS...`);
  takeGPSReading(tipe);
  gpsReadingTimer = setInterval(() => takeGPSReading(tipe), GPS_READING_INTERVAL);
}

function takeGPSReading(tipe) {
  navigator.geolocation.getCurrentPosition(
    (pos) => {
      gpsReadings.push({ lat: pos.coords.latitude, lng: pos.coords.longitude, accuracy: pos.coords.accuracy });
      updateGPSStatus(tipe, false, `Mengambil pembacaan... (${gpsReadings.length}/${GPS_READINGS_NEEDED})`);
      if (gpsReadings.length >= GPS_READINGS_NEEDED) {
        clearInterval(gpsReadingTimer);
        gpsReadingTimer = null;
        processGPSReadings(tipe);
      }
    },
    () => {},
    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
  );
}

function processGPSReadings(tipe) {
  if (gpsReadings.length === 0) {
    updateGPSStatus(tipe, false, 'Tidak ada pembacaan GPS berhasil');
    attemptIPGeolocation(tipe);
    return;
  }
  if (gpsReadings.length === 1) {
    applyGPSResult(gpsReadings[0].lat, gpsReadings[0].lng, gpsReadings[0].accuracy, tipe);
    return;
  }
  const meanLat = gpsReadings.reduce((s, r) => s + r.lat, 0) / gpsReadings.length;
  const meanLng = gpsReadings.reduce((s, r) => s + r.lng, 0) / gpsReadings.length;
  const stdDevLat = Math.sqrt(gpsReadings.reduce((s, r) => s + Math.pow(r.lat - meanLat, 2), 0) / gpsReadings.length);
  const stdDevLng = Math.sqrt(gpsReadings.reduce((s, r) => s + Math.pow(r.lng - meanLng, 2), 0) / gpsReadings.length);
  const filtered = gpsReadings.filter(r => Math.abs(r.lat - meanLat) <= 2 * stdDevLat && Math.abs(r.lng - meanLng) <= 2 * stdDevLng);
  if (filtered.length === 0) {
    applyGPSResult(meanLat, meanLng, Math.min(...gpsReadings.map(r => r.accuracy)), tipe);
    return;
  }
  let wLat = 0, wLng = 0, tw = 0;
  filtered.forEach(r => { const w = 1 / Math.max(r.accuracy, 1); wLat += r.lat * w; wLng += r.lng * w; tw += w; });
  const finalLat = wLat / tw, finalLng = wLng / tw;
  const avgAcc = filtered.reduce((s, r) => s + r.accuracy, 0) / filtered.length;
  const finalAcc = avgAcc / Math.sqrt(filtered.length);
  applyGPSResult(finalLat, finalLng, finalAcc, tipe);
}

function applyGPSResult(lat, lng, accuracy, tipe) {
  lokasiPulang = { lat, lng, accuracy, source: 'gps' };
  updatePetaPulang(lat, lng);
  updateInfoLokasi(tipe, lat, lng);
  updateGPSStatus(tipe, true, `Akurasi: ±${Math.round(accuracy)}m ✓ (${gpsReadings.length} pembacaan)`);
  cekValidasiSubmit(tipe);
}

function refreshGPS(tipe) { updateGPS(tipe); }

function attemptIPGeolocation(tipe) {
  fetch('https://ipapi.co/json/')
    .then(res => res.json())
    .then(data => {
      if (data.latitude && data.longitude) {
        lokasiPulang = { lat: data.latitude, lng: data.longitude, accuracy: 5000, source: 'ip' };
        updatePetaPulang(data.latitude, data.longitude);
        updateInfoLokasi(tipe, data.latitude, data.longitude);
        updateGPSStatus(tipe, true, `Lokasi via IP: ${data.city || 'Unknown'} (±5km)`);
        cekValidasiSubmit(tipe);
      } else {
        updateLokasiFallback(tipe, 'ip');
      }
    })
    .catch(() => updateLokasiFallback(tipe, 'ip'));
}

function updateLokasiFallback(tipe, source) {
  if (gpsWatchId !== null) { navigator.geolocation.clearWatch(gpsWatchId); gpsWatchId = null; }
  lokasiPulang = { lat: KANTOR_LAT, lng: KANTOR_LNG, accuracy: 1000, source: 'kantor' };
  updatePetaPulang(KANTOR_LAT, KANTOR_LNG);
  updateInfoLokasi(tipe, KANTOR_LAT, KANTOR_LNG);
  const sourceLabel = source === 'ip' ? 'IP juga gagal, ' : '';
  updateGPSStatus(tipe, false, `${sourceLabel}Menggunakan lokasi kantor (mode fallback)`);
  cekValidasiSubmit(tipe);
}

function updatePetaPulang(lat, lng) {
  if (!petaPulang) return;
  petaPulang.setView([lat, lng], 15);
  if (markerPulang) petaPulang.removeLayer(markerPulang);
  markerPulang = L.marker([lat, lng]).addTo(petaPulang);
}

function updateInfoLokasi(tipe, lat, lng) {
  document.getElementById('locationCoordsPulang').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
  const distance = haversineDistance(lat, lng, KANTOR_LAT, KANTOR_LNG);
  const distanceEl = document.getElementById('locationDistancePulang');
  if (distance <= 100) {
    distanceEl.innerHTML = `<span style="color:#10b981;">✅ Dalam radius kantor (±${Math.round(distance)}m)</span>`;
  } else {
    distanceEl.innerHTML = `<span style="color:#ef4444;">⚠️ Di luar radius kantor (±${Math.round(distance)}m) - Maks 100m</span>`;
  }
  document.getElementById('gpsSuccessInfoPulang').style.display = 'block';
  document.getElementById('gpsStatusPulang').style.display = 'none';
  getAddressFromCoordinates(lat, lng);
}

function haversineDistance(lat1, lng1, lat2, lng2) {
  const R = 6371000;
  const dLat = (lat2 - lat1) * Math.PI / 180;
  const dLng = (lng2 - lng1) * Math.PI / 180;
  const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLng / 2) * Math.sin(dLng / 2);
  return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}

function getAddressFromCoordinates(lat, lng) {
  fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
    .then(r => r.json())
    .then(data => {
      if (data.display_name) document.getElementById('locationAddressPulang').textContent = data.display_name;
    });
}

function updateGPSStatus(tipe, sukses, pesan) {
  const status = document.getElementById('gpsStatusPulang');
  if (!status) return;
  if (sukses) {
    status.innerHTML = `<div>✅</div><div><div style="font-weight:500;">GPS Aktif</div><div style="font-size:12px;">${pesan}</div></div>`;
    status.style.background = '#f0fdf4';
    status.style.borderColor = '#bbf7d0';
  } else {
    status.innerHTML = `<div>⚠️</div><div><div style="font-weight:500;">Mode Simulasi</div><div style="font-size:12px;">${pesan}</div></div>`;
    status.style.background = '#fef3c7';
    status.style.borderColor = '#fde68a';
  }
}

async function ambilFoto(tipe) {
  const video = document.getElementById('webcamPulang');
  const canvas = document.createElement('canvas');
  canvas.width = video.videoWidth;
  canvas.height = video.videoHeight;
  const ctx = canvas.getContext('2d');
  ctx.translate(canvas.width, 0);
  ctx.scale(-1, 1);
  ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
  const wajahTerdeteksi = await detectFaceFromCanvas(canvas);
  if (!wajahTerdeteksi) return;
  fotoDiambilPulang = true;
  document.getElementById('photoPreviewPulang').style.display = 'block';
  document.getElementById('capturedPhotoPulang').src = canvas.toDataURL('image/jpeg', 0.9);
  const status = document.getElementById('cameraStatusPulang');
  if (status) {
    status.textContent = 'Foto berhasil diambil';
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
      window.showFormalAlert('Waktu pengambilan foto telah habis (kadaluarsa dalam 30 detik). Silakan ambil foto ulang.', 'warning', 'Waktu Habis');
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
    window.showFormalAlert('Silakan ambil foto terlebih dahulu sebelum submit absensi.', 'warning', 'Foto Belum Ada');
    return;
  }

  // Validasi jarak dari kantor (maks 100m)
  if (lokasiPulang) {
    const distance = haversineDistance(lokasiPulang.lat, lokasiPulang.lng, KANTOR_LAT, KANTOR_LNG);
    if (distance > 100) {
      window.showFormalAlert(
        `Anda berada ${Math.round(distance)}m dari kantor. Radius maksimal 100m.\n\nSilakan menuju lokasi kantor untuk melakukan absensi.`,
        'error',
        'Di Luar Radius'
      );
      return;
    }
  }

  // Tidak boleh absen pulang sebelum absen masuk hari ini (verifikasi ke server)
  try {
    const cekResp = await fetch("{{ route('absensi.cek') }}?attendance_type=masuk", {
      headers: { 'Accept': 'application/json' }
    });
    const cek = await cekResp.json().catch(() => ({}));
    if (!cek.has_attended) {
      window.showFormalAlert('Anda belum melakukan absen masuk hari ini. Silakan absen masuk terlebih dahulu sebelum absen pulang.', 'warning', 'Belum Absen Masuk');
      return;
    }
  } catch (e) {
    console.error('Gagal memeriksa absen masuk:', e);
    window.showFormalAlert('Gagal memeriksa status absen masuk. Periksa koneksi Anda lalu coba lagi.', 'error', 'Kesalahan Koneksi');
    return;
  }

  try {
    const photoBase64 = document.getElementById('capturedPhotoPulang').src;
    const coordinates = lokasiPulang ? `${lokasiPulang.lat.toFixed(6)}, ${lokasiPulang.lng.toFixed(6)}` : 'Tidak terdeteksi';
    const address = document.getElementById('locationAddressPulang').textContent;
    const waktu = new Date().toLocaleTimeString('id-ID');
    const tanggal = new Date().toLocaleDateString('id-ID');
    const distance = lokasiPulang ? haversineDistance(lokasiPulang.lat, lokasiPulang.lng, KANTOR_LAT, KANTOR_LNG) : 0;

    let waktuMasuk = 'Tidak tercatat';
    let lamaKerja = '-';
    if (absensiMasukHariIni) {
      const masukTime = new Date(absensiMasukHariIni.created_at);
      waktuMasuk = masukTime.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
      lamaKerja = formatHHMMSS(Math.max(0, new Date() - masukTime));
    }

    const konfirmasi = await window.showFormalConfirm(
      `Tanggal: ${tanggal}\nWaktu: ${waktu}\nLokasi: ${coordinates}\nAlamat: ${address}\nWaktu Masuk: ${waktuMasuk}\nLama Bekerja: ${lamaKerja}\nJarak dari kantor: ±${Math.round(distance)}m`,
      'Konfirmasi Absensi Pulang',
      'Ya, Simpan Absensi',
      'Batal'
    );

    if (!konfirmasi) {
      return;
    }

    showLoading('Menyimpan absensi...');

    const submitBtn = document.getElementById('submitBtnPulang');
    submitBtn.innerHTML = 'Menyimpan...';
    submitBtn.disabled = true;

    await simpanKeDatabase(tipe, {
      photo: photoBase64 || '',
      latitude: (lokasiPulang && typeof lokasiPulang.lat === 'number') ? lokasiPulang.lat : null,
      longitude: (lokasiPulang && typeof lokasiPulang.lng === 'number') ? lokasiPulang.lng : null,
      address: address || 'Alamat tidak diketahui',
      timestamp: new Date().toISOString(),
      waktu_masuk_iso: absensiMasukHariIni?.created_at || null,
      waktu_masuk: waktuMasuk,
      lama_kerja: lamaKerja
    });

    if (timerPulang) clearInterval(timerPulang);

    hideLoading();
    window.showFormalAlert('Absensi pulang berhasil disimpan.', 'success', 'Berhasil');

    retakePhoto(tipe);
    updateUIAfterSubmit();
  } catch (error) {
    console.error('Error submit absensi pulang:', error);
    hideLoading();
    const errMsg = String(error?.message || 'Gagal menyimpan absensi. Silakan coba lagi.').replace(/[\u2700-\u27BF]|[\uE000-\uF8FF]|\uD83C[\uDC00-\uDFFF]|\uD83D[\uDC00-\uDFFF]|[\u2011-\u26FF]|\uD83E[\uDD10-\uDDFF]/g, '').trim();
    window.showFormalAlert(errMsg, 'error', 'Gagal');

    const submitBtn = document.getElementById('submitBtnPulang');
    submitBtn.innerHTML = 'Submit Absensi Pulang';
    submitBtn.disabled = false;
  }
}

async function simpanKeDatabase(tipe, data) {
  // Simpan ke database lewat endpoint absensi.simpan
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  const response = await fetch("{{ route('absensi.simpan') }}", {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': csrfToken,
    },
    body: JSON.stringify({
      attendance_type: tipe,
      photo: data.photo,
      latitude: data.latitude,
      longitude: data.longitude,
      address: data.address,
    }),
  });

  const result = await response.json().catch(() => ({}));

  if (!response.ok || !result.success) {
    throw new Error(result.message || 'Gagal menyimpan absensi ke server.');
  }

  // Data absensi pulang tersimpan
  sessionStorage.setItem('last_attendance_pulang', JSON.stringify({ type: tipe, ...result.data, ...data }));

  return result.data;
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
  window.showFormalAlert('Absensi pulang berhasil! Selamat beristirahat.', 'success', 'Absensi Berhasil');
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
