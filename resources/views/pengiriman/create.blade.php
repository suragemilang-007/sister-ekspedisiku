@extends('layouts.app')

@section('title', 'Buat Pengiriman Baru')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Buat Pengiriman Baru</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.pengirim') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.history') }}">Pengiriman</a></li>
        <li class="breadcrumb-item active">Buat Baru</li>
    </ol>

    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-plus me-1"></i>
                    Form Pengiriman Baru
                </div>
                <div class="card-body">
                    <form id="pengirimanForm">
                        @csrf

                        <!-- Alamat Penjemputan Section -->
                        <div class="mb-4">
                            <h5 class="text-primary">1. Alamat Penjemputan</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="alamat_penjemputan_type" id="penjemputan_existing" value="existing" {{ count($alamatPenjemputan) > 0 ? 'checked' : 'disabled' }}>
                                        <label class="form-check-label" for="penjemputan_existing">
                                            Gunakan alamat yang sudah ada ({{ count($alamatPenjemputan) }} alamat)
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="alamat_penjemputan_type" id="penjemputan_new" value="new" {{ count($alamatPenjemputan) == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="penjemputan_new">
                                            Buat alamat penjemputan baru
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Existing Alamat Penjemputan -->
                            <div id="existing_penjemputan" class="mt-3" style="{{ count($alamatPenjemputan) > 0 ? '' : 'display: none;' }}">
                                <div class="mb-3">
                                    <label for="id_alamat_penjemputan" class="form-label">Pilih Alamat Penjemputan</label>
                                    <select class="form-select" id="id_alamat_penjemputan" name="id_alamat_penjemputan">
                                        <option value="">Pilih alamat penjemputan...</option>
                                        @foreach($alamatPenjemputan as $alamat)
                                        <option value="{{ $alamat->id_alamat_penjemputan }}" data-kecamatan="{{ $alamat->kecamatan }}">
                                            {{ $alamat->nama_pengirim }} - {{ $alamat->kecamatan }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="detail_penjemputan" class="alert alert-info" style="display: none;">
                                    <!-- Detail alamat penjemputan akan dimuat disini -->
                                </div>
                            </div>

                            <!-- New Alamat Penjemputan -->
                            <div id="new_penjemputan" class="mt-3" style="{{ count($alamatPenjemputan) == 0 ? '' : 'display: none;' }}">
                                <div class="alert alert-warning">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Anda akan diarahkan ke halaman pembuatan alamat penjemputan baru.
                                </div>
                                <a href="{{ route('alamat-penjemputan.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Buat Alamat Penjemputan
                                </a>
                            </div>
                        </div>

                        <!-- Alamat Tujuan Section -->
                        <div class="mb-4">
                            <h5 class="text-primary">2. Alamat Tujuan</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="alamat_tujuan_type" id="tujuan_existing" value="existing" {{ count($alamatTujuan) > 0 ? 'checked' : 'disabled' }}>
                                        <label class="form-check-label" for="tujuan_existing">
                                            Gunakan alamat yang sudah ada ({{ count($alamatTujuan) }} alamat)
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="alamat_tujuan_type" id="tujuan_new" value="new" {{ count($alamatTujuan) == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="tujuan_new">
                                            Buat alamat tujuan baru
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Existing Alamat Tujuan -->
                            <div id="existing_tujuan" class="mt-3" style="{{ count($alamatTujuan) > 0 ? '' : 'display: none;' }}">
                                <div class="mb-3">
                                    <label for="id_alamat_tujuan" class="form-label">Pilih Alamat Tujuan</label>
                                    <select class="form-select" id="id_alamat_tujuan" name="id_alamat_tujuan">
                                        <option value="">Pilih alamat tujuan...</option>
                                        @foreach($alamatTujuan as $alamat)
                                        <option value="{{ $alamat->id_alamat_tujuan }}" data-kecamatan="{{ $alamat->kecamatan }}">
                                            {{ $alamat->nama_penerima }} - {{ $alamat->kecamatan }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="detail_tujuan" class="alert alert-info" style="display: none;">
                                    <!-- Detail alamat tujuan akan dimuat disini -->
                                </div>
                            </div>

                            <!-- New Alamat Tujuan -->
                            <div id="new_tujuan" class="mt-3" style="{{ count($alamatTujuan) == 0 ? '' : 'display: none;' }}">
                                <div class="alert alert-warning">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Anda akan diarahkan ke halaman pembuatan alamat tujuan baru.
                                </div>
                                <a href="{{ route('alamat-tujuan.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Buat Alamat Tujuan
                                </a>
                            </div>
                        </div>

                        <!-- Layanan dan Zona Section -->
                        <div class="mb-4">
                            <h5 class="text-primary">3. Layanan dan Zona Pengiriman</h5>
                            
                            <!-- Layanan Paket -->
                            <div class="mb-3">
                                <label for="id_layanan" class="form-label">Layanan Paket <span class="text-danger">*</span></label>
                                <select class="form-select" id="id_layanan" name="id_layanan" required>
                                    <option value="">Pilih layanan paket...</option>
                                    @foreach($layananPaket as $layanan)
                                    <option value="{{ $layanan->id_layanan }}" 
                                            data-deskripsi="{{ $layanan->deskripsi }}"
                                            data-min-berat="{{ $layanan->min_berat }}"
                                            data-max-berat="{{ $layanan->max_berat }}"
                                            data-harga-dasar="{{ $layanan->harga_dasar }}">
                                        {{ $layanan->nama_layanan }} ({{ $layanan->min_berat }}kg - {{ $layanan->max_berat }}kg)
                                    </option>
                                    @endforeach
                                </select>
                                <div id="layanan_detail" class="mt-2" style="display: none;">
                                    <!-- Detail layanan akan dimuat disini -->
                                </div>
                            </div>

                            <!-- Kecamatan Zona -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kecamatan_asal" class="form-label">Kecamatan Asal <span class="text-danger">*</span></label>
                                        <select class="form-select" id="kecamatan_asal" name="kecamatan_asal" required>
                                            <option value="">Pilih kecamatan asal...</option>
                                            @foreach($kecamatanAsal as $kecamatan)
                                            <option value="{{ $kecamatan }}">{{ $kecamatan }}</option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Akan otomatis terisi berdasarkan alamat penjemputan</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kecamatan_tujuan" class="form-label">Kecamatan Tujuan <span class="text-danger">*</span></label>
                                        <select class="form-select" id="kecamatan_tujuan" name="kecamatan_tujuan" required>
                                            <option value="">Pilih kecamatan tujuan...</option>
                                            @foreach($kecamatanTujuan as $kecamatan)
                                            <option value="{{ $kecamatan }}">{{ $kecamatan }}</option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Akan otomatis terisi berdasarkan alamat tujuan</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Pengiriman Section -->
                        <div class="mb-4">
                            <h5 class="text-primary">4. Detail Pengiriman</h5>
                            
                            <div class="mb-3">
                                <label for="catatan_opsional" class="form-label">Catatan Opsional</label>
                                <textarea class="form-control" id="catatan_opsional" name="catatan_opsional" rows="3" placeholder="Catatan tambahan untuk pengiriman (opsional)"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="foto_barang" class="form-label">Foto Barang (Opsional)</label>
                                <input type="file" class="form-control" id="foto_barang" name="foto_barang" accept="image/*">
                                <small class="form-text text-muted">Upload foto barang yang akan dikirim (maksimal 2MB)</small>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('dashboard.pengirim') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-paper-plane me-1"></i> Buat Pengiriman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-calculator me-1"></i>
                    Ringkasan Biaya
                </div>
                <div class="card-body">
                    <div id="biaya_summary" style="display: none;">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Layanan:</span>
                            <span id="summary_layanan">-</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Rute:</span>
                            <span id="summary_rute">-</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total Biaya:</strong>
                            <strong class="text-primary" id="summary_total">Rp 0</strong>
                        </div>
                    </div>
                    <div id="biaya_placeholder" class="text-muted text-center">
                        <i class="fas fa-info-circle me-1"></i>
                        Lengkapi form untuk melihat estimasi biaya
                    </div>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-question-circle me-1"></i>
                    Bantuan
                </div>
                <div class="card-body">
                    <div class="accordion" id="helpAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="helpOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                    Cara membuat pengiriman
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                <div class="accordion-body">
                                    <ol class="mb-0">
                                        <li>Pilih atau buat alamat penjemputan</li>
                                        <li>Pilih atau buat alamat tujuan</li>
                                        <li>Pilih layanan paket</li>
                                        <li>Tentukan zona pengiriman</li>
                                        <li>Isi detail pengiriman</li>
                                        <li>Konfirmasi dan buat pengiriman</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Toggle alamat penjemputan sections
    $('input[name="alamat_penjemputan_type"]').change(function() {
        if ($(this).val() === 'existing') {
            $('#existing_penjemputan').show();
            $('#new_penjemputan').hide();
        } else {
            $('#existing_penjemputan').hide();
            $('#new_penjemputan').show();
        }
        calculateBiaya();
    });

    // Toggle alamat tujuan sections
    $('input[name="alamat_tujuan_type"]').change(function() {
        if ($(this).val() === 'existing') {
            $('#existing_tujuan').show();
            $('#new_tujuan').hide();
        } else {
            $('#existing_tujuan').hide();
            $('#new_tujuan').show();
        }
        calculateBiaya();
    });

    // Load alamat penjemputan detail
    $('#id_alamat_penjemputan').change(function() {
        const id = $(this).val();
        const kecamatan = $(this).find(':selected').data('kecamatan');
        
        if (id) {
            // Set kecamatan asal
            $('#kecamatan_asal').val(kecamatan);
            
            // Load detail alamat
            axios.get(`/api/alamat-penjemputan/${id}`)
                .then(response => {
                    const alamat = response.data;
                    $('#detail_penjemputan').html(`
                        <strong>${alamat.nama_pengirim}</strong><br>
                        ${alamat.alamat_lengkap}<br>
                        ${alamat.kecamatan}, ${alamat.kode_pos}<br>
                        <i class="fas fa-phone me-1"></i>${alamat.no_hp}
                    `).show();
                })
                .catch(error => console.error('Error loading alamat penjemputan:', error));
        } else {
            $('#detail_penjemputan').hide();
            $('#kecamatan_asal').val('');
        }
        
        updateKecamatanTujuan();
        calculateBiaya();
    });

    // Load alamat tujuan detail
    $('#id_alamat_tujuan').change(function() {
        const id = $(this).val();
        const kecamatan = $(this).find(':selected').data('kecamatan');
        
        if (id) {
            // Set kecamatan tujuan
            $('#kecamatan_tujuan').val(kecamatan);
            
            // Load detail alamat
            axios.get(`/api/alamat-tujuan/${id}`)
                .then(response => {
                    const alamat = response.data;
                    $('#detail_tujuan').html(`
                        <strong>${alamat.nama_penerima}</strong><br>
                        ${alamat.alamat_lengkap}<br>
                        ${alamat.kecamatan}, ${alamat.kode_pos}<br>
                        <i class="fas fa-phone me-1"></i>${alamat.no_hp}
                    `).show();
                })
                .catch(error => console.error('Error loading alamat tujuan:', error));
        } else {
            $('#detail_tujuan').hide();
            $('#kecamatan_tujuan').val('');
        }
        
        calculateBiaya();
    });

    // Load layanan detail
    $('#id_layanan').change(function() {
        const id = $(this).val();
        const option = $(this).find(':selected');
        
        if (id) {
            const deskripsi = option.data('deskripsi');
            const minBerat = option.data('min-berat');
            const maxBerat = option.data('max-berat');
            const hargaDasar = option.data('harga-dasar');
            
            $('#layanan_detail').html(`
                <div class="alert alert-light">
                    <small>
                        <strong>Deskripsi:</strong> ${deskripsi}<br>
                        <strong>Berat:</strong> ${minBerat}kg - ${maxBerat}kg<br>
                        <strong>Harga Dasar:</strong> Rp ${number_format(hargaDasar)}
                    </small>
                </div>
            `).show();
        } else {
            $('#layanan_detail').hide();
        }
        
        updateKecamatanTujuan();
        calculateBiaya();
    });

    // Update kecamatan tujuan based on asal and layanan
    function updateKecamatanTujuan() {
        const kecamatanAsal = $('#kecamatan_asal').val();
        const idLayanan = $('#id_layanan').val();
        
        if (kecamatanAsal && idLayanan) {
            axios.get('/api/kecamatan-tujuan', {
                params: {
                    kecamatan_asal: kecamatanAsal,
                    id_layanan: idLayanan
                }
            })
            .then(response => {
                const kecamatanTujuan = $('#kecamatan_tujuan');
                const currentValue = kecamatanTujuan.val();
                
                kecamatanTujuan.empty().append('<option value="">Pilih kecamatan tujuan...</option>');
                
                response.data.forEach(kecamatan => {
                    kecamatanTujuan.append(`<option value="${kecamatan}">${kecamatan}</option>`);
                });
                
                // Restore previous value if still available
                if (response.data.includes(currentValue)) {
                    kecamatanTujuan.val(currentValue);
                }
            })
            .catch(error => console.error('Error updating kecamatan tujuan:', error));
        }
    }

    // Calculate biaya
    function calculateBiaya() {
        const kecamatanAsal = $('#kecamatan_asal').val();
        const kecamatanTujuan = $('#kecamatan_tujuan').val();
        const idLayanan = $('#id_layanan').val();
        
        if (kecamatanAsal && kecamatanTujuan && idLayanan) {
            axios.get('/api/zona-pengiriman', {
                params: {
                    kecamatan_asal: kecamatanAsal,
                    kecamatan_tujuan: kecamatanTujuan,
                    id_layanan: idLayanan
                }
            })
            .then(response => {
                const zona = response.data;
                
                $('#summary_layanan').text(zona.layanan);
                $('#summary_rute').text(`${zona.asal} → ${zona.tujuan}`);
                $('#summary_total').text(`Rp ${number_format(zona.biaya_tambahan + zona.layanan.harga_dasar)}`);

                $('#biaya_placeholder').hide();
                $('#biaya_summary').show();
            })
            .catch(error => {
                $('#biaya_summary').hide();
                $('#biaya_placeholder').show();
                console.error('Error calculating biaya:', error);
            });
        } else {
            $('#biaya_summary').hide();
            $('#biaya_placeholder').show();
        }
    }

    // Manual change events for kecamatan
    $('#kecamatan_asal, #kecamatan_tujuan').change(function() {
        if ($(this).attr('id') === 'kecamatan_asal') {
            updateKecamatanTujuan();
        }
        calculateBiaya();
    });

    // Form submission
    $('#pengirimanForm').submit(function(e) {
        e.preventDefault();
        
        const submitBtn = $('#submitBtn');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Memproses...');
        
        const formData = new FormData(this);
        
        // Handle foto barang upload
        const fotoBarang = $('#foto_barang')[0].files[0];
        if (fotoBarang) {
            const reader = new FileReader();
            reader.onload = function(e) {
                formData.set('foto_barang', e.target.result);
                submitForm(formData);
            };
            reader.readAsDataURL(fotoBarang);
        } else {
            submitForm(formData);
        }
    });

    function submitForm(formData) {
        axios.post('{{ route("pengiriman.store") }}', formData)
            .then(response => {
                if (response.data.redirect) {
                    // Redirect to create alamat
                    Swal.fire({
                        icon: 'info',
                        title: 'Perhatian',
                        text: response.data.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = response.data.redirect;
                    });
                } else {
                    // Success
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        html: `
                            Pengiriman berhasil dibuat!<br>
                            <strong>Nomor Resi:</strong> ${response.data.nomor_resi}<br>
                            <strong>Total Biaya:</strong> Rp ${number_format(response.data.total_biaya)}
                        `,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = '{{ route("dashboard.pengirim") }}';
                    });
                }
            })
            .catch(error => {
                let errorMessage = 'Terjadi kesalahan saat membuat pengiriman.';
                
                if (error.response && error.response.data) {
                    if (error.response.data.error) {
                        errorMessage = error.response.data.error;
                    } else if (error.response.data.errors) {
                        const errors = Object.values(error.response.data.errors).flat();
                        errorMessage = errors.join('<br>');
                    }
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: errorMessage
                });
            })
            .finally(() => {
                $('#submitBtn').prop('disabled', false).html('<i class="fas fa-paper-plane me-1"></i> Buat Pengiriman');
            });
    }

    // Helper function to format number
    function number_format(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }
});
</script>
@endpush