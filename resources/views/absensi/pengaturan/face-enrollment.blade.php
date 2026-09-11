@extends('layouts.absen')

@section('title', 'Daftarkan Wajah Karyawan')

@section('styles')
<style>
    :root {
        --primary: #6366f1;
        --primary-dark: #4f46e5;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-600: #4b5563;
        --gray-700: #374151;
        --gray-900: #111827;
    }

    .page-header {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        border-radius: 16px;
        padding: 28px 32px;
        color: white;
        margin-bottom: 28px;
        box-shadow: 0 8px 32px rgba(99,102,241,0.25);
    }

    .page-header h1 { font-size: 22px; font-weight: 700; margin: 0 0 4px; }
    .page-header p  { font-size: 14px; opacity: 0.85; margin: 0; }

    .stats-bar {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px 24px;
        border: 1px solid var(--gray-200);
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .stat-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .stat-icon.purple { background: #ede9fe; }
    .stat-icon.green  { background: #d1fae5; }
    .stat-icon.red    { background: #fee2e2; }

    .stat-val  { font-size: 28px; font-weight: 800; color: var(--gray-900); line-height: 1; }
    .stat-lbl  { font-size: 13px; color: var(--gray-600); margin-top: 2px; }

    .search-bar {
        background: white;
        border-radius: 12px;
        padding: 16px 20px;
        border: 1px solid var(--gray-200);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    }

    .search-bar input {
        border: none;
        outline: none;
        font-size: 14px;
        flex: 1;
        background: transparent;
        color: var(--gray-700);
    }

    .karyawan-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 16px;
    }

    .karyawan-card {
        background: white;
        border-radius: 14px;
        border: 1.5px solid var(--gray-200);
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        transition: all 0.2s ease;
    }

    .karyawan-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        border-color: var(--primary);
    }

    .karyawan-card.enrolled { border-color: #a7f3d0; }
    .karyawan-card.enrolled:hover { border-color: var(--success); }

    .card-body {
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .avatar {
        width: 52px; height: 52px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--gray-200);
        flex-shrink: 0;
        background: var(--gray-100);
    }

    .avatar-placeholder {
        width: 52px; height: 52px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; font-weight: 700; color: var(--primary);
        flex-shrink: 0;
    }

    .karyawan-info { flex: 1; min-width: 0; }
    .karyawan-name { font-size: 15px; font-weight: 700; color: var(--gray-900); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .karyawan-jabatan { font-size: 12px; color: var(--gray-600); margin-top: 2px; }

    .badge-enrolled {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 20px;
        font-size: 11px; font-weight: 600;
        background: #d1fae5; color: #065f46;
        margin-top: 6px;
    }

    .badge-not-enrolled {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 20px;
        font-size: 11px; font-weight: 600;
        background: #fee2e2; color: #991b1b;
        margin-top: 6px;
    }

    .card-footer {
        padding: 12px 20px;
        background: var(--gray-50);
        border-top: 1px solid var(--gray-200);
        display: flex;
        gap: 8px;
    }

    .btn-enroll {
        flex: 1;
        padding: 8px 0;
        border-radius: 8px;
        border: none;
        background: var(--primary);
        color: white;
        font-size: 13px; font-weight: 600;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 6px;
        transition: all 0.2s;
    }

    .btn-enroll:hover { background: var(--primary-dark); transform: scale(1.02); }

    .btn-re-enroll {
        flex: 1;
        padding: 8px 0;
        border-radius: 8px;
        border: 1.5px solid var(--primary);
        background: white;
        color: var(--primary);
        font-size: 13px; font-weight: 600;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 6px;
        transition: all 0.2s;
    }

    .btn-re-enroll:hover { background: #ede9fe; }

    .btn-delete {
        padding: 8px 14px;
        border-radius: 8px;
        border: 1.5px solid #fecaca;
        background: white;
        color: var(--danger);
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-delete:hover { background: #fee2e2; }

    /* MODAL */
    .modal-overlay {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.55);
        z-index: 9000;
        align-items: center; justify-content: center;
        backdrop-filter: blur(3px);
    }

    .modal-overlay.active { display: flex; }

    .modal-box {
        background: white;
        border-radius: 20px;
        padding: 28px 32px;
        width: 480px;
        max-width: 95vw;
        box-shadow: 0 24px 64px rgba(0,0,0,0.2);
        animation: modalIn 0.25s ease;
    }

    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.92) translateY(16px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }

    .modal-title { font-size: 18px; font-weight: 700; color: var(--gray-900); margin-bottom: 4px; }
    .modal-subtitle { font-size: 13px; color: var(--gray-600); margin-bottom: 20px; }

    .cam-wrap {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        background: #0f0f0f;
        aspect-ratio: 4/3;
        margin-bottom: 16px;
    }

    #enrollVideo {
        width: 100%; height: 100%;
        object-fit: cover;
        transform: scaleX(-1);
        display: block;
    }

    .cam-overlay {
        position: absolute; inset: 0;
        display: flex; align-items: center; justify-content: center;
        pointer-events: none;
    }

    .face-guide {
        width: 180px; height: 220px;
        border: 2.5px solid rgba(255,255,255,0.7);
        border-radius: 50%;
        box-shadow: 0 0 0 2000px rgba(0,0,0,0.35);
    }

    #enrollStatus {
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 16px;
        background: #f3f4f6;
        color: var(--gray-700);
        border: 1px solid var(--gray-200);
        text-align: center;
        min-height: 42px;
        display: flex; align-items: center; justify-content: center;
    }

    .modal-actions { display: flex; gap: 10px; }

    .btn-capture {
        flex: 1; padding: 11px;
        border-radius: 10px; border: none;
        background: var(--primary); color: white;
        font-size: 14px; font-weight: 700;
        cursor: pointer; transition: all 0.2s;
    }

    .btn-capture:hover:not(:disabled) { background: var(--primary-dark); }
    .btn-capture:disabled { opacity: 0.5; cursor: not-allowed; }

    .btn-cancel-modal {
        padding: 11px 20px;
        border-radius: 10px;
        border: 1.5px solid var(--gray-200);
        background: white; color: var(--gray-700);
        font-size: 14px; font-weight: 600;
        cursor: pointer; transition: all 0.2s;
    }

    .btn-cancel-modal:hover { background: var(--gray-100); }

    .progress-steps {
        display: flex; align-items: center; gap: 6px;
        margin-bottom: 16px;
    }

    .step-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: var(--gray-200);
        transition: background 0.3s;
    }

    .step-dot.active { background: var(--primary); }
    .step-dot.done   { background: var(--success); }

    .step-label { font-size: 12px; color: var(--gray-600); margin-left: 8px; }

    /* Loading spinner dalam modal */
    .spin { animation: spin 1s linear infinite; display: inline-block; }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>
@endsection

@section('content')
<div class="container" style="padding: 24px;">
    <!-- Header -->
    <div class="page-header">
        <h1>👤 Daftarkan Wajah Karyawan</h1>
        <p>Kelola data pengenalan wajah untuk sistem absensi biometrik</p>
    </div>

    <!-- Stats -->
    @php
        $total    = $karyawan->count();
        $enrolled = $karyawan->whereNotNull('face_descriptor')->count();
        $belum    = $total - $enrolled;
    @endphp
    <div class="stats-bar">
        <div class="stat-card">
            <div class="stat-icon purple">👥</div>
            <div>
                <div class="stat-val">{{ $total }}</div>
                <div class="stat-lbl">Total Karyawan</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">✅</div>
            <div>
                <div class="stat-val">{{ $enrolled }}</div>
                <div class="stat-lbl">Wajah Terdaftar</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red">⚠️</div>
            <div>
                <div class="stat-val">{{ $belum }}</div>
                <div class="stat-lbl">Belum Terdaftar</div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="search-bar">
        <span style="font-size: 18px;">🔍</span>
        <input type="text" id="searchInput" placeholder="Cari nama karyawan..." oninput="filterKaryawan(this.value)">
    </div>

    <!-- Grid Karyawan -->
    <div class="karyawan-grid" id="karyawanGrid">
        @foreach($karyawan as $k)
        @php $isEnrolled = !is_null($k->face_descriptor); @endphp
        <div class="karyawan-card {{ $isEnrolled ? 'enrolled' : '' }}" data-name="{{ strtolower($k->name) }}" id="card-{{ $k->id }}">
            <div class="card-body">
                <!-- Avatar -->
                @if($k->foto_profile && file_exists(public_path('profile_photos/' . $k->foto_profile)))
                    <img src="{{ asset('profile_photos/' . $k->foto_profile) }}" alt="{{ $k->name }}" class="avatar">
                @else
                    <div class="avatar-placeholder">{{ strtoupper(substr($k->name, 0, 1)) }}</div>
                @endif

                <!-- Info -->
                <div class="karyawan-info">
                    <div class="karyawan-name">{{ $k->name }}</div>
                    <div class="karyawan-jabatan">{{ $k->jabatan ?: 'Karyawan' }} • {{ $k->nik ?: '-' }}</div>
                    @if($isEnrolled)
                        <span class="badge-enrolled">✅ Wajah Terdaftar</span>
                    @else
                        <span class="badge-not-enrolled">❌ Belum Terdaftar</span>
                    @endif
                </div>
            </div>
            <div class="card-footer">
                @if($isEnrolled)
                    <button class="btn-re-enroll" onclick="openEnroll({{ $k->id }}, '{{ $k->name }}')">
                        🔄 Perbarui Wajah
                    </button>
                    <button class="btn-delete" onclick="deleteEnroll({{ $k->id }}, '{{ $k->name }}')" title="Hapus data wajah">
                        🗑️
                    </button>
                @else
                    <button class="btn-enroll" onclick="openEnroll({{ $k->id }}, '{{ $k->name }}')">
                        📷 Daftarkan Wajah
                    </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    @if($karyawan->isEmpty())
    <div style="text-align: center; padding: 60px 20px; color: #9ca3af;">
        <div style="font-size: 48px;">👥</div>
        <div style="font-size: 16px; font-weight: 600; margin-top: 12px;">Belum ada karyawan</div>
        <div style="font-size: 14px; margin-top: 4px;">Tambah karyawan terlebih dahulu di menu Data Karyawan</div>
    </div>
    @endif
</div>

<!-- Modal Enrollment -->
<div class="modal-overlay" id="enrollModal">
    <div class="modal-box">
        <div class="modal-title" id="modalTitle">Daftarkan Wajah</div>
        <div class="modal-subtitle" id="modalSubtitle">Pastikan wajah karyawan terlihat jelas dan pencahayaan cukup</div>

        <!-- Progress steps -->
        <div class="progress-steps">
            <div class="step-dot active" id="step1"></div>
            <div style="flex: 1; height: 2px; background: #e5e7eb;" id="line1"></div>
            <div class="step-dot" id="step2"></div>
            <div style="flex: 1; height: 2px; background: #e5e7eb;" id="line2"></div>
            <div class="step-dot" id="step3"></div>
            <span class="step-label" id="stepLabel">Siapkan kamera</span>
        </div>

        <!-- Kamera -->
        <div class="cam-wrap">
            <video id="enrollVideo" autoplay playsinline muted></video>
            <div class="cam-overlay">
                <div class="face-guide"></div>
            </div>
        </div>

        <!-- Status -->
        <div id="enrollStatus">Memuat model pengenalan wajah...</div>

        <!-- Tombol -->
        <div class="modal-actions">
            <button class="btn-capture" id="captureEnrollBtn" onclick="captureAndEnroll()" disabled>
                📷 Ambil & Daftarkan
            </button>
            <button class="btn-cancel-modal" onclick="closeModal()">Batal</button>
        </div>
    </div>
</div>

<!-- Konfirmasi hapus -->
<div class="modal-overlay" id="confirmModal">
    <div class="modal-box" style="width: 380px;">
        <div class="modal-title">🗑️ Hapus Data Wajah?</div>
        <div class="modal-subtitle" id="confirmText">Data wajah karyawan akan dihapus.</div>
        <div class="modal-actions" style="margin-top: 20px;">
            <button class="btn-capture" style="background: #ef4444;" id="confirmDeleteBtn">Ya, Hapus</button>
            <button class="btn-cancel-modal" onclick="closeConfirm()">Batal</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- face-api.js dari public/models/ (semua aset lokal, tidak perlu CDN) -->
<script src="{{ asset('js/face-api.min.js') }}"></script>

<script>
const MODEL_URL  = "{{ asset('models') }}";
const STORE_URL  = (id) => `{{ url('absensi/face-enrollment') }}/${id}`;
const DELETE_URL = (id) => `{{ url('absensi/face-enrollment') }}/${id}`;
const CSRF       = document.querySelector('meta[name="csrf-token"]')?.content;

let modelsLoaded = false;
let enrollStream = null;
let currentUserId = null;

// ===================== SEARCH =====================
function filterKaryawan(q) {
    const lq = q.toLowerCase();
    document.querySelectorAll('.karyawan-card').forEach(card => {
        card.style.display = card.dataset.name.includes(lq) ? '' : 'none';
    });
}

// ===================== MODAL ENROLLMENT =====================
async function openEnroll(userId, userName) {
    currentUserId = userId;
    document.getElementById('modalTitle').textContent = `Daftarkan Wajah: ${userName}`;
    document.getElementById('enrollModal').classList.add('active');
    setStep(1, 'Memuat model AI...');
    setEnrollStatus('⏳ Memuat model pengenalan wajah...', null);
    document.getElementById('captureEnrollBtn').disabled = true;

    // Muat model jika belum
    if (!modelsLoaded) {
        try {
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL),
            ]);
            modelsLoaded = true;
        } catch (e) {
            setEnrollStatus('❌ Gagal memuat model AI. Cek koneksi internet.', false);
            return;
        }
    }

    // Aktifkan kamera
    setStep(2, 'Aktifkan kamera');
    try {
        enrollStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user', width: 640 } });
        document.getElementById('enrollVideo').srcObject = enrollStream;
        await new Promise(r => document.getElementById('enrollVideo').addEventListener('loadedmetadata', r, { once: true }));
        setStep(3, 'Siap ambil foto');
        setEnrollStatus('✅ Kamera siap. Posisikan wajah di dalam lingkaran, lalu klik "Ambil & Daftarkan".', true);
        document.getElementById('captureEnrollBtn').disabled = false;
    } catch (e) {
        setEnrollStatus('❌ Tidak bisa mengakses kamera. Berikan izin kamera.', false);
    }
}

