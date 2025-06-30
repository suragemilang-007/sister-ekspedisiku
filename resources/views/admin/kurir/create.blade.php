@extends('layouts.admin')

@section('title', 'Tambah Kurir Baru')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endsection

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-bottom-0 py-3">
            <h5 class="mb-0">Tambah Kurir Baru</h5>
        </div>
        <div class="card-body">
            <form id="form-add-kurir" class="needs-validation" novalidate>
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
                            <input type="tel" name="nohp" class="form-control" id="nohp" placeholder="No HP">
                            <label for="nohp">No HP</label>
                            <div class="invalid-feedback">No HP tidak valid</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <textarea name="alamat" class="form-control" id="alamat" placeholder="Alamat" style="height: 100px"></textarea>
                            <label for="alamat">Alamat</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <select name="status" class="form-select" id="status" required>
                                <option value="">Pilih Status</option>
                                <option value="AKTIF">AKTIF</option>
                                <option value="NONAKTIF">NONAKTIF</option>
                            </select>
                            <label for="status">Status</label>
                            <div class="invalid-feedback">Pilih status kurir</div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.kurir.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary" id="btn-add-kurir">
                        <span class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                        <i class="fas fa-plus-circle me-2"></i> Tambah Kurir
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
        const form = document.getElementById('form-add-kurir');
        const passwordField = document.getElementById('password');
        const passwordConfirmationField = document.getElementById('password_confirmation');

        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);

        if (passwordConfirmationField) {
            passwordConfirmationField.addEventListener('input', validatePasswordMatch);
            passwordField.addEventListener('input', validatePasswordMatch);
        }

        function validatePasswordMatch() {
            if (passwordConfirmationField.value !== passwordField.value) {
                passwordConfirmationField.setCustomValidity('Password tidak cocok');
            } else {
                passwordConfirmationField.setCustomValidity('');
            }
        }

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            if (!this.checkValidity()) return;

            const btn = document.getElementById('btn-add-kurir');
            const spinner = btn.querySelector('.spinner-border');

            Swal.fire({
                title: 'Tambahkan Kurir Ini?',
                text: "Pastikan data yang diisi sudah benar.",
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

                    axios.post('{{ route('admin.kurir.store') }}', new FormData(e.target))
                        .then(res => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.data.message || 'Kurir berhasil ditambahkan.',
                                timer: 3000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = '{{ route('admin.kurir.index') }}';
                            });
                        })
                        .catch(err => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: err.response?.data?.message || 'Terjadi kesalahan saat menambahkan kurir.'
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
