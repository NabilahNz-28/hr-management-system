@extends('layouts.absen')

@section('title', 'Pengajuan Cuti')

@section('content')
<!-- Pengajuan Cuti -->
                <div class="page-content active" id="absensi-cuti">
                    <div class="content-title">Pengajuan Cuti</div>
                    <p class="content-description">Ajukan cuti tahunan, melahirkan, atau khusus</p>

                    @if(session('success'))
                      <div class="success-message" style="background:#dcfce7;border:1px solid #86efac;color:#166534;padding:12px 16px;border-radius:8px;margin-bottom:16px;">
                        {{ session('success') }}
                      </div>
                    @endif
                    @if(session('error'))
                      <div class="success-message" style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;padding:12px 16px;border-radius:8px;margin-bottom:16px;">
                        {{ session('error') }}
                      </div>
                    @endif
                    @if($errors->any())
                      <div class="success-message" style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;padding:12px 16px;border-radius:8px;margin-bottom:16px;">
                        <ul style="margin:0;padding-left:18px;">
                          @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                        </ul>
                      </div>
                    @endif

                    <form id="formCuti" method="POST" action="{{ route('absensi.cuti.post') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="jenis" value="cuti">

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div class="form-group">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" name="start_date" value="{{ old('start_date') }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" name="end_date" value="{{ old('end_date') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Jenis Cuti</label>
                            <select class="form-control" name="jenis_detail" required>
                                <option value="">Pilih jenis cuti</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->type_code }}" {{ old('jenis_detail') == $type->type_code ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Alasan Cuti</label>
                            <textarea class="form-control" name="keterangan" rows="4" minlength="10" placeholder="Jelaskan alasan cuti secara detail (minimal 10 karakter)" required>{{ old('keterangan') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Upload Dokumen Pendukung</label>
                            <input type="file" class="form-control" name="document" accept="image/*,.pdf">
                            <div style="font-size: 12px; color: #64748b; margin-top: 4px;">Surat dokter, surat keterangan, atau dokumen lainnya</div>
                        </div>

                        <button type="submit" class="btn" style="background: #1e293b; color: #ffffff; border: none; padding: 12px 28px; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 4px rgba(30,41,59,0.1);">Ajukan Cuti</button>
                    </form>
                </div>
@endsection