function closeModal() {
    document.getElementById('enrollModal').classList.remove('active');
    stopStream();
    currentUserId = null;
}

function stopStream() {
    if (enrollStream) {
        enrollStream.getTracks().forEach(t => t.stop());
        enrollStream = null;
    }
}

// ===================== CAPTURE & ENROLL =====================
async function captureAndEnroll() {
    const btn = document.getElementById('captureEnrollBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spin">⏳</span> Memproses...';
    setEnrollStatus('🔍 Mendeteksi wajah...', null);

    const video = document.getElementById('enrollVideo');

    // Deteksi wajah + landmark + descriptor
    const detection = await faceapi
        .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({ scoreThreshold: 0.5 }))
        .withFaceLandmarks()
        .withFaceDescriptor();

    if (!detection) {
        setEnrollStatus('❌ Wajah tidak terdeteksi. Pastikan wajah terlihat jelas dan pencahayaan cukup.', false);
        btn.disabled = false;
        btn.innerHTML = '📷 Coba Lagi';
        return;
    }

    // Descriptor: Float32Array → array biasa
    const descriptor = Array.from(detection.descriptor);

    setEnrollStatus('⬆️ Menyimpan data wajah ke server...', null);

    // Kirim ke server
    try {
        const res = await fetch(STORE_URL(currentUserId), {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ descriptor }),
        });

        const data = await res.json();

        if (data.success) {
            setEnrollStatus('✅ ' + data.message, true);
            // Update UI card tanpa reload
            updateCardUI(currentUserId, true);
            setTimeout(closeModal, 1800);
        } else {
            setEnrollStatus('❌ Gagal: ' + data.message, false);
            btn.disabled = false;
            btn.innerHTML = '📷 Coba Lagi';
        }
    } catch (e) {
        setEnrollStatus('❌ Error jaringan. Coba lagi.', false);
        btn.disabled = false;
        btn.innerHTML = '📷 Coba Lagi';
    }
}

