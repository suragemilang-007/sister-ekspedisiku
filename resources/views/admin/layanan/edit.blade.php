@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endsection

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h3 class="mb-0">Edit Layanan Paket</h3>
            </div>
            <div class="card-body">
                <form id="form-layanan" class="needs-validation" novalidate>
                    @csrf
                    <div class="row g-3">
                        <input type="hidden" name="id_layanan" value="{{ $layananPaket->id_layanan }}">

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" name="nama_layanan" class="form-control" id="nama_layanan"
                                    placeholder="Nama Layanan" value="{{ $layananPaket->nama_layanan }}" required>
                                <label for="nama_layanan">Nama Layanan</label>
                                <div class="invalid-feedback">Nama layanan wajib diisi.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" name="deskripsi" class="form-control" id="deskripsi"
                                    placeholder="Deskripsi" value="{{ $layananPaket->deskripsi }}" required>
                                <label for="deskripsi">Deskripsi</label>
                                <div class="invalid-feedback">Deskripsi wajib diisi.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="number" name="min_berat" class="form-control" id="min_berat"
                                    placeholder="Minimal Berat" value="{{ $layananPaket->min_berat }}" required>
                                <label for="min_berat">Minimal Berat</label>
                                <div class="invalid-feedback">Minimal berat wajib diisi.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="number" name="max_berat" class="form-control" id="max_berat"
                                    placeholder="Maksimal Berat" value="{{ $layananPaket->max_berat }}" required>
                                <label for="max_berat">Maksimal Berat</label>
                                <div class="invalid-feedback">Maksimal berat wajib diisi.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="number" id="harga_dasar_display" class="form-control"
                                    placeholder="Harga Dasar"
                                    value="{{ number_format($layananPaket->harga_dasar, 0, ',', '.') }}" required>

                                <input type="hidden" name="harga_dasar" id="harga_dasar"
                                    value="{{ $layananPaket->harga_dasar }}">

                                <label for="harga_dasar_display">Harga Dasar (Rp)</label>
                                <div class="invalid-feedback">Harga dasar wajib diisi</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="hidden" name="id_layanan" class="form-control" id="id_layanan"
                                    placeholder="Username" value="{{ $layananPaket->id_layanan }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="{{ route('admin.layanan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary" id="btn-simpan">
                            <span class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                            <i class="fas fa-save me-2"></i> Simpan Perubahan
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
        const inputDisplay = document.getElementById('harga_dasar_display');
        const inputHidden = document.getElementById('harga_dasar');

        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function unformatRupiah(rp) {
            return rp.replace(/\./g, '').replace(/[^\d]/g, '');
        }

        inputDisplay.addEventListener('input', function() {
            let raw = unformatRupiah(this.value);
            if (!isNaN(raw)) {
                this.value = formatRupiah(raw);
                inputHidden.value = raw;
            }
        });

        // Trigger format on page load
        document.addEventListener("DOMContentLoaded", function() {
            let raw = inputHidden.value;
            if (raw) inputDisplay.value = formatRupiah(raw);
        });
        const form = document.getElementById('form-layanan');
        const btn = document.getElementById('btn-simpan');
        const spinner = btn.querySelector('.spinner-border');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            Swal.fire({
                title: 'Simpan Perubahan?',
                text: "Pastikan data sudah benar.",
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
                    btn.disabled = true;
                    spinner.classList.remove('d-none');

                    axios.post(`/admin/layanan/update/${id_layanan}`, new FormData(form))
                        .then(res => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Data layanan berhasil diperbarui.',
                                timer: 2000,
                                showConfirmButton: false,
                                customClass: {
                                    popup: 'animate__animated animate__fadeInDown'
                                }
                            }).then(() => {
                                window.location.href = '{{ route('admin.layanan.index') }}';
                            });
                        })
                        .catch(err => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: err.response?.data?.message ||
                                    'Terjadi kesalahan saat menyimpan data.',
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
    </script>
@endsection
