@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endsection

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h3 class="mb-0">Edit Zona Pengiriman</h3>
            </div>
            <div class="card-body">
                <form id="form-zona" class="needs-validation" novalidate>
                    @csrf
                    <div class="row g-3">
                        <input type="hidden" name="id_zona" value="{{ $zonaPengiriman->id_zona }}">

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" name="nama_zona" class="form-control" id="nama_zona"
                                    placeholder="Nama Zona" value="{{ $zonaPengiriman->nama_zona }}" required>
                                <label for="nama_zona">Nama Zona</label>
                                <div class="invalid-feedback">Nama zona wajib diisi.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select name="id_layanan" class="form-select" id="id_layanan" required>
                                    <option value="">-- Pilih Layanan --</option>
                                    @foreach ($layananPakets as $layanan)
                                        <option value="{{ $layanan->id_layanan }}"
                                            {{ $layanan->id_layanan == $zonaPengiriman->id_layanan ? 'selected' : '' }}>
                                            {{ $layanan->nama_layanan }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="id_layanan">Layanan</label>
                                <div class="invalid-feedback">Layanan harus dipilih.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" name="kecamatan_asal" class="form-control" id="kecamatan_asal"
                                    placeholder="Kecamatan Asal" value="{{ $zonaPengiriman->kecamatan_asal }}" required>
                                <label for="kecamatan_asal">Kecamatan Asal</label>
                                <div class="invalid-feedback">Kecamatan asal wajib diisi.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" name="kecamatan_tujuan" class="form-control" id="kecamatan_tujuan"
                                    placeholder="Kecamatan Tujuan" value="{{ $zonaPengiriman->kecamatan_tujuan }}" required>
                                <label for="kecamatan_tujuan">Kecamatan Tujuan</label>
                                <div class="invalid-feedback">Kecamatan tujuan wajib diisi.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" id="biaya_tambahan_display" class="form-control"
                                    placeholder="Biaya Tambahan"
                                    value="{{ number_format($zonaPengiriman->biaya_tambahan, 0, ',', '.') }}" required>

                                <input type="hidden" name="biaya_tambahan" id="biaya_tambahan"
                                    value="{{ $zonaPengiriman->biaya_tambahan }}">

                                <label for="biaya_tambahan_display">Biaya Tambahan (Rp)</label>
                                <div class="invalid-feedback">Biaya tambahan wajib diisi</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="hidden" name="id_zona" class="form-control" id="id_zona"
                                    placeholder="id zona" value="{{ $zonaPengiriman->id_zona }}" required>
                            </div>
                        </div>

                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="{{ route('admin.zona.index') }}" class="btn btn-secondary">
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
        const inputDisplay = document.getElementById('biaya_tambahan_display');
        const inputHidden = document.getElementById('biaya_tambahan');

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
        const form = document.getElementById('form-zona');
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
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.disabled = true;
                    spinner.classList.remove('d-none');

                    axios.post(`/admin/zona/update/${id_zona}`, new FormData(form))
                        .then(res => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Data zona berhasil diperbarui.',
                                timer: 2000,
                                showConfirmButton: false,
                                customClass: {
                                    popup: 'animate__animated animate__fadeInDown'
                                }
                            }).then(() => {
                                window.location.href = '{{ route('admin.zona.index') }}';
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
