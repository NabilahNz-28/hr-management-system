@extends('layouts.superadmin')

@section('title', 'Profile Super Administrator')

@section('content')
<div class="dashboard-content">
    <div class="page-content active" id="profile-superadmin">
        <div class="content-title text-center">Profile Super Admin</div>
        <p class="content-description text-center">Kelola data diri dan keamanan akun Anda</p>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ✅ {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0" style="padding-left:18px;">
                    @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Card Profile -->
        <div class="profile-card-superadmin">
            <!-- Form Data Diri -->
            <form id="form-profile" class="profile-form-superadmin" method="POST"
                  action="{{ route('superadmin.profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Foto Profile -->
                <div class="avatar-section-superadmin" style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                    <div class="avatar-wrapper-superadmin" style="margin-bottom: 0;">
                        <img id="foto-profile"
                             src="{{ $user->foto_profile ? asset('storage/'.$user->foto_profile) : asset('default-avatar.png') }}"
                             alt="Foto Profile">
                    </div>
                    <button type="button" class="btn-avatar-superadmin" style="position: static; margin-top: 4px; display: block;" onclick="document.getElementById('upload-foto').click()">Ganti Foto</button>
                    <input type="file" id="upload-foto" name="foto_profile" accept="image/*" style="display: none;" onchange="previewFoto(this)">
                </div>

                <!-- Badge Role -->
                <div class="superadmin-role-wrap">
                    <span class="superadmin-role-badge">{{ ucfirst($user->role) }}</span>
                </div>

                <div class="form-row-superadmin">
                    <div class="form-group-superadmin">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="form-group-superadmin">
                        <label>Email</label>
                        <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                    </div>
                </div>
                <div class="form-row-superadmin">
                    <div class="form-group-superadmin">
                        <label>NIK</label>
                        <input type="text" class="form-control" value="{{ $user->nik ?? '-' }}" readonly>
                    </div>
                    <div class="form-group-superadmin">
                        <label>Role</label>
                        <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" readonly>
                    </div>
                </div>
                <div class="form-row-superadmin">
                    <div class="form-group-superadmin">
                        <label>No. HP</label>
                        <input type="tel" name="no_hp" class="form-control" value="{{ old('no_hp', $user->no_hp) }}" placeholder="Masukkan nomor HP">
                    </div>
                    <div class="form-group-superadmin">
                        <label>Tanggal Bergabung</label>
                        <input type="text" class="form-control"
                               value="{{ $user->tgl_bergabung ? \Carbon\Carbon::parse($user->tgl_bergabung)->format('d F Y') : '-' }}" readonly>
                    </div>
                </div>
                <div class="form-row-superadmin">
                    <div class="form-group-superadmin" style="grid-column: 1 / -1;">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2" placeholder="Masukkan alamat lengkap">{{ old('alamat', $user->alamat) }}</textarea>
                    </div>
                </div>

                <div class="form-actions-superadmin">
                    <button type="submit" class="btn btn-primary btn-small">Simpan Perubahan</button>
                </div>
            </form>

            <!-- Ubah Password -->
            <div class="password-section-superadmin">
                <h3>Ubah Password</h3>
                <form class="password-form-superadmin" method="POST" action="{{ route('superadmin.profile.password') }}">
                    @csrf
                    @method('PUT')
                    <input type="password" name="current_password" class="form-control" placeholder="Password lama" required>
                    <input type="password" name="password" class="form-control" placeholder="Password baru (min. 6 karakter)" required>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi password baru" required>
                    <button type="submit" class="btn btn-primary btn-small">Ubah Password</button>
                </form>
            </div>

            <!-- Logout -->
            <div class="logout-section-superadmin">
                <form method="POST" action="{{ route('logout') }}" onsubmit="event.preventDefault(); confirmLogout();">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-small">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Preview foto sebelum di-upload (file tetap dikirim saat "Simpan Perubahan")
    function previewFoto(input) {
        const file = input.files[0];
        if (!file) return;
        if (!file.type.match('image.*')) { alert('File harus gambar'); input.value = ''; return; }
        if (file.size > 2 * 1024 * 1024) { alert('Maksimal 2MB'); input.value = ''; return; }
        const reader = new FileReader();
        reader.onload = e => document.getElementById('foto-profile').src = e.target.result;
        reader.readAsDataURL(file);
    }
</script>
@endsection
