@extends('layouts.admin') {{-- Pastikan ini sesuai dengan layout admin Anda --}}

@section('title', 'Buat Layanan Paket Baru')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        /* CSS Kustom yang sama seperti contoh pengguna create */
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .form-floating>.form-control:focus,
        .form-floating>.form-control:not(:placeholder-shown) {
            padding-top: 1.625rem;
            padding-bottom: 0.625rem;
        }

        .form-floating>.form-control:focus~label,
        .form-floating>.form-control:not(:placeholder-shown)~label {
            opacity: .65;
            transform: scale(.85) translateY(-0.5rem) translateX(0.15rem);
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }
    </style>
@endsection

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Buat Layanan Paket Baru</h5>
                <a href="{{ route('admin.layanan.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
            <div class="card-body p-4">
                {{-- TAMBAHKAN DATA ATTRIBUTES DI SINI UNTUK URL ROUTE --}}
                <form id="form-add-layanan" class="needs-validation" novalidate
                    data-store-url="{{ route('admin.layanan.store') }}" data-list-url="{{ route('admin.layanan.index') }}">
                    @csrf {{-- Laravel CSRF Token untuk keamanan --}}

                    <div class="row g-3">
                        {{-- Input untuk id_layanan --}}
                        {{-- Asumsi id_layanan adalah input angka. Jika ini dropdown, sesuaikan. --}}
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" name="nama_layanan" class="form-control" id="nama_layanan"
                                    placeholder="Nama Layanan" required>
                                <label for="nama_layanan">Nama Layanan</label>
                                <div class="invalid-feedback">Nama Layanan tidak boleh kosong.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <textarea name="deskripsi" class="form-control" id="deskripsi"
                                    placeholder="Deskripsi" required></textarea>
                                <label for="deskripsi">Deskripsi</label>
                                <div class="invalid-feedback">Deskripsi tidak boleh kosong.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="number" name="berat_minimal" class="form-control" id="berat_minimal"
                                    placeholder="Berat Minimal" required>
                                <label for="berat_minimal">Berat Minimal (kg)</label>
                                <div class="invalid-feedback">Berat Minimal tidak boleh kosong dan harus angka positif.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="number" name="berat_maksimal" class="form-control" id="berat_maksimal"
                                    placeholder="Berat Maksimal" required>
                                <label for="berat_maksimal">Berat Maksimal (kg)</label>
                                <div class="invalid-feedback">Berat Maksimal tidak boleh kosong dan harus angka positif.</div>
                            </div>
                        </div>
                        {{-- Input untuk biaya --}}
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="number" name="biaya" class="form-control" id="biaya"
                                    placeholder="Biaya" step="0.01" min="0" required>
                                <label for="biaya">Biaya (Rp)</label>
                                <div class="invalid-feedback">Biaya tidak boleh kosong dan harus angka positif.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="{{ route('admin.layanan.index') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary" id="btn-add-layanan">
                            <span class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                            <i class="fas fa-plus-circle me-2"></i>Simpan Layanan Baru
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
            const form = document.getElementById('form-add-layanan');
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);


            // Handle form submission via Axios
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!this.checkValidity()) {
                    return;
                }

                const btn = document.getElementById('btn-add-layanan');
                const spinner = btn.querySelector('.spinner-border');

                Swal.fire({
                    title: 'Tambahkan Layanan Ini?',
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

                        axios.post('{{ route('admin.layanan.store') }}', new FormData(e.target))
                            .then(res => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Permintaan Dikirim!',
                                    text: res.data.message ||
                                        'Permintaan penambahan zona berhasil dikirim. Admin akan segera terdaftar.',
                                    timer: 3000,
                                    showConfirmButton: false,
                                    customClass: {
                                        popup: 'animate__animated animate__fadeInDown'
                                    }
                                }).then(() => {
                                    window.location.href =
                                        '{{ route('admin.layanan.index') }}'; // Redirect ke daftar layanan
                                });
                            })
                            .catch(err => {
                                let errorMessage = 'Gagal mengirim permintaan penambahan layanan.';
                                if (err.response && err.response.data && err.response.data
                                    .message) {
                                    errorMessage = err.response.data.message;
                                } else if (err.response && err.response.data && err.response
                                    .data.errors) {
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
