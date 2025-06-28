@extends('layouts.app')

@section('title', 'Dashboard Pengirim')

@section('content')
<div class="container-fluid">
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
            <h5 class="mb-0">Histori Pengiriman</h5>
            <a href="/dashboard/pengirim/kirim" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Kirim Paket
            </a>
        </div>

        <!-- Search and Filter Section -->
        <div class="card-body border-bottom">
            <form method="GET" id="filterForm">
                <div class="row g-3">
                    <!-- Search -->
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   name="search" 
                                   placeholder="Cari no. resi, nama penerima, alamat..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="col-md-2">
                        <select class="form-select" name="status">
                            @foreach($statusOptions as $value => $label)
                                <option value="{{ $value }}" {{ request('status', 'all') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date From -->
                    <div class="col-md-2">
                        <input type="date" 
                               class="form-control" 
                               name="date_from" 
                               placeholder="Dari tanggal"
                               value="{{ request('date_from') }}">
                    </div>

                    <!-- Date To -->
                    <div class="col-md-2">
                        <input type="date" 
                               class="form-control" 
                               name="date_to" 
                               placeholder="Sampai tanggal"
                               value="{{ request('date_to') }}">
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-md-2">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i>
                            </button>
                            <a href="{{ route('dashboard.history.pengiriman') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-undo"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body">
            @if(isset($recent_shipments) && count($recent_shipments) > 0)
                <!-- Results Info -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-muted small">
                        Menampilkan {{ $recent_shipments->firstItem() }} - {{ $recent_shipments->lastItem() }} 
                        dari {{ $recent_shipments->total() }} hasil
                        @if(request('search'))
                            untuk pencarian "<strong>{{ request('search') }}</strong>"
                        @endif
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-sort me-1"></i> Urutkan
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => 'desc']) }}">Terbaru</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => 'asc']) }}">Terlama</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'nomor_resi', 'sort_order' => 'asc']) }}">No. Resi A-Z</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_order' => 'asc']) }}">Status</a></li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover ">
                        <thead class="table-light">
                            <tr>
                                <th>No. Resi</th>
                                <th>Tujuan</th>
                                <th>Penerima</th>
                                <th>Status</th>
                                <th>Tanggal Kirim</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_shipments as $shipment)
                                <tr class="text-dark">
                                    <td class="fw-medium">{{ $shipment->nomor_resi }}</td>
                                    <td class="text-dark">
                                        {{ $shipment->alamatTujuan->alamat_lengkap ?? '-' }},
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
                                        <div class="btn-group" role="group">
                                                                               
                                            <button class="btn btn-sm btn-outline-secondary" 
                                                    onclick="showDetailModal({{ $shipment->id_pengiriman }})"
                                                    data-bs-toggle="tooltip" 
                                                    title="Detail Pengiriman">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        Halaman {{ $recent_shipments->currentPage() }} dari {{ $recent_shipments->lastPage() }}
                        ({{ $recent_shipments->total() }} total data)
                    </div>
                    <div>
                        {{ $recent_shipments->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @else
                <div class="empty-state text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h5 class="fw-medium">
                        @if(request('search') || request('status') != 'all' || request('date_from') || request('date_to'))
                            Tidak Ada Data Yang Sesuai
                        @else
                            Belum Ada Pengiriman
                        @endif
                    </h5>
                    <p class="text-muted mb-3">
                        @if(request('search') || request('status') != 'all' || request('date_from') || request('date_to'))
                            Coba ubah filter pencarian atau buat pengiriman baru.
                        @else
                            Anda belum memiliki riwayat pengiriman. Mulai kirim paket sekarang!
                        @endif
                    </p>
                    <div class="d-flex gap-2 justify-content-center">
                        @if(request('search') || request('status') != 'all' || request('date_from') || request('date_to'))
                            <a href="{{ route('dashboard.history.pengiriman') }}" class="btn btn-outline-primary">
                                <i class="fas fa-undo me-2"></i> Reset Filter
                            </a>
                        @endif
                        <a href="/dashboard/pengirim/kirim" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> Kirim Paket
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@include('dashboard_pengirim.modal_detail')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const statusSelect = filterForm.querySelector('select[name="status"]');
    const dateInputs = filterForm.querySelectorAll('input[type="date"]');
    
    statusSelect.addEventListener('change', function() {
        filterForm.submit();
    });
    
    dateInputs.forEach(input => {
        input.addEventListener('change', function() {
            filterForm.submit();
        });
    });
    const searchInput = filterForm.querySelector('input[name="search"]');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterForm.submit();
        }, 500);
    });
    

});
</script>
@endpush
@endsection