// ===================== DELETE =====================
let deleteTargetId = null;

function deleteEnroll(userId, userName) {
    deleteTargetId = userId;
    document.getElementById('confirmText').textContent = `Data wajah "${userName}" akan dihapus permanen. Karyawan tidak bisa absen sampai didaftarkan ulang.`;
    document.getElementById('confirmModal').classList.add('active');
    document.getElementById('confirmDeleteBtn').onclick = doDelete;
}

async function doDelete() {
    const btn = document.getElementById('confirmDeleteBtn');
    btn.disabled = true;
    btn.textContent = 'Menghapus...';

    try {
        const res = await fetch(DELETE_URL(deleteTargetId), {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ _method: 'DELETE' }),
        });

        const data = await res.json();
        if (data.success) {
            updateCardUI(deleteTargetId, false);
            closeConfirm();
        } else {
            alert('Gagal: ' + data.message);
            btn.disabled = false;
            btn.textContent = 'Ya, Hapus';
        }
    } catch (e) {
        alert('Error jaringan.');
        btn.disabled = false;
        btn.textContent = 'Ya, Hapus';
    }
}

function closeConfirm() {
    document.getElementById('confirmModal').classList.remove('active');
    deleteTargetId = null;
    const btn = document.getElementById('confirmDeleteBtn');
    btn.disabled = false;
    btn.textContent = 'Ya, Hapus';
}

