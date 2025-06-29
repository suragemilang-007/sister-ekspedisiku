@extends('layouts.kurir')

@section('title', 'Tugas Pengiriman')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Tugas Pengiriman</h1>
            <p class="text-muted">Kelola semua tugas pengiriman Anda</p>
        </div>
        <a href="{{ url()->current() }}" class="btn btn-primary">
            <i class="fas fa-sync-alt me-2"></i>Refresh
        </a>
    </div>

    <!-- Tugas List -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Daftar Tugas</h5>
        </div>
        <div class="card-body">
            @if (isset($tugas) && count($tugas) > 0)
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
                        @foreach ($tugas as $tugas_item)
                        <tr class="text-dark">
                            <td class="fw-medium">{{ $tugas_item->id_penugasan }}</td>
                            <td class="text-dark">{{ $tugas_item->pengiriman->nomor_resi }}</td>
                            <td class="text-dark">
                                {{ $tugas_item->pengiriman->alamatTujuan->alamat_lengkap }}
                            </td>
                            <td class="text-dark">{{ $tugas_item->created_at->format('d M Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $tugas_item->pengiriman->status_color }} text-dark rounded-pill">
                                    {{ $tugas_item->status }}
                                </span>
                            </td>
                            <td>
                                <a href="/kurir/detail/{{ $tugas_item->id_penugasan }}"
                                    class="btn btn-sm btn-outline-primary me-2"
                                    data-bs-toggle="tooltip"
                                    title="Detail Tugas">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($tugas_item->status !== 'SELESAI' && $tugas_item->status !== 'DIBATALKAN')
                                <a href="/kurir/update/{{ $tugas_item->id_penugasan }}"
                                    class="btn btn-sm btn-outline-success"
                                    data-bs-toggle="tooltip"
                                    title="Update Status">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $tugas->links() }}
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