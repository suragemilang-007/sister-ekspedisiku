@extends('layouts.admin')

@section('title', 'Tambah Admin Baru')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endsection

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-bottom-0 py-3">
            <h5 class="mb-0">Tambah Admin Baru</h5>
        </div>
        <div class="card-body">
            <form id="form-add-admin" class="needs-validation" novalidate>
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" name="nama" class="form-control" id="nama" placeholder="Nama Lengkap" required>
                            <label for="nama">Nama Lengkap</label>
                            <div class="invalid-feedback">Nama tidak boleh kosong</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
                            <label for="email">Email</label>
                            <div class="invalid-feedback">Email tidak valid atau sudah digunakan</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="password" name="password" class="form-control" id="password" placeholder="Password" required minlength="8">
                            <label for="password">Password</label>
                            <div class="invalid-feedback">Password minimal 8 karakter</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Konfirmasi Password" required>
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <div class="invalid-feedback">Password tidak cocok</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="date" name="tgl_lahir" class="form-control" id="tgl_lahir">
                            <label for="tgl_lahir">Tanggal Lahir (Opsional)</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="tel" name="nohp" class="form-control" id="nohp" placeholder="No HP">
                            <label for="nohp">No HP (Opsional)</label>
                            <div class="invalid-feedback">No HP tidak valid</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <textarea name="alamat" class="form-control" id="alamat" placeholder="Alamat" style="height: 100px"></textarea>
                            <label for="alamat">Alamat (Opsional)</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <select name="kelamin" class="form-select" id="kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                            <label for="kelamin">Jenis Kelamin</label>
                            <div class="invalid-feedback">Pilih jenis kelamin</div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.pengguna.list') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary" id="btn-add-admin">
                        <span class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                        <i class="fas fa-plus-circle me-2"></i> Tambah Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation
        const form = document.getElementById('form-add-admin');
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);

        // Password confirmation validation
        const passwordField = document.getElementById('password');
        const passwordConfirmationField = document.getElementById('password_confirmation');
        if (passwordConfirmationField) {
            passwordConfirmationField.addEventListener('input', function() {
                if (this.value !== passwordField.value) {
                    this.setCustomValidity('Password tidak cocok');
                } else {
                    this.setCustomValidity('');
                }
            });
            passwordField.addEventListener('input', function() { // Juga cek saat password utama berubah
                 if (passwordConfirmationField.value !== this.value) {
                    passwordConfirmationField.setCustomValidity('Password tidak cocok');
                } else {
                    passwordConfirmationField.setCustomValidity('');
                }
            });
        }

        // Handle form submission via Axios
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            if (!this.checkValidity()) {
                return;
            }

            const btn = document.getElementById('btn-add-admin');
            const spinner = btn.querySelector('.spinner-border');

            Swal.fire({
                title: 'Tambahkan Admin Ini?',
                text: "Pastikan data yang diisi sudah benar. Permintaan akan dikirim dan diproses secara asinkron.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tambahkan!',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.disabled = true;
                    spinner.classList.remove('d-none');

                    axios.post('{{ route('admin.pengguna.store') }}', new FormData(e.target))
                        .then(res => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Permintaan Dikirim!',
                                text: res.data.message || 'Permintaan penambahan admin berhasil dikirim. Admin akan segera terdaftar.',
                                timer: 3000,
                                showConfirmButton: false,
                                customClass: {
                                    popup: 'animate__animated animate__fadeInDown'
                                }
                            }).then(() => {
                                window.location.href = '{{ route('admin.pengguna.list') }}'; // Redirect ke daftar admin
                            });
                        })
                        .catch(err => {
                            let errorMessage = 'Gagal mengirim permintaan penambahan admin.';
                            if (err.response && err.response.data && err.response.data.message) {
                                errorMessage = err.response.data.message;
                            } else if (err.response && err.response.data && err.response.data.errors) {
                                // Tampilkan error validasi dari Laravel
                                const errors = err.response.data.errors;
                                errorMessage = "<ul>";
                                for (const key in errors) {
                                    if (errors.hasOwnProperty(key)) {
                                        errors[key].forEach(errorMsg => {
                                            errorMessage += `<li>${errorMsg}</li>`;
                                        });
                                    }
                                }
                                errorMessage += "</ul>";
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                html: errorMessage, // Gunakan html untuk menampilkan daftar error
                                customClass: {
                                    popup: 'animate__animated animate__shakeX'
                                }
                            });
                        })
                        .finally(() => {
                            btn.disabled = false;
                            spinner.classList.add('d-none');
                        });
                }
            });
        });
    });
</script>
@endsection