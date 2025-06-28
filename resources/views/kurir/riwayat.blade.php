@extends('layouts.kurir')

@section('title', 'Riwayat Pengiriman')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Riwayat Pengiriman</h1>
            <p class="text-muted">Riwayat tugas pengiriman yang telah selesai</p>
        </div>
        <a href="{{ url()->current() }}" class="btn btn-primary">
            <i class="fas fa-sync-alt me-2"></i>Refresh
        </a>
    </div>

    <!-- Riwayat List -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Daftar Riwayat</h5>
        </div>
        <div class="card-body">
            @if (isset($riwayat) && count($riwayat) > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID Penugasan</th>
                            <th>Nomor Resi</th>
                            <th>Alamat Tujuan</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($riwayat as $riwayat_item)
                        <tr class="text-dark">
                            <td class="fw-medium">{{ $riwayat_item->id_penugasan }}</td>
                            <td class="text-dark">{{ $riwayat_item->pengiriman->nomor_resi }}</td>
                            <td class="text-dark">
                                {{ $riwayat_item->pengiriman->alamatTujuan->alamat_lengkap }}
                            </td>
                            <td class="text-dark">{{ $riwayat_item->updated_at->format('d M Y H:i') }}</td>
                            <td>
                                @if($riwayat_item->status === 'SELESAI')
                                    <span class="badge bg-success text-white rounded-pill">
                                        <i class="fas fa-check-circle me-1"></i>Selesai
                                    </span>
                                @elseif($riwayat_item->status === 'DIBATALKAN')
                                    <span class="badge bg-danger text-white rounded-pill">
                                        <i class="fas fa-times-circle me-1"></i>Dibatalkan
                                    </span>
                                @else
                                    <span class="badge bg-secondary text-white rounded-pill">
                                        {{ $riwayat_item->status }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="/kurir/detail/{{ $riwayat_item->id_penugasan }}"
                                    class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="tooltip"
                                    title="Detail Pengiriman">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $riwayat->links() }}
            </div>
            @else
            <div class="empty-state text-center py-5">
                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                <h5 class="fw-medium">Belum Ada Riwayat</h5>
                <p class="text-muted mb-3">Anda belum memiliki riwayat pengiriman yang selesai.</p>
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