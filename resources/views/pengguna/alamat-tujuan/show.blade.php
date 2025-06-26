@extends('layouts.app')

@section('title', 'Detail Alamat Tujuan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('alamat-tujuan.index') }}" class="text-decoration-none">
                            <i class="fas fa-map-marker-alt me-1"></i>Alamat Tujuan
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">Detail Alamat Tujuan</h1>
                    <p class="text-muted">Informasi lengkap alamat tujuan pengiriman</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('alamat-tujuan.edit', $alamatTujuan->id_alamat_tujuan) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                    <a href="{{ route('alamat-tujuan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Main Information Card -->
                <div class="col-lg-8">
                    <div class="card shadow mb-4">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="fas fa-user text-primary"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $alamatTujuan->nama_penerima }}</h5>
                                    <small class="text-white-50">Penerima Paket</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Contact Information -->
                                <div class="col-md-6 mb-4">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-phone me-2"></i>Informasi Kontak
                                    </h6>
                                    <div class="info-item mb-3">
                                        <label class="text-muted small">Nomor HP</label>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-success me-2">
                                                <i class="fas fa-mobile-alt"></i>
                                            </span>
                                            <span class="fw-bold">{{ $alamatTujuan->no_hp }}</span>
                                            <button class="btn btn-sm btn-outline-primary ms-2" onclick="copyToClipboard('{{ $alamatTujuan->no_hp }}')">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    @if($alamatTujuan->telepon)
                                        <div class="info-item mb-3">
                                            <label class="text-muted small">Telepon</label>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-info me-2">
                                                    <i class="fas fa-phone"></i>
                                                </span>
                                                <span class="fw-bold">{{ $alamatTujuan->telepon }}</span>
                                                <button class="btn btn-sm btn-outline-primary ms-2" onclick="copyToClipboard('{{ $alamatTujuan->telepon }}')">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Location Information -->
                                <div class="col-md-6 mb-4">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-map-marked-alt me-2"></i>Informasi Lokasi
                                    </h6>
                                    <div class="info-item mb-3">
                                        <label class="text-muted small">Kecamatan</label>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-secondary me-2">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </span>
                                            <span class="fw-bold">{{ $alamatTujuan->kecamatan }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item mb-3">
                                        <label class="text-muted small">Kode Pos</label>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-warning me-2">
                                                <i class="fas fa-mailbox"></i>
                                            </span>
                                            <span class="fw-bold">{{ $alamatTujuan->kode_pos }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Full Address -->
                            <div class="mb-4">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-home me-2"></i>Alamat Lengkap
                                </h6>
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-0 lh-lg">{{ $alamatTujuan->alamat_lengkap }}</p>
                                </div>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-outline-primary" onclick="copyToClipboard('{{ $alamatTujuan->alamat_lengkap }}')">
                                        <i class="fas fa-copy me-1"></i>Salin Alamat
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" onclick="openMaps()">
                                        <i class="fas fa-map me-1"></i>Buka di Maps
                                    </button>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('alamat-tujuan.edit', $alamatTujuan->id_alamat_tujuan) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-2"></i>Edit Alamat
                                </a>
                                <button class="btn btn-danger" onclick="confirmDelete()">
                                    <i class="fas fa-trash me-2"></i>Hapus Alamat
                                </button>
                                <button class="btn btn-info" onclick="printAddress()">
                                    <i class="fas fa-print me-2"></i>Cetak
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Information -->
                <div class="col-lg-4">
                    <!-- Quick Info Card -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Informasi Tambahan
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="info-item mb-3">
                                <label class="text-muted small">Tanggal Dibuat</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                                    <span>{{ $alamatTujuan->created_at ? $alamatTujuan->created_at->format('d F Y') : '-' }}</span>
                                </div>
                                @if($alamatTujuan->created_at)
                                    <small class="text-muted">{{ $alamatTujuan->created_at->format('H:i') }} WIB</small>
                                @endif
                            </div>

                            <div class="info-item mb-3">
                                <label class="text-muted small">Status</label>
                                <div>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>Aktif
                                    </span>
                                </div>
                            </div>

                            <!-- Usage Statistics -->
                            <div class="info-item">
                                <label class="text-muted small">Penggunaan</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-shipping-fast text-info me-2"></i>
                                    <span>{{ $alamatTujuan->pengiriman->count() }} kali digunakan</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions Card -->
                    <div class="card shadow">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-bolt me-2"></i>Aksi Cepat
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                
                                <a href="{{ route('alamat-tujuan.create') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-plus me-2"></i>Tambah Alamat Baru
                                </a>
                                <a href="{{ route('alamat-tujuan.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-list me-2"></i>Lihat Semua Alamat
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h6>Apakah Anda yakin ingin menghapus alamat tujuan ini?</h6>
                    <p class="text-muted mb-2">
                        <strong>{{ $alamatTujuan->nama_penerima }}</strong><br>
                        {{ $alamatTujuan->alamat_lengkap }}
                    </p>
                    <p class="text-danger small mb-0">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form method="POST" action="{{ route('alamat-tujuan.destroy', $alamatTujuan->id_alamat_tujuan) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="copyToast" class="toast" role="alert">
        <div class="toast-header">
            <i class="fas fa-check-circle text-success me-2"></i>
            <strong class="me-auto">Berhasil</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            Teks berhasil disalin ke clipboard!
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .info-item {
        border-bottom: 1px solid #f8f9fc;
        padding-bottom: 0.75rem;
    }
    
    .info-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .info-item label {
        font-weight: 600;
        display: block;
        margin-bottom: 0.5rem;
    }
    
    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        border: 1px solid #e3e6f0;
    }
    
    .badge {
        font-size: 0.75rem;
    }
    
    .bg-light {
        background-color: #f8f9fc !important;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        content: ">";
    }
    
    .breadcrumb-item a {
        color: #5a5c69;
    }
    
    .breadcrumb-item a:hover {
        color: #3a3b45;
    }
    
    .lh-lg {
        line-height: 1.8;
    }
    
    @media print {
        .btn, .breadcrumb, .toast-container {
            display: none !important;
        }
        
        .card {
            border: 1px solid #000 !important;
            box-shadow: none !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function confirmDelete() {
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }
    
    function copyToClipboard(text) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function() {
                const toast = new bootstrap.Toast(document.getElementById('copyToast'));
                toast.show();
            }).catch(function() {
                fallbackCopyTextToClipboard(text);
            });
        } else {
            fallbackCopyTextToClipboard(text);
        }
    }

    function fallbackCopyTextToClipboard(text) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        try {
            document.execCommand('copy');
        } catch (err) {
            // Optionally handle error
        }
        document.body.removeChild(textArea);

        const toast = new bootstrap.Toast(document.getElementById('copyToast'));
        toast.show();
    }
    
    function openMaps() {
        const address = encodeURIComponent('{{ $alamatTujuan->alamat_lengkap }}, {{ $alamatTujuan->kecamatan }}');
        const url = `https://www.google.com/maps/search/?api=1&query=${address}`;
        window.open(url, '_blank');
    }
    
    function printAddress() {
        window.print();
    }
    
    // Auto hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    });
</script>
@endpush