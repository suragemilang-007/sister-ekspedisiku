@extends('layouts.admin')

@section('title', 'Edit Kurir')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h3 class="mb-0">Edit Kurir</h3>
            </div>
            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-fill mb-4" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile" type="button"
                            role="tab">
                            <i class="fas fa-user me-2"></i>Profil
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#security" type="button"
                            role="tab">
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
                            <input type="hidden" name="id_kurir" value="{{ $kurir->id_kurir }}">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nama</label>
                                    <input type="text" name="nama" class="form-control" value="{{ $kurir->nama }}"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ $kurir->email }}"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">No HP</label>
                                    <input type="text" name="nohp" class="form-control" value="{{ $kurir->nohp }}"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="AKTIF" {{ $kurir->status == 'AKTIF' ? 'selected' : '' }}>AKTIF
                                        </option>
                                        <option value="NONAKTIF" {{ $kurir->status == 'NONAKTIF' ? 'selected' : '' }}>
                                            NONAKTIF</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="alamat" class="form-control" rows="3" required>{{ $kurir->alamat }}</textarea>
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <a href="{{ route('admin.kurir.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary" id="btn-simpan">
                                    <span class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                                    <i class="fas fa-save me-2"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Password Tab -->
                    <div class="tab-pane fade" id="security" role="tabpanel">
                        <form id="form-password" class="needs-validation" novalidate>
                            @csrf
                            <input type="hidden" name="id_kurir" value="{{ $kurir->id_kurir }}">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Password Baru</label>
                                    <input type="password" id="password" name="password" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Konfirmasi Password</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <a href="{{ route('admin.kurir.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary" id="btn-simpan">
                                    <span class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                                    <i class="fas fa-save me-2"></i> Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const formprofile = document.getElementById('form-update');
        formprofile.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Simpan Perubahan?',
                text: "Data kurir akan diperbarui.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, simpan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post("{{ route('admin.kurir.update.info') }}", formprofile)
                        .then(res => {
                            Swal.fire('Berhasil!', 'Data kurir telah diperbarui.', 'success')
                                .then(() => window.location.href = "{{ route('admin.kurir.index') }}");
                        })
                        .catch(err => {
                            Swal.fire('Gagal!', 'Terjadi kesalahan saat memperbarui data.', 'error');
                        });
                }
            });
        });

        // Password confirmation validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('form-password');
            const password = document.getElementById('password');
            const passwordConfirmation = document.getElementById('password_confirmation');
            const btn = document.getElementById('btn-simpan');
            const spinner = btn.querySelector('.spinner-border');

            function validatePasswordMatch() {
                if (passwordConfirmation.value !== password.value) {
                    passwordConfirmation.setCustomValidity('Password tidak cocok');
                } else {
                    passwordConfirmation.setCustomValidity('');
                }
            }

            password.addEventListener('input', validatePasswordMatch);
            passwordConfirmation.addEventListener('input', validatePasswordMatch);

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    return;
                }

                Swal.fire({
                    title: 'Ubah Password?',
                    text: "Password kurir akan diperbarui.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, ubah!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        btn.disabled = true;
                        spinner.classList.remove('d-none');

                        const formData = new FormData(form);
                        axios.post("{{ route('admin.kurir.update.password') }}", formData)
                            .then(res => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Password berhasil diperbarui.',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href =
                                        "{{ route('admin.kurir.index') }}";
                                });
                            })
                            .catch(err => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: err.response?.data?.message ||
                                        'Terjadi kesalahan saat mengubah password.'
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
