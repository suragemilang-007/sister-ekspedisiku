@extends('layouts.kurir')

@section('title', 'Detail Tugas Kurir')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Detail Tugas</h1>
            <p class="text-muted">Informasi lengkap tentang tugas pengiriman</p>
        </div>
        <div>
            <a href="/kurir/dashboard" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Detail Pengiriman -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Pengiriman</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Nomor Resi</div>
                        <div class="col-md-8">{{ $penugasan->pengiriman->nomor_resi }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Status Pengiriman</div>
                        <div class="col-md-8">
                            <span class="badge bg-{{ $penugasan->pengiriman->status_color }} text-dark rounded-pill">
                                {{ $penugasan->pengiriman->status }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Status Tugas</div>
                        <div class="col-md-8">
                            <span class="badge bg-primary text-white rounded-pill">
                                {{ $penugasan->status }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Tanggal Dibuat</div>
                        <div class="col-md-8">{{ $penugasan->created_at->format('d M Y H:i') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Terakhir Diupdate</div>
                        <div class="col-md-8">{{ $penugasan->updated_at ? $penugasan->updated_at->format('d M Y H:i') : '-' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Catatan</div>
                        <div class="col-md-8">{{ $penugasan->catatan ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <!-- Informasi Alamat -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-primary-light">
                            <h5 class="mb-0">Alamat Penjemputan</h5>
                        </div>
                        <div class="card-body">
                            <p class="fw-bold mb-1">{{ $penugasan->pengiriman->alamatPenjemputan->nama_pengirim }}</p>
                            <p class="mb-1">{{ $penugasan->pengiriman->alamatPenjemputan->no_hp }}</p>
                            <p class="mb-1">{{ $penugasan->pengiriman->alamatPenjemputan->alamat_lengkap }}</p>
                            <p class="mb-1">{{ $penugasan->pengiriman->alamatPenjemputan->kecamatan }}, {{ $penugasan->pengiriman->alamatPenjemputan->kode_pos }}</p>
                            @if($penugasan->pengiriman->alamatPenjemputan->keterangan_alamat)
                            <p class="text-muted mt-2">Catatan: {{ $penugasan->pengiriman->alamatPenjemputan->keterangan_alamat }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-success-light">
                            <h5 class="mb-0">Alamat Tujuan</h5>
                        </div>
                        <div class="card-body">
                            <p class="fw-bold mb-1">{{ $penugasan->pengiriman->alamatTujuan->nama_penerima }}</p>
                            <p class="mb-1">{{ $penugasan->pengiriman->alamatTujuan->no_hp }}</p>
                            <p class="mb-1">{{ $penugasan->pengiriman->alamatTujuan->alamat_lengkap }}</p>
                            <p class="mb-1">{{ $penugasan->pengiriman->alamatTujuan->kecamatan }}, {{ $penugasan->pengiriman->alamatTujuan->kode_pos }}</p>
                            @if($penugasan->pengiriman->alamatTujuan->keterangan_alamat)
                            <p class="text-muted mt-2">Catatan: {{ $penugasan->pengiriman->alamatTujuan->keterangan_alamat }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Status -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Update Status</h5>
                </div>
                <div class="card-body">
                    <form id="updateStatusForm">
                        <input type="hidden" id="id_penugasan" value="{{ $penugasan->id_penugasan }}">

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="" disabled selected>Pilih Status</option>
                                <option value="MENUJU PENGIRIM" {{ $penugasan->status == 'MENUJU PENGIRIM' ? 'selected' : '' }}>Menuju Pengirim</option>
                                <option value="DITERIMA KURIRI" {{ $penugasan->status == 'DITERIMA KURIRI' ? 'selected' : '' }}>Diterima Kurir</option>
                                <option value="DIANTAR" {{ $penugasan->status == 'DIANTAR' ? 'selected' : '' }}>Sedang Diantar</option>
                                <option value="SELESAI" {{ $penugasan->status == 'SELESAI' ? 'selected' : '' }}>Selesai</option>
                                <option value="DIBATALKAN" {{ $penugasan->status == 'DIBATALKAN' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="3">{{ $penugasan->catatan }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Riwayat Status -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Riwayat Status</h5>
                </div>
                <div class="card-body p-0">
                    <div class="timeline p-3">
                        @foreach($penugasan->pengiriman->pelacakan as $pelacakan)
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="mb-0">{{ $pelacakan->status }}</h6>
                                <p class="text-muted mb-0 small">{{ $pelacakan->created_at->format('d M Y H:i') }}</p>
                                <p class="mb-0">{{ $pelacakan->keterangan }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
        border-left: 2px solid #e9ecef;
        padding-left: 20px;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-marker {
        position: absolute;
        left: -9px;
        top: 0;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: var(--accent-blue);
        border: 3px solid white;
    }

    .bg-primary-light {
        background-color: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }

    .bg-success-light {
        background-color: rgba(25, 135, 84, 0.1);
        color: #198754;
    }
</style>
@endsection

@section('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<!-- Custom JS -->
<script src="/js/kurir-dashboard.js"></script>
@endsection