@extends('layouts.admin')

@section('title', 'Dashboard Kurir')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Dashboard Kurir</h1>
            <p class="text-muted">Selamat datang kembali, {{ Session::get('user_name') }}!</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary-light rounded-3 p-3">
                            <i class="fas fa-box fa-2x text-primary"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="fw-medium mb-1">Total Tugas</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['total_tugas'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-warning bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-truck fa-2x text-warning"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="fw-medium mb-1">Sedang Dikirim</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['sedang_dikirim'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="fw-medium mb-1">Selesai</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['selesai'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-danger bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-times-circle fa-2x text-danger"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="fw-medium mb-1">Dibatalkan</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['dibatalkan'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tugas Terbaru -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Tugas Terbaru</h5>
            <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-primary">Refresh</a>
        </div>
        <div class="card-body">
            @if (isset($tugas_terbaru) && count($tugas_terbaru) > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID Penugasan</th>
                            <th>Nomor Resi</th>
                            <th>Alamat Tujuan</th>
                            <th>Tanggal Tugas</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tugas_terbaru as $tugas)
                        <tr class="text-dark">
                            <td class="fw-medium">{{ $tugas->id_penugasan }}</td>
                            <td class="text-dark">{{ $tugas->pengiriman->nomor_resi }}</td>
                            <td class="text-dark">
                                {{ $tugas->pengiriman->alamatTujuan->alamat_lengkap }}
                            </td>
                            <td class="text-dark">{{ $tugas->created_at->format('d M Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $tugas->pengiriman->status_color }} text-dark rounded-pill">
                                    {{ $tugas->status }}
                                </span>
                            </td>
                            <td>
                                <a href="/kurir/detail/{{ $tugas->id_penugasan }}"
                                    class="btn btn-sm btn-outline-primary me-2"
                                    data-bs-toggle="tooltip"
                                    title="Detail Tugas">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/kurir/update/{{ $tugas->id_penugasan }}"
                                    class="btn btn-sm btn-outline-success"
                                    data-bs-toggle="tooltip"
                                    title="Update Status">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state text-center py-5">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <h5 class="fw-medium">Belum Ada Tugas</h5>
                <p class="text-muted mb-3">Anda belum memiliki tugas pengiriman saat ini.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endsection