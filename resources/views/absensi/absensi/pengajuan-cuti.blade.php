<!-- Pengajuan Cuti -->
                <div class="page-content" id="absensi-cuti">
                    <div class="content-title">Pengajuan Cuti</div>
                    <p class="content-description">Ajukan cuti tahunan, melahirkan, atau khusus</p>
                    
                    <form id="formCuti">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div class="form-group">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Jenis Cuti</label>
                            <select class="form-control" required>
                                <option value="">Pilih jenis cuti</option>
                                <option value="tahunan">Cuti Tahunan</option>
                                <option value="melahirkan">Cuti Melahirkan</option>
                                <option value="besar">Cuti Besar</option>
                                <option value="sakit">Cuti Sakit</option>
                                <option value="penting">Cuti Alasan Penting</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Alasan Cuti</label>
                            <textarea class="form-control" rows="4" placeholder="Jelaskan alasan cuti secara detail" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Upload Dokumen Pendukung</label>
                            <input type="file" class="form-control" accept="image/*,.pdf" multiple>
                            <div style="font-size: 12px; color: #64748b; margin-top: 4px;">Surat dokter, surat keterangan, atau dokumen lainnya</div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Ajukan Cuti</button>
                    </form>
                </div>
                