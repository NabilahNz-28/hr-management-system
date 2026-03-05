<!-- Rekap Harian -->
                <div class="page-content" id="rekap-harian">
                    <div class="content-title">Rekap Absensi Harian</div>
                    <p class="content-description">Data absensi seluruh karyawan hari ini</p>
                    
                    <div style="display: flex; gap: 16px; margin-bottom: 20px;">
                        <input type="date" class="form-control" style="width: 200px;" value="2025-12-24">
                        <select class="form-control" style="width: 200px;">
                            <option value="all">Semua Departemen</option>
                            <option value="it">IT</option>
                            <option value="hrd">HRD</option>
                            <option value="finance">Finance</option>
                            <option value="marketing">Marketing</option>
                        </select>
                        <button class="btn btn-primary">Filter</button>
                        <button class="btn btn-success">Export Excel</button>
                    </div>
                    
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Departemen</th>
                                <th>Masuk</th>
                                <th>Pulang</th>
                                <th>Status</th>
                                <th>Lokasi Masuk</th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Ahmad Wijaya</td>
                                <td>IT</td>
                                <td>07:45</td>
                                <td>17:15</td>
                                <td><span class="status-badge status-present">Tepat Waktu</span></td>
                                <td>Kantor Pusat</td>
                                <td><button class="btn btn-primary btn-sm" onclick="showPhoto('ahmad')">Lihat</button></td>
                            </tr>
                            <tr>
                                <td>Siti Rahma</td>
                                <td>HRD</td>
                                <td>08:05</td>
                                <td>-</td>
                                <td><span class="status-badge status-present">Tepat Waktu</span></td>
                                <td>Kantor Pusat</td>
                                <td><button class="btn btn-primary btn-sm" onclick="showPhoto('siti')">Lihat</button></td>
                            </tr>
                            <tr>
                                <td>Budi Santoso</td>
                                <td>Finance</td>
                                <td>08:31</td>
                                <td>-</td>
                                <td><span class="status-badge status-late">Terlambat</span></td>
                                <td>Kantor Cabang</td>
                                <td><button class="btn btn-primary btn-sm" onclick="showPhoto('budi')">Lihat</button></td>
                            </tr>
                            <tr>
                                <td>Dewi Anggraini</td>
                                <td>Marketing</td>
                                <td>07:50</td>
                                <td>17:05</td>
                                <td><span class="status-badge status-present">Tepat Waktu</span></td>
                                <td>Kantor Pusat</td>
                                <td><button class="btn btn-primary btn-sm" onclick="showPhoto('dewi')">Lihat</button></td>
                            </tr>
                            <tr>
                                <td>Rudi Hartono</td>
                                <td>IT</td>
                                <td>-</td>
                                <td>-</td>
                                <td><span class="status-badge status-absent">Tidak Hadir</span></td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>