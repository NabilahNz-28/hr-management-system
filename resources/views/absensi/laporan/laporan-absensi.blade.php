<!-- Profile Karyawan -->
<div class="page-content" id="profile-pribadi">
    <div class="content-title">Profile Karyawan</div>
    <p class="content-description">Informasi pribadi dan rekap absensi</p>
    
    <div>
        <div>
            <p>Nama: Ahmad Wijaya</p>
            <p>NIK: 001</p>
            <p>Departemen: IT</p>
            <p>Jabatan: Staff IT</p>
            <p>Email: ahmad.wijaya@company.com</p>
            <p>No. HP: 081234567890</p>
        </div>
        <br>
        <div>
            <div class="content-title">Rekap Absensi Bulan Ini</div>
            <select id="bulan-rekap">
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
            
            <select id="tahun-rekap">
                <option value="2024">2024</option>
                <option value="2025">2025</option>
                <option value="2026">2026</option>
            </select>
            
            <button onclick="lihatRekapProfile()">Lihat</button>
        </div>
        
        <br>
        
        <table id="rekap-container">
    <tr>
        <td>
            <p>Hadir</p>
            <p id="jml-hadir">22</p>
            <p>hari</p>
        </td>
        <td>
            <p>Libur</p>
            <p id="jml-libur">6</p>
            <p>hari</p>
        </td>
        <td>
            <p>Cuti/Izin</p>
            <p id="jml-cuti">2</p>
            <p>hari</p>
        </td>
    </tr>
</table>

<br>

<div>
    <p>Total Hari Kerja: <span id="total-hari">30</span> hari</p>
</div>