@extends('layouts.absen')

@section('title', 'Pengajuan Izin')

@section('content')
  <div class="page-content active" id="absensi-izin">
    <div class="content-title">Pengajuan Izin</div>
    <p class="content-description">
      Ajukan izin tidak masuk kerja dengan alasan yang jelas
    </p>

    <form id="formIzin">
      <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:20px;">
        <div class="form-group">
          <label class="form-label">Tanggal Izin</label>
          <input type="date" class="form-control" name="tanggal_izin" required>
        </div>

        <div class="form-group">
          <label class="form-label">Jenis Izin</label>
          <select class="form-control" name="jenis_izin" required>
            <option value="">Pilih jenis izin</option>
            <option value="sakit">Sakit</option>
            <option value="urusan_keluarga">Urusan Keluarga</option>
            <option value="urusan_pribadi">Urusan Pribadi</option>
            <option value="lainnya">Lainnya</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Alasan Izin</label>
        <textarea class="form-control" name="alasan" rows="4"
          placeholder="Jelaskan alasan izin secara detail" required></textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Upload Bukti (Opsional)</label>
        <input type="file" class="form-control" name="bukti" accept="image/*,.pdf">
        <div style="font-size:12px; color:#64748b; margin-top:4px;">
          Surat dokter, foto, atau dokumen pendukung
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Ajukan Izin</button>
    </form>
  </div>
@endsection
