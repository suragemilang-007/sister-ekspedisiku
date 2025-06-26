@extends('layouts.kurir')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="fw-bold mb-1">Pengaturan Akun</h1>
        <p class="text-muted">Kelola informasi akun dan keamanan</p>
    </div>

    <!-- Informasi Akun -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Informasi Pribadi -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Pribadi</h5>
                </div>
                <div class="card-body">
                    <form action="/kurir/update-info" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ $kurir->nama }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $kurir->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="telepon" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="telepon" name="telepon" value="{{ $kurir->telepon }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{ $kurir->alamat }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Ubah Password -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Ubah Password</h5>
                </div>
                <div class="card-body">
                    <form action="/kurir/update-password" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="password_lama" class="form-label">Password Lama</label>
                            <input type="password" class="form-control" id="password_lama" name="password_lama" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_baru" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="password_baru" name="password_baru" required>
                        </div>
                        <div class="mb-3">
                            <label for="konfirmasi_password" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-key me-2"></i> Ubah Password
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Informasi Akun -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Akun</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar-container mx-auto mb-3" style="width: 100px; height: 100px; background-color: #4e73df; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user fa-3x text-white"></i>
                        </div>
                        <h5 class="fw-bold mb-0">{{ $kurir->nama }}</h5>
                        <p class="text-muted">Kurir</p>
                    </div>
                    <div class="mb-3">
                        <div class="fw-bold text-muted mb-1">ID Kurir</div>
                        <div>{{ $kurir->id_kurir }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="fw-bold text-muted mb-1">Email</div>
                        <div>{{ $kurir->email }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="fw-bold text-muted mb-1">Telepon</div>
                        <div>{{ $kurir->telepon }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="fw-bold text-muted mb-1">Status</div>
                        <div><span class="badge bg-success">Aktif</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection