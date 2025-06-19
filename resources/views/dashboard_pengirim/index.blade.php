@extends('layouts.app')

@section('title', 'Dashboard Pengirim')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Dashboard Pengirim</h1>
            <p class="text-muted">Selamat datang kembali, {{ Session::get('user_name') }}!</p>
        </div>
        <a href="/dashboard/pengirim/kirim" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Kirim Paket Baru
        </a>
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
                            <h6 class="fw-medium mb-1">Total Pengiriman</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['total_pengiriman'] ?? 0 }}</h3>
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
                            <i class="fas fa-truck fa-2x text-success"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="fw-medium mb-1">Pengiriman Aktif</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['pengiriman_aktif'] ?? 0 }}</h3>
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
                            <i class="fas fa-star fa-2x text-warning"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="fw-medium mb-1">Rating Rata-rata</h6>
                            <h3 class="fw-bold mb-0">{{ number_format($stats['rating_avg'] ?? 0, 1) }}</h3>
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
                            <i class="fas fa-bell fa-2x text-danger"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="fw-medium mb-1">Pengiriman Selesai</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['pengiriman_selesai'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Shipments -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Pengiriman Terbaru</h5>
            <a href="/dashboard/pengirim/history" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="card-body">
            @if(isset($recent_shipments) && count($recent_shipments) > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>No. Resi</th>
                                <th>Tujuan</th>
                                <th>Penerima</th>
                                <th>Status</th>
                                <th>Tanggal Kirim</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_shipments as $shipment)
                                <tr class="text-dark">
                                    <td class="fw-medium">{{ $shipment->nomor_resi }}</td>
                                    <td class="text-dark">{{ $shipment->alamatTujuan->alamat_lengkap ?? '-' }},
                                        {{ $shipment->alamatTujuan->kecamatan ?? '' }},
                                        {{ $shipment->alamatTujuan->kode_pos ?? '' }}
                                    </td>
                                    <td class="text-dark">
                                        {{ $shipment->alamatTujuan->nama_penerima ?? '-' }},
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $shipment->status_color }} text-dark rounded-pill">
                                            {{ $shipment->status }}
                                        </span>
                                    </td>
                                    <td>{{ $shipment->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="/dashboard/pengirim/lacak/{{ $shipment->no_resi }}" 
                                           class="btn btn-sm btn-outline-primary me-2"
                                           data-bs-toggle="tooltip"
                                           title="Lacak Paket">
                                            <i class="fas fa-search"></i>
                                        </a>
                                        <a href="/dashboard/pengirim/detail/{{ $shipment->id }}" 
                                           class="btn btn-sm btn-outline-secondary"
                                           data-bs-toggle="tooltip"
                                           title="Detail Pengiriman">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ $shipment->status === 'DIPROSES' ? url('/dashboard/pengirim/batal/'.$shipment->id) : '#' }}" 
                                        class="btn btn-sm btn-outline-secondary {{ $shipment->status !== 'DIPROSES' ? 'disabled' : '' }}"
                                        data-bs-toggle="tooltip"
                                        title="Batal">
                                            <i class="fas fa-cancel"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-box-open mb-3"></i>
                    <h5 class="fw-medium">Belum Ada Pengiriman</h5>
                    <p class="text-muted mb-3">Anda belum memiliki riwayat pengiriman. Mulai kirim paket sekarang!</p>
                    <a href="/dashboard/pengirim/kirim" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Kirim Paket
                    </a>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection