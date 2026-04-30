<!-- Keterlambatan Bulanan -->
<div class="page-content" id="keterlambatan-bulanan">
    <div class="content-title">Riwayat Keterlambatan</div>
    <p class="content-description">Catatan keterlambatan per bulan</p>
    
    <div>
        <select id="bulan-telat">
            <option value="01">Januari</option>
            <option value="02">Februari</option>
            <option value="03">Maret</option>
            <option value="04">April</option>
            <option value="05">Mei</option>
            <option value="06">Juni</option>
            <option value="07">Juli</option>
            <option value="08">Agustus</option>
            <option value="09">September</option>
            <option value="10">Oktober</option>
            <option value="11">November</option>
            <option value="12">Desember</option>
        </select>
        
        <select id="tahun-telat">
            <option value="2024">2024</option>
            <option value="2025">2025</option>
            <option value="2026">2026</option>
        </select>
        
        <button onclick="lihatKeterlambatan()">Lihat</button>
    </div>
    
    <br>
    
    <div id="info-telat">
        <p>Periode: <span id="label-bulan-telat">Desember</span> <span id="label-tahun-telat">2025</span></p>
        <p>Total Keterlambatan: <span id="total-telat">0</span> kali</p>
    </div>
    
    <br>
    
    <div id="container-telat">
        <!-- Tabel keterlambatan atau pesan sanjungan -->
    </div>
</div>

<script>
    // Data dummy keterlambatan per bulan
const dataKeterlambatan = {
    '01': [
        { tanggal: '2025-01-05', jam_masuk: '08:15', keterlambatan: '15 menit', alasan: 'Macet' },
        { tanggal: '2025-01-12', jam_masuk: '08:30', keterlambatan: '30 menit', alasan: 'Bangun kesiangan' },
        { tanggal: '2025-01-20', jam_masuk: '08:45', keterlambatan: '45 menit', alasan: 'Hujan deras' }
    ],
    '02': [
        { tanggal: '2025-02-03', jam_masuk: '08:10', keterlambatan: '10 menit', alasan: 'Motor mogok' },
        { tanggal: '2025-02-18', jam_masuk: '08:25', keterlambatan: '25 menit', alasan: 'Macet parah' }
    ],
    '03': [],
    '04': [
        { tanggal: '2025-04-07', jam_masuk: '08:20', keterlambatan: '20 menit', alasan: 'Telat bangun' }
    ],
    '05': [],
    '06': [
        { tanggal: '2025-06-15', jam_masuk: '08:35', keterlambatan: '35 menit', alasan: 'Ada acara keluarga' },
        { tanggal: '2025-06-22', jam_masuk: '09:00', keterlambatan: '60 menit', alasan: 'Sakit perut' }
    ],
    '07': [],
    '08': [
        { tanggal: '2025-08-10', jam_masuk: '08:15', keterlambatan: '15 menit', alasan: 'Macet' }
    ],
    '09': [],
    '10': [
        { tanggal: '2025-10-05', jam_masuk: '08:40', keterlambatan: '40 menit', alasan: 'Kecelakaan di jalan' },
        { tanggal: '2025-10-14', jam_masuk: '08:20', keterlambatan: '20 menit', alasan: 'Hujan' },
        { tanggal: '2025-10-28', jam_masuk: '08:10', keterlambatan: '10 menit', alasan: 'Macet' }
    ],
    '11': [],
    '12': []
};

// Fungsi lihat keterlambatan
function lihatKeterlambatan() {
    const bulan = document.getElementById('bulan-telat').value;
    const tahun = document.getElementById('tahun-telat').value;
    const namaBulan = document.getElementById('bulan-telat').selectedOptions[0].text;
    
    // Update label periode
    document.getElementById('label-bulan-telat').textContent = namaBulan;
    document.getElementById('label-tahun-telat').textContent = tahun;
    
    // Ambil data keterlambatan
    const dataTelat = dataKeterlambatan[bulan] || [];
    const totalTelat = dataTelat.length;
    
    // Update total keterlambatan
    document.getElementById('total-telat').textContent = totalTelat;
    
    // Container untuk tabel atau pesan
    const container = document.getElementById('container-telat');
    
    if (totalTelat === 0) {
        // Tampilkan pesan sanjungan
        container.innerHTML = `
            <div>
                <p>✅ Luar Biasa!</p>
                <p>Anda tidak memiliki keterlambatan sama sekali di bulan ${namaBulan} ${tahun}.</p>
                <p>Pertahankan kedisiplinan Anda! 🎉</p>
                <p>⭐⭐⭐⭐⭐</p>
            </div>
        `;
    } else {
        // Tampilkan tabel keterlambatan
        let tabelHTML = `
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Keterlambatan</th>
                        <th>Alasan</th>
                    </tr>
                </thead>
                <tbody>
        `;
        
        dataTelat.forEach((item, index) => {
            tabelHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.tanggal}</td>
                    <td>${item.jam_masuk}</td>
                    <td>${item.keterlambatan}</td>
                    <td>${item.alasan}</td>
                </tr>
            `;
        });
        
        tabelHTML += `
                </tbody>
            </table>
        `;
        
        container.innerHTML = tabelHTML;
    }
}

// Set default bulan & tahun saat ini
document.addEventListener('DOMContentLoaded', function() {
    const sekarang = new Date();
    document.getElementById('bulan-telat').value = String(sekarang.getMonth() + 1).padStart(2, '0');
    document.getElementById('tahun-telat').value = sekarang.getFullYear();
    lihatKeterlambatan();
});
    </script>