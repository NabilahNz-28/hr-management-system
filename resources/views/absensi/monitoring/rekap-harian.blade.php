<div class="page-content" id="rekap-harian">
    <div class="content-title">Rekap Absensi Harian</div>
    <p class="content-description">Data absensi seluruh karyawan hari ini</p>
    
    <div style="display: flex; gap: 16px; margin-bottom: 20px;">
        <input type="date" id="tanggal" class="form-control" style="width: 200px;" value="2025-12-24">
        <select id="departemen" class="form-control" style="width: 200px;">
            <option value="all">Semua Departemen</option>
            <option value="it">IT</option>
            <option value="hrd">HRD</option>
            <option value="finance">Finance</option>
            <option value="marketing">Marketing</option>
        </select>
        <button class="btn btn-primary" onclick="filterData()">Filter</button>
        <button id="btnExportExcel" class="btn btn-success">Export Excel</button>
    </div>
    
    <div id="resultContainer">
        <!-- Tabel hasil filter akan muncul di sini -->
    </div>
</div>

<script>
    // Fungsi export ke Excel (mengambil data langsung dari tabel)
function exportToExcel() {
    const tanggal = document.querySelector('#rekap-harian input[type="date"]').value;
    const departemen = document.querySelector('#rekap-harian select').value;
    
    // Ambil tabel
    const table = document.querySelector('#rekap-harian .data-table');
    
    // Buat salinan tabel untuk dimodifikasi
    const tableClone = table.cloneNode(true);
    
    // Hapus kolom "Foto" dari tabel clone (kolom terakhir)
    const rows = tableClone.querySelectorAll('tr');
    rows.forEach(row => {
        const lastCell = row.lastElementChild;
        if (lastCell && (lastCell.textContent.includes('Lihat') || lastCell.textContent === '-')) {
            row.removeChild(lastCell);
        }
    });
    
    // Hapus header "Foto"
    const headerRow = tableClone.querySelector('thead tr');
    if (headerRow.lastElementChild) {
        headerRow.removeChild(headerRow.lastElementChild);
    }
    
    // Siapkan konten Excel
    let excelContent = '<table>';
    
    // Tambahkan judul
    excelContent += '<tr><td colspan="5" style="font-size: 14px; font-weight: bold; text-align: center; background-color: #4CAF50; color: white; padding: 10px;">REKAP ABSENSI HARIAN</td></tr>';
    excelContent += `<tr><td colspan="5" style="text-align: center; padding: 5px;">Tanggal: ${tanggal} | Departemen: ${departemen === 'all' ? 'Semua Departemen' : departemen.toUpperCase()}</td></tr>`;
    excelContent += '<tr><td colspan="5" style="padding: 5px;"></td></tr>';
    
    // Style untuk header
    excelContent += '<tr>';
    const headers = tableClone.querySelectorAll('thead th');
    headers.forEach(header => {
        excelContent += `<th style="background-color: #2196F3; color: white; padding: 10px; border: 1px solid #ddd; font-weight: bold;">${header.textContent}</th>`;
    });
    excelContent += '</tr>';
    
    // Style untuk data
    const dataRows = tableClone.querySelectorAll('tbody tr');
    dataRows.forEach((row, index) => {
        excelContent += '<tr>';
        const cells = row.querySelectorAll('td');
        cells.forEach(cell => {
            // Ambil text content dari cell (termasuk dari span status badge)
            let cellContent = cell.textContent.trim();
            
            // Style khusus untuk kolom status
            if (cell.querySelector('.status-badge')) {
                const badge = cell.querySelector('.status-badge');
                let bgColor = '#4CAF50'; // default hijau
                if (badge.classList.contains('status-late')) bgColor = '#FF9800';
                if (badge.classList.contains('status-absent')) bgColor = '#f44336';
                
                excelContent += `<td style="text-align: center; padding: 8px; border: 1px solid #ddd; background-color: ${bgColor}; color: white; font-weight: bold;">${cellContent}</td>`;
            } else {
                excelContent += `<td style="text-align: center; padding: 8px; border: 1px solid #ddd;">${cellContent}</td>`;
            }
        });
        excelContent += '</tr>';
    });
    
    excelContent += '</table>';
    
    // Buat blob dan download
    const blob = new Blob([excelContent], { 
        type: 'application/vnd.ms-excel;charset=utf-8' 
    });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `Rekap_Absensi_${tanggal.replace(/-/g, '')}.xls`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

// Fungsi filter sederhana (bisa dikembangkan nanti)
function filterTable() {
    const departemen = document.querySelector('#rekap-harian select').value;
    const rows = document.querySelectorAll('#rekap-harian tbody tr');
    
    rows.forEach(row => {
        const deptCell = row.cells[1]; // Kolom Departemen (index 1)
        if (departemen === 'all' || deptCell.textContent.toLowerCase() === departemen.toLowerCase()) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Event listener
document.addEventListener('DOMContentLoaded', function() {
    // Tombol Export Excel
    const btnExport = document.querySelector('#rekap-harian .btn-success');
    if (btnExport) {
        btnExport.addEventListener('click', exportToExcel);
    }
    
    // Tombol Filter
    const btnFilter = document.querySelector('#rekap-harian .btn-primary');
    if (btnFilter) {
        btnFilter.addEventListener('click', filterTable);
    }
});

// Fungsi showPhoto (placeholder)
function showPhoto(nama) {
    alert(`Menampilkan foto ${nama}`);
    // Nanti bisa diganti dengan modal atau lightbox
}
    </script>