@extends('layouts.absen')

@section('title', 'Pengajuan Izin')

@section('content')
  <div class="page-content active" id="absensi-izin">
    <div class="content-title">Pengajuan Izin</div>
    <p class="content-description">
      Ajukan izin tidak masuk kerja dengan alasan yang jelas
    </p>

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

    <form id="formIzin" method="POST" action="{{ route('absensi.cuti.post') }}" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="jenis" value="izin">

      <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
        <div class="form-group">
          <label class="form-label">Tanggal Izin</label>
          <input type="date" class="form-control" name="start_date" value="{{ old('start_date') }}" required>
        </div>

        <div class="form-group">
          <label class="form-label">Jenis Izin</label>
          <select class="form-control" name="jenis_detail" required>
            <option value="">Pilih jenis izin</option>
            @foreach($leaveTypes as $type)
              <option value="{{ $type->type_code }}" {{ old('jenis_detail') == $type->type_code ? 'selected' : '' }}>{{ $type->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Alasan Izin</label>
        <textarea class="form-control" name="keterangan" rows="4" minlength="10"
          placeholder="Jelaskan alasan izin secara detail (minimal 10 karakter)" required>{{ old('keterangan') }}</textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Upload Bukti (Opsional)</label>
        <input type="file" class="form-control" name="document" accept="image/*,.pdf">
        <div style="font-size:12px; color:#64748b; margin-top:4px;">
          Surat dokter, foto, atau dokumen pendukung
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Ajukan Izin</button>
    </form>
  </div>
@endsection
