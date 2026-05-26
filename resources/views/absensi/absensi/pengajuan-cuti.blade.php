@extends('layouts.absen')

@section('title', 'Pengajuan Cuti')

@section('content')
<!-- Pengajuan Cuti -->
                <div class="page-content active" id="absensi-cuti">
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
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->type_code }}">{{ $type->name }}</option>
                                @endforeach
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
@endsection
