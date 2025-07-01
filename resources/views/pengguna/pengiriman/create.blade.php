@extends('layouts.app')

@section('title', 'Buat Pengiriman Baru')

@section('content')
<!-- Loading Overlay -->
<div id="loading-overlay" class="position-fixed top-0 start-0 w-100 h-100 d-none" style="background: rgba(0,0,0,0.5); z-index: 9999;">
    <div class="position-absolute top-50 start-50 translate-middle text-white text-center">
        <div class="spinner-border text-primary mb-2" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mb-0">Memproses...</p>
    </div>
</div>

<div class="container-fluid px-4 animate__animated animate__fadeIn">
    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4 shadow-sm border-0 rounded-3">
                <div class="card-header bg-gradient py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-box-open me-2 text-primary"></i>
                        <h5 class="mb-0 flex-grow-1">Form Pengiriman Baru</h5>
                        <div class="progress" style="width: 100px; height: 6px;" data-bs-toggle="tooltip" data-bs-placement="left" title="Progress pengisian form">
                            <div class="progress-bar" role="progressbar" style="width: 0%" id="formProgress"></div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form id="pengirimanForm">
                        @csrf

                        <!-- Alamat Penjemputan Section -->
                        <div class="mb-4 section-fade">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px;">1</div>
                                <h5 class="text-primary mb-0">Alamat Penjemputan</h5>
                            </div>
                            <div class="card border-0 shadow-sm rounded-3 mb-3">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-check custom-radio p-3 border rounded-3 h-100 position-relative" data-bs-toggle="tooltip" data-bs-placement="top" title="Pilih dari alamat tersimpan">
                                                <input class="form-check-input" type="radio" name="alamat_penjemputan_type" id="penjemputan_existing" value="existing" {{ count($alamatPenjemputan) > 0 ? 'checked' : 'disabled' }}>
                                                <label class="form-check-label w-100 cursor-pointer d-flex align-items-center" for="penjemputan_existing">
                                                    <i class="fas fa-address-book fs-4 text-primary me-2"></i>
                                                    <div>
                                                        <span class="d-block">Gunakan alamat tersimpan</span>
                                                        <small class="text-muted">{{ count($alamatPenjemputan) }} alamat tersedia</small>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check custom-radio p-3 border rounded-3 h-100 position-relative" data-bs-toggle="tooltip" data-bs-placement="top" title="Tambah alamat baru">
                                                <input class="form-check-input" type="radio" name="alamat_penjemputan_type" id="penjemputan_new" value="new" {{ count($alamatPenjemputan) == 0 ? 'checked' : '' }}>
                                                <label class="form-check-label w-100 cursor-pointer d-flex align-items-center" for="penjemputan_new">
                                                    <i class="fas fa-plus-circle fs-4 text-success me-2"></i>
                                                    <div>
                                                        <span class="d-block">Buat alamat baru</span>
                                                        <small class="text-muted">Tambah alamat penjemputan</small>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Existing Alamat Penjemputan -->
                            <div id="existing_penjemputan" class="mt-3 animate__animated animate__fadeIn" style="{{ count($alamatPenjemputan) > 0 ? '' : 'display: none;' }}">
                                <div class="mb-3 position-relative">
                                    <label for="id_alamat_penjemputan" class="form-label d-flex align-items-center">
                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                        Pilih Alamat Penjemputan
                                        <div class="ms-2" data-bs-toggle="tooltip" data-bs-placement="right" title="Pilih dari daftar alamat yang tersimpan">
                                            <i class="fas fa-info-circle text-muted"></i>
                                        </div>
                                    </label>
                                    <select class="form-select form-select-lg border-0 shadow-sm" id="id_alamat_penjemputan" name="id_alamat_penjemputan">
                                        <option value="">Pilih alamat penjemputan...</option>
                                        @foreach($alamatPenjemputan as $alamat)
                                        <option value="{{ $alamat->uid }}" data-kecamatan="{{ $alamat->kecamatan }}">
                                            {{ $alamat->nama_pengirim }} - {{ $alamat->kecamatan }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Silakan pilih alamat penjemputan
                                    </div>
                                </div>
                                <div id="detail_penjemputan" class="alert alert-info border-0 shadow-sm animate__animated animate__fadeIn" style="display: none;">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <div class="flex-grow-1">
                                            <!-- Detail alamat penjemputan akan dimuat disini -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- New Alamat Penjemputan -->
                            <div id="new_penjemputan" class="mt-3 animate__animated animate__fadeIn" style="{{ count($alamatPenjemputan) == 0 ? '' : 'display: none;' }}">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="rounded-circle bg-warning-soft p-3 me-3">
                                                <i class="fas fa-lightbulb text-warning fs-4"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Buat Alamat Penjemputan Baru</h6>
                                                <p class="mb-0 text-muted">Anda akan diarahkan ke halaman pembuatan alamat penjemputan baru.</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('alamat-penjemputan.create') }}" class="btn btn-primary btn-lg w-100 position-relative overflow-hidden">
                                            <span class="btn-animation-wrapper">
                                                <i class="fas fa-plus me-2"></i> Buat Alamat Penjemputan
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Alamat Tujuan Section -->
                        <div class="mb-4 section-fade">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px;">2</div>
                                <h5 class="text-primary mb-0">Alamat Tujuan</h5>
                            </div>
                            <div class="card border-0 shadow-sm rounded-3 mb-3">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-check custom-radio p-3 border rounded-3 h-100 position-relative" data-bs-toggle="tooltip" data-bs-placement="top" title="Pilih dari alamat tersimpan">
                                                <input class="form-check-input" type="radio" name="alamat_tujuan_type" id="tujuan_existing" value="existing" {{ count($alamatTujuan) > 0 ? 'checked' : 'disabled' }}>
                                                <label class="form-check-label w-100 cursor-pointer d-flex align-items-center" for="tujuan_existing">
                                                    <i class="fas fa-address-book fs-4 text-primary me-2"></i>
                                                    <div>
                                                        <span class="d-block">Gunakan alamat tersimpan</span>
                                                        <small class="text-muted">{{ count($alamatTujuan) }} alamat tersedia</small>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check custom-radio p-3 border rounded-3 h-100 position-relative" data-bs-toggle="tooltip" data-bs-placement="top" title="Tambah alamat baru">
                                                <input class="form-check-input" type="radio" name="alamat_tujuan_type" id="tujuan_new" value="new" {{ count($alamatTujuan) == 0 ? 'checked' : '' }}>
                                                <label class="form-check-label w-100 cursor-pointer d-flex align-items-center" for="tujuan_new">
                                                    <i class="fas fa-plus-circle fs-4 text-success me-2"></i>
                                                    <div>
                                                        <span class="d-block">Buat alamat baru</span>
                                                        <small class="text-muted">Tambah alamat tujuan</small>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Existing Alamat Tujuan -->
                            <div id="existing_tujuan" class="mt-3 animate__animated animate__fadeIn" style="{{ count($alamatTujuan) > 0 ? '' : 'display: none;' }}">
                                <div class="mb-3 position-relative">
                                    <label for="id_alamat_tujuan" class="form-label d-flex align-items-center">
                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                        Pilih Alamat Tujuan
                                        <div class="ms-2" data-bs-toggle="tooltip" data-bs-placement="right" title="Pilih dari daftar alamat yang tersimpan">
                                            <i class="fas fa-info-circle text-muted"></i>
                                        </div>
                                    </label>
                                    <select class="form-select form-select-lg border-0 shadow-sm" id="id_alamat_tujuan" name="id_alamat_tujuan">
                                        <option value="">Pilih alamat tujuan...</option>
                                        @foreach($alamatTujuan as $alamat)
                                        <option value="{{ $alamat->uid }}" data-kecamatan="{{ $alamat->kecamatan }}">
                                            {{ $alamat->nama_penerima }} - {{ $alamat->kecamatan }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Silakan pilih alamat tujuan
                                    </div>
                                </div>
                                <div id="detail_tujuan" class="alert alert-info border-0 shadow-sm animate__animated animate__fadeIn" style="display: none;">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <div class="flex-grow-1">
                                            <!-- Detail alamat tujuan akan dimuat disini -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- New Alamat Tujuan -->
                            <div id="new_tujuan" class="mt-3 animate__animated animate__fadeIn" style="{{ count($alamatTujuan) == 0 ? '' : 'display: none;' }}">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="rounded-circle bg-warning-soft p-3 me-3">
                                                <i class="fas fa-lightbulb text-warning fs-4"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Buat Alamat Tujuan Baru</h6>
                                                <p class="mb-0 text-muted">Anda akan diarahkan ke halaman pembuatan alamat tujuan baru.</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('alamat-tujuan.create') }}" class="btn btn-primary btn-lg w-100 position-relative overflow-hidden">
                                            <span class="btn-animation-wrapper">
                                                <i class="fas fa-plus me-2"></i> Buat Alamat Tujuan
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Layanan dan Zona Section -->
                        <div class="mb-4 section-fade">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px;">3</div>
                                <h5 class="text-primary mb-0">Layanan dan Zona Pengiriman</h5>
                            </div>
                            
                            <!-- Layanan Paket -->
                            <div class="card border-0 shadow-sm rounded-3 mb-3">
                                <div class="card-body p-4">
                                    <div class="mb-4">
                                        <label for="id_layanan" class="form-label d-flex align-items-center">
                                            <i class="fas fa-truck text-primary me-2"></i>
                                            Layanan Paket
                                            <span class="text-danger ms-1">*</span>
                                            <div class="ms-2" data-bs-toggle="tooltip" data-bs-placement="right" title="Pilih layanan pengiriman yang sesuai dengan kebutuhan Anda">
                                                <i class="fas fa-info-circle text-muted"></i>
                                            </div>
                                        </label>
                                        <select class="form-select form-select-lg border-0 shadow-sm" id="id_layanan" name="id_layanan" required>
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
                                        <div class="invalid-feedback">
                                            Silakan pilih layanan paket
                                        </div>
                                        <div id="layanan_detail" class="alert alert-info border-0 shadow-sm mt-3 animate__animated animate__fadeIn" style="display: none;">
                                            <div class="d-flex">
                                                <i class="fas fa-info-circle mt-1 me-2"></i>
                                                <div class="flex-grow-1">
                                                    <!-- Detail layanan akan dimuat disini -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                            <!-- Kecamatan Zona -->
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="position-relative">
                                                <label for="kecamatan_asal" class="form-label d-flex align-items-center">
                                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                    Kecamatan Asal
                                                    <span class="text-danger ms-1">*</span>
                                                    <div class="ms-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Akan otomatis terisi berdasarkan alamat penjemputan">
                                                        <i class="fas fa-info-circle text-muted"></i>
                                                    </div>
                                                </label>
                                                <select class="form-select form-select-lg border-0 shadow-sm" id="kecamatan_asal" required disabled>
                                                    <option value="">kecamatan asal...</option>
                                                    @foreach($kecamatanAsal as $kecamatan)
                                                    <option value="{{ $kecamatan }}">{{ $kecamatan }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="kecamatan_asal" id="kecamatan_asal_hidden">
                                                <div class="invalid-feedback">
                                                    kecamatan asal
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative">
                                                <label for="kecamatan_tujuan" class="form-label d-flex align-items-center">
                                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                    Kecamatan Tujuan
                                                    <span class="text-danger ms-1">*</span>
                                                    <div class="ms-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Akan otomatis terisi berdasarkan alamat tujuan">
                                                        <i class="fas fa-info-circle text-muted"></i>
                                                    </div>
                                                </label>
                                                <select class="form-select form-select-lg border-0 shadow-sm" id="kecamatan_tujuan" name="kecamatan_tujuan" required disabled>
                                                    <option value="">kecamatan tujuan...</option>
                                                    @foreach($kecamatanTujuan as $kecamatan)
                                                    <option value="{{ $kecamatan }}">{{ $kecamatan }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="kecamatan_tujuan" id="kecamatan_tujuan_hidden">
                                                
                                                <div class="invalid-feedback">
                                                    Silakan pilih kecamatan tujuan
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Pengiriman Section -->
                        <div class="mb-4 section-fade">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px;">4</div>
                                <h5 class="text-primary mb-0">Detail Pengiriman</h5>
                            </div>
                            
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-body p-4">
                                    <div class="mb-4">
                                        <label for="catatan_opsional" class="form-label d-flex align-items-center">
                                            <i class="fas fa-comment-alt text-primary me-2"></i>
                                            Catatan Pengiriman
                                            <div class="ms-2" data-bs-toggle="tooltip" data-bs-placement="right" title="Tambahkan catatan khusus untuk pengiriman ini">
                                                <i class="fas fa-info-circle text-muted"></i>
                                            </div>
                                        </label>
                                        <textarea class="form-control border-0 shadow-sm" id="catatan_opsional" name="catatan_opsional" rows="3" placeholder="Contoh: Barang mudah pecah, harap ditangani dengan hati-hati" required></textarea>
                                        <small class="text-muted mt-2 d-block">Opsional - Tambahkan instruksi khusus untuk kurir</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="foto_barang" class="form-label d-flex align-items-center">
                                            <i class="fas fa-camera text-primary me-2"></i>
                                            Foto Barang
                                            <div class="ms-2" data-bs-toggle="tooltip" data-bs-placement="right" title="Upload foto barang untuk dokumentasi">
                                                <i class="fas fa-info-circle text-muted"></i>
                                            </div>
                                        </label>
                                        <div class="custom-file-upload position-relative">
                                            <input type="file" class="form-control form-control-lg border-0 shadow-sm" id="foto_barang" name="foto_barang" accept="image/*" required>
                                            <div class="upload-icon position-absolute top-50 start-0 translate-middle-y ms-3 " >
                                                <i class="fas fa-cloud-upload-alt text-primary"></i>
                                            </div>
                                        </div>
                                        <small class="text-muted mt-2 d-block">Opsional - Format yang didukung: JPG, PNG (Maks. 2MB)</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('dashboard.pengirim') }}" class="btn btn-light btn-lg px-4 me-2 d-flex align-items-center">
                                <i class="fas fa-arrow-left me-2"></i>
                Kembali
            </a>
            <button type="submit" class="btn btn-primary btn-lg px-4 position-relative" id="submitBtn">
                <span class="submit-text">
                    <i class="fas fa-paper-plane me-2"></i> Buat Pengiriman
                </span>
                <span class="submit-loader d-none">
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Memproses...
                </span>
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
            $('#kecamatan_asal_hidden').val(kecamatan);
            
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
        } else {
            $('#detail_penjemputan').hide();
            $('#kecamatan_asal').val('');
            $('#kecamatan_asal_hidden').val('');
        }
        
        updateKecamatanTujuan();
        calculateBiaya();
        calculateBiaya();
    });

    // Load alamat tujuan detail
    $('#id_alamat_tujuan').change(function() {
        const id = $(this).val();
        const kecamatan = $(this).find(':selected').data('kecamatan');
        
        if (id) {
            // Set kecamatan tujuan
            $('#kecamatan_tujuan').val(kecamatan);
            $('#kecamatan_tujuan_hidden').val(kecamatan);
            
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
             $('#kecamatan_tujuan_hidden').val('');
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

                if (zona && zona.layanan && zona.asal && zona.tujuan) {
                    $('#summary_layanan').text(zona.layanan);
                    $('#summary_rute').text(`${zona.asal} → ${zona.tujuan}`);
                    const biayaTambahan = Number(zona.biaya_zona) || 0;
                    const hargaDasar = Number(zona.harga_dasar) || 0;
                    const total = biayaTambahan + hargaDasar;
                    $('#summary_total').text(`Rp ${total.toLocaleString('id-ID')}`);
                    $('#biaya_placeholder').hide();
                    $('#biaya_summary').show();
                } else {
                    $('#biaya_summary').hide();
                    $('#biaya_placeholder').show().html(
                        `<span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>Lokasi tidak tersedia</span>`
                    );
                }
            })
            .catch(error => {
                $('#biaya_summary').hide();
                $('#biaya_placeholder').show().html(
                    `<span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>Lokasi tidak tersedia</span>`
                );
                console.error('Error calculating biaya:', error);
            });
        } else {
            $('#biaya_summary').hide();
            $('#biaya_placeholder').show().html(
                `<i class="fas fa-info-circle me-1"></i>Lengkapi form untuk melihat estimasi biaya`
            );
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