// ===================== UI UPDATE =====================
function updateCardUI(userId, isEnrolled) {
    const card = document.getElementById(`card-${userId}`);
    if (!card) return;

    // Update badge
    const badge = card.querySelector('.badge-enrolled, .badge-not-enrolled');
    if (badge) {
        badge.className = isEnrolled ? 'badge-enrolled' : 'badge-not-enrolled';
        badge.textContent = isEnrolled ? '✅ Wajah Terdaftar' : '❌ Belum Terdaftar';
    }

    // Update card border
    card.classList.toggle('enrolled', isEnrolled);

    // Update tombol
    const footer = card.querySelector('.card-footer');
    const name = card.querySelector('.karyawan-name')?.textContent ?? '';
    // Ambil userId dari onclick card
    if (isEnrolled) {
        footer.innerHTML = `
            <button class="btn-re-enroll" onclick="openEnroll(${userId}, '${name}')">🔄 Perbarui Wajah</button>
            <button class="btn-delete" onclick="deleteEnroll(${userId}, '${name}')" title="Hapus data wajah">🗑️</button>
        `;
    } else {
        footer.innerHTML = `
            <button class="btn-enroll" onclick="openEnroll(${userId}, '${name}')">📷 Daftarkan Wajah</button>
        `;
    }

    // Update stats
    updateStats();
}

