@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold mb-1">Dashboard Admin</h1>
                <p class="text-muted">Selamat datang kembali, {{ Session::get('user_name') }}!</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card">
                    <a href="{{ route('admin.pesanan.baru.index') }}" class="card-link-overlay text-decoration-none text-dark">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-primary-light rounded-3 p-3">
                                    <i class="fas fa-box fa-2x text-primary"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="fw-medium mb-1">Pesanan Baru</h6>
                                    <h3 class="fw-bold mb-0">{{ $stats['pengiriman_baru'] ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                    </a>
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
                                <h6 class="fw-medium mb-1">Total Kurir</h6>
                                <h3 class="fw-bold mb-0">{{ $stats['total_kurir'] ?? 0 }}</h3>
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
                                <i class="fas fa-square-check fa-2x text-warning"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="fw-medium mb-1">Total Sukses</h6>
                                <h3 class="fw-bold mb-0">{{ $stats['pengiriman_selesai'] ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    {{-- Bungkus seluruh card-body dengan tag <a> --}}
                    <a href="{{ route('admin.pengguna.list') }}" class="card-link-overlay text-decoration-none text-dark">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-danger bg-opacity-10 rounded-3 p-3">
                                    <i class="fas fa-users fa-2x text-danger"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="fw-medium mb-1">Total Admin</h6>
                                    <h3 class="fw-bold mb-0">{{ $stats['jumlah_admin'] ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Shipments -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Pengiriman Terbaru</h5>
                <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-primary">Refresh</a>
            </div>
            <div class="card-body">
                @if (isset($recent_shipments) && count($recent_shipments) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>No Resi</th>
                                    <th>Tanggal</th>
                                    <th>Pengirim</th>
                                    <th>Penerima</th>
                                    <th>Kurir</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recent_shipments as $shipment)
                                    <tr class="text-dark">
                                        <td class="fw-medium">{{ $shipment->nomor_resi }}</td>
                                        <td class="text-dark">{{ $shipment->created_at->format('d M Y') }}</td>
                                        <td class="text-dark">
                                            {{ $shipment->alamatPenjemputan->nama_pengirim ?? '-' }}
                                        </td>
                                        <td class="text-dark">
                                            {{ $shipment->alamatTujuan->nama_penerima ?? '-' }}
                                        </td>
                                        <td class="text-dark">
                                            {{ $shipment->kurir->nama ?? '-' }}
                                        <td>
                                            <span class="badge bg-{{ $shipment->status_color }} text-dark rounded-pill">
                                                {{ $shipment->status }}
                                            </span>
                                        </td>
                                        <!--
                                            <td>
                                                <a href="/dashboard/admin/lacak/{{ $shipment->no_resi }}"
                                                   class="btn btn-sm btn-outline-primary me-2"
                                                   data-bs-toggle="tooltip"
                                                   title="Lacak Paket">
                                                    <i class="fas fa-search"></i>
                                                </a>
                                                <a href="/dashboard/admin/detail/{{ $shipment->id }}"
                                                   class="btn btn-sm btn-outline-secondary"
                                                   data-bs-toggle="tooltip"
                                                   title="Detail Pengiriman">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ in_array($shipment->status, ['DIPROSES', 'MENUNGGU KONFIRMASI']) ? url('/dashboard/admin/batal/' . $shipment->id) : '#' }}"
                                                class="btn btn-sm btn-outline-secondary {{ $shipment->status !== 'DIPROSES' ? 'disabled' : '' }}"
                                                data-bs-toggle="tooltip"
                                                title="Batal">
                                                    <i class="fas fa-cancel"></i>
                                                </a>
                                            </td>
        -->
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
                        <a href="/dashboard/admin/kirim" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> Kirim Paket
                        </a>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
