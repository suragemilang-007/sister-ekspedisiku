@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .nav-tabs .nav-link {
        transition: all 0.3s ease;
        border: none;
        color: var(--bs-gray-600);
        padding: 1rem 1.5rem;
    }
    .nav-tabs .nav-link:hover {
        color: var(--bs-primary);
        background: rgba(13, 110, 253, 0.1);
        border: none;
    }
    .nav-tabs .nav-link.active {
        color: var(--bs-primary);
        background: rgba(13, 110, 253, 0.1);
        border: none;
        position: relative;
    }
    .nav-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: var(--bs-primary);
        animation: slideIn 0.3s ease-out forwards;
    }
    @keyframes slideIn {
        from { transform: scaleX(0); }
        to { transform: scaleX(1); }
    }
    .form-floating > .form-control:focus,
    .form-floating > .form-control:not(:placeholder-shown) {
        padding-top: 1.625rem;
        padding-bottom: 0.625rem;
    }
    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        opacity: .65;
        transform: scale(.85) translateY(-0.5rem) translateX(0.15rem);
    }
    .btn {
        transition: all 0.3s ease;
    }
    .btn:hover {
        transform: translateY(-2px);
    }
    .tab-content {
        animation: fadeIn 0.5s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-bottom-0 py-3">
            <h3 class="mb-0">Pengaturan Akun</h3>
        </div>
        <div class="card-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs nav-fill mb-4" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                        <i class="fas fa-user me-2"></i>Profil
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">
                        <i class="fas fa-lock me-2"></i>Password
                    </button>
                </li>
            </ul>

            <!-- Tab content -->
            <div class="tab-content">
                <!-- Profile Tab -->
                <div class="tab-pane fade show active" id="profile" role="tabpanel">
                    <form id="form-update" class="needs-validation" novalidate>
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" name="nama" class="form-control" id="nama" placeholder="Nama" value="{{ $pengguna->nama }}" required>
                                    <label for="nama">Nama</label>
                                    <div class="invalid-feedback">Nama tidak boleh kosong</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="email" name="email" class="form-control" id="email" placeholder="Email" value="{{ $pengguna->email }}" required>
                                    <label for="email">Email</label>
                                    <div class="invalid-feedback">Email tidak valid</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="date" name="tgl_lahir" class="form-control" id="tgl_lahir" value="{{ $pengguna->tgl_lahir }}" required>
                                    <label for="tgl_lahir">Tanggal Lahir</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="tel" name="nohp" class="form-control" id="nohp" placeholder="No HP" value="{{ $pengguna->nohp }}" required>
                                    <label for="nohp">No HP</label>
                                    <div class="invalid-feedback">No HP tidak valid</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <textarea name="alamat" class="form-control" id="alamat" placeholder="Alamat" style="height: 100px" required>{{ $pengguna->alamat }}</textarea>
                                    <label for="alamat">Alamat</label>
                                    <div class="invalid-feedback">Alamat tidak boleh kosong</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select name="kelamin" class="form-select" id="kelamin" required>
                                        <option value="L" {{ $pengguna->kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ $pengguna->kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    <label for="kelamin">Jenis Kelamin</label>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="submit" class="btn btn-primary" id="btn-update">
                                <span class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Security Tab -->
                <div class="tab-pane fade" id="security" role="tabpanel">
                    <form id="form-password" class="needs-validation" novalidate>
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="password" name="password" class="form-control" id="password" placeholder="Password Baru" required>
                                    <label for="password">Password Baru</label>
                                    <div class="invalid-feedback">Password tidak boleh kosong</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Konfirmasi Password" required>
                                    <label for="password_confirmation">Konfirmasi Password</label>
                                    <div class="invalid-feedback">Password tidak cocok</div>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="submit" class="btn btn-warning" id="btn-password">
                                <span class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                                <i class="fas fa-key me-2"></i>Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Enable Bootstrap tooltips
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

// Form validation
const forms = document.querySelectorAll('.needs-validation');
forms.forEach(form => {
    form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    }, false);
});

// Password confirmation validation
const passwordConfirmation = document.getElementById('password_confirmation');
if (passwordConfirmation) {
    passwordConfirmation.addEventListener('input', function() {
        const password = document.getElementById('password').value;
        if (this.value !== password) {
            this.setCustomValidity('Password tidak cocok');
        } else {
            this.setCustomValidity('');
        }
    });
}

// Profile update form
document.getElementById('form-update').addEventListener('submit', function (e) {
    e.preventDefault();
    
    if (!this.checkValidity()) {
        return;
    }

    const btn = document.getElementById('btn-update');
    const spinner = btn.querySelector('.spinner-border');

    Swal.fire({
        title: 'Simpan Perubahan?',
        text: "Pastikan data yang diisi sudah benar.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, simpan!',
        cancelButtonText: 'Batal',
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-secondary'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            btn.disabled = true;
            spinner.classList.remove('d-none');

            axios.post('/admin/update-info', new FormData(e.target))
                .then(res => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Informasi pengguna berhasil diperbarui.',
                        timer: 2000,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        }
                    });
                })
                .catch(err => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: err.response?.data?.message || 'Gagal memperbarui informasi.',
                        customClass: {
                            popup: 'animate__animated animate__shakeX'
                        }
                    });
                })
                .finally(() => {
                    // Hide loading state
                    btn.disabled = false;
                    spinner.classList.add('d-none');
                });
        }
    });
});

// Password update form
document.getElementById('form-password').addEventListener('submit', function (e) {
    e.preventDefault();

    if (!this.checkValidity()) {
        return;
    }

    const btn = document.getElementById('btn-password');
    const spinner = btn.querySelector('.spinner-border');

    Swal.fire({
        title: 'Ubah Password?',
        text: "Pastikan password baru sudah benar.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, ubah!',
        cancelButtonText: 'Batal',
        customClass: {
            confirmButton: 'btn btn-warning',
            cancelButton: 'btn btn-secondary'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            btn.disabled = true;
            spinner.classList.remove('d-none');

            axios.post('/admin/update-password', new FormData(e.target))
                .then(res => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Password Diperbarui!',
                        text: 'Password berhasil diubah.',
                        timer: 2000,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        }
                    });
                    this.reset();
                    this.classList.remove('was-validated');
                })
                .catch(err => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: err.response?.data?.message || 'Gagal memperbarui password.',
                        customClass: {
                            popup: 'animate__animated animate__shakeX'
                        }
                    });
                })
                .finally(() => {
                    // Hide loading state
                    btn.disabled = false;
                    spinner.classList.add('d-none');
                });
        }
    });
});
</script>
@endsection
