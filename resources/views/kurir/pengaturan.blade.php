@extends('layouts.kurir')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Pengaturan Akun</h1>
            <p class="text-muted">Kelola informasi akun kurir Anda</p>
        </div>
    </div>

    <div class="row">
        <!-- Informasi Akun -->
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Akun</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('kurir.update.info') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ $kurir->nama ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $kurir->email ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="nomor_telepon" name="nomor_telepon" value="{{ $kurir->nomor_telepon ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{ $kurir->alamat ?? '' }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Ubah Password -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Ubah Password</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('kurir.update.password') }}" method="POST">
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
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key me-2"></i>Ubah Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Tambahan -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Kurir</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>ID Kurir:</strong> {{ $kurir->id_kurir ?? 'N/A' }}</p>
                            <p><strong>Status:</strong> 
                                @if($kurir->status ?? false)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Tidak Aktif</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Tanggal Bergabung:</strong> {{ $kurir->created_at ? $kurir->created_at->format('d M Y') : 'N/A' }}</p>
                            <p><strong>Terakhir Update:</strong> {{ $kurir->updated_at ? $kurir->updated_at->format('d M Y H:i') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Validasi konfirmasi password
        const passwordBaru = document.getElementById('password_baru');
        const konfirmasiPassword = document.getElementById('konfirmasi_password');
        
        function validatePassword() {
            if (passwordBaru.value !== konfirmasiPassword.value) {
                konfirmasiPassword.setCustomValidity('Password tidak cocok');
            } else {
                konfirmasiPassword.setCustomValidity('');
            }
        }
        
        passwordBaru.addEventListener('change', validatePassword);
        konfirmasiPassword.addEventListener('keyup', validatePassword);
    });
</script>
@endsection 