@extends('layouts.app')

@section('title', 'Daftar Alamat Tujuan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">Daftar Alamat Tujuan</h1>
                    <p class="text-muted">Kelola alamat tujuan pengiriman Anda</p>
                </div>
                <a href="{{ route('alamat-tujuan.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah Alamat Baru
                </a>
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

            <!-- Main Content Card -->
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-map-marker-alt me-2"></i>Alamat Tujuan Tersimpan
                            </h6>
                        </div>
                        <div class="col-auto">
                            <span class="badge bg-info">{{ $alamatTujuan->count() }} Alamat</span>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    @if($alamatTujuan->count() > 0)
                        <!-- Desktop View -->
                        <div class="table-responsive d-none d-md-block">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="20%">Nama Penerima</th>
                                        <th width="15%">No. HP</th>
                                        <th width="30%">Alamat</th>
                                        <th width="15%">Kecamatan</th>
                                        <th width="10%">Kode Pos</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($alamatTujuan as $index => $alamat)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary-light rounded-circle d-flex align-items-center justify-content-center me-3">
                                                        <i class="fas fa-user text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $alamat->nama_penerima }}</h6>
                                                        <small class="text-muted">
                                                            <i class="fas fa-calendar-alt me-1"></i>
                                                            {{ $alamat->created_at ? $alamat->created_at->format('d M Y') : '-' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-success-light text-success">
                                                    <i class="fas fa-phone me-1"></i>{{ $alamat->no_hp }}
                                                </span>
                                                
                                            </td>
                                            <td>
                                                <div style="max-width: 200px;" title="{{ $alamat->alamat_lengkap }}">
                                                    {{ $alamat->alamat_lengkap }}
                                                </div>
                                                @if($alamat->keterangan_alamat)
                                                    <br><small class="text-muted"> {{ $alamat->keterangan_alamat?? '-' }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $alamat->kecamatan }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $alamat->kode_pos }}</span>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('alamat-tujuan.show', $alamat->id_alamat_tujuan) }}">
                                                                <i class="fas fa-eye me-2"></i>Lihat Detail
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('alamat-tujuan.edit', $alamat->id_alamat_tujuan) }}">
                                                                <i class="fas fa-edit me-2"></i>Edit
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <button class="dropdown-item text-danger" onclick="confirmDelete({{ $alamat->id_alamat_tujuan }})">
                                                                <i class="fas fa-trash me-2"></i>Hapus
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile View -->
                        <div class="d-block d-md-none">
                            @foreach($alamatTujuan as $alamat)
                                <div class="card mb-3 border-left-primary">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0 text-primary">{{ $alamat->nama_penerima }}</h6>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('alamat-tujuan.show', $alamat->id_alamat_tujuan) }}">
                                                            <i class="fas fa-eye me-2"></i>Lihat Detail
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('alamat-tujuan.edit', $alamat->id_alamat_tujuan) }}">
                                                            <i class="fas fa-edit me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <button class="dropdown-item text-danger" onclick="confirmDelete({{ $alamat->id_alamat_tujuan }})">
                                                            <i class="fas fa-trash me-2"></i>Hapus
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <small class="text-muted d-block">No. HP</small>
                                                <span class="badge bg-success">{{ $alamat->no_hp }}</span>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">Kode Pos</small>
                                                <span class="badge bg-info">{{ $alamat->kode_pos }}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <small class="text-muted d-block">Alamat Lengkap</small>
                                            <p class="mb-1">{{ $alamat->alamat_lengkap }}</p>
                                            <small class="text-muted">{{ $alamat->kecamatan }}</small>
                                        </div>
                                        
                                        @if($alamat->telepon)
                                            <div class="mb-2">
                                                <small class="text-muted d-block">Telepon</small>
                                                <span>{{ $alamat->telepon }}</span>
                                            </div>
                                        @endif
                                        
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            Dibuat: {{ $alamat->created_at ? $alamat->created_at->format('d M Y H:i') : '-' }}
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-map-marker-alt fa-4x text-gray-400"></i>
                            </div>
                            <h5 class="text-gray-600 mb-3">Belum Ada Alamat Tujuan</h5>
                            <p class="text-muted mb-4">
                                Anda belum memiliki alamat tujuan tersimpan.<br>
                                Tambahkan alamat tujuan untuk memudahkan pengiriman paket.
                            </p>
                            <a href="{{ route('alamat-tujuan.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tambah Alamat Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    
    .bg-primary-light {
        background-color: rgba(78, 115, 223, 0.1);
    }
    
    .bg-success-light {
        background-color: rgba(28, 200, 138, 0.1);
    }
    
    .text-success {
        color: #1cc88a !important;
    }
    
    .avatar-sm {
        width: 40px;
        height: 40px;
    }
    
    .table-responsive {
        border-radius: 0.35rem;
    }
    
    .dropdown-toggle::after {
        display: none;
    }
    
    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        border: 1px solid #e3e6f0;
    }
    
    .table th {
        background-color: #f8f9fc;
        border-top: none;
        font-weight: 600;
        font-size: 0.85rem;
        color: #5a5c69;
    }
    
    .badge {
        font-size: 0.75rem;
    }
    
    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
        title: 'Hapus alamat?',
        text: 'Data yang dihapus tidak dapat dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/alamat-tujuan/delete/${id}`)
                .then(() => {
                    Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success')
                        .then(() => location.reload());
                })
                .catch(() => {
                    Swal.fire('Gagal', 'Gagal menghapus data.', 'error');
                });
        }
    });
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