function updateStats() {
    const enrolled = document.querySelectorAll('.badge-enrolled').length;
    const total    = document.querySelectorAll('.karyawan-card').length;
    const belum    = total - enrolled;

    const vals = document.querySelectorAll('.stat-val');
    if (vals[0]) vals[0].textContent = total;
    if (vals[1]) vals[1].textContent = enrolled;
    if (vals[2]) vals[2].textContent = belum;
}

// ===================== HELPERS =====================
function setEnrollStatus(text, ok) {
    const el = document.getElementById('enrollStatus');
    el.textContent = text;
    if (ok === true)       { el.style.background = '#d1fae5'; el.style.color = '#065f46'; el.style.borderColor = '#a7f3d0'; }
    else if (ok === false) { el.style.background = '#fee2e2'; el.style.color = '#991b1b'; el.style.borderColor = '#fecaca'; }
    else                   { el.style.background = '#f3f4f6'; el.style.color = '#374151'; el.style.borderColor = '#e5e7eb'; }
}

function setStep(n, label) {
    [1,2,3].forEach(i => {
        const dot = document.getElementById(`step${i}`);
        dot.className = 'step-dot' + (i < n ? ' done' : i === n ? ' active' : '');
    });
    document.getElementById('stepLabel').textContent = label;
}

// Tutup modal kalau klik overlay
document.getElementById('enrollModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
document.getElementById('confirmModal').addEventListener('click', function(e) {
    if (e.target === this) closeConfirm();
});
</script>
@endsection
