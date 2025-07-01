@extends('layouts.kurir')

@section('title', 'Riwayat Pengiriman')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Riwayat Pengiriman</h1>
            <p class="text-muted">Daftar tugas pengiriman yang telah selesai atau dibatalkan</p>
        </div>
        <a href="{{ url()->current() }}" class="btn btn-primary">
            <i class="fas fa-sync-alt me-2"></i>Refresh
        </a>
    </div>

    <!-- Riwayat List -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Riwayat Tugas</h5>
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
                            <th>Tanggal Tugas</th>
                            <th>Status</th>
                            <th>Waktu Sampai / Dibatalkan</th>
                            <th>Catatan / Keterangan Batal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($riwayat as $item)
                        @php
                            $statusTugas = $item->status;
                            $penugasan = $item->penugasanKurir;
                        @endphp
                        <tr class="text-dark">
                            <td class="fw-medium">{{ $penugasan->id_penugasan ?? '-' }}</td>
                            <td>{{ $item->nomor_resi }}</td>
                            <td>{{ $item->alamatTujuan->alamat_lengkap ?? '-' }}</td>
                            <td>{{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}</td>
                            <td>
                                <span class="badge bg-{{ badgeColor($statusTugas) }} text-dark rounded-pill">
                                    {{ $statusTugas }}
                                </span>
                            </td>
                            <td>
                                @if($statusTugas === 'DITERIMA')
                                    {{ $item->tanggal_sampai ? date('d M Y H:i', strtotime($item->tanggal_sampai)) : '-' }}
                                @elseif($statusTugas === 'DIBATALKAN')
                                    {{ $item->updated_at ? date('d M Y H:i', strtotime($item->updated_at)) : '-' }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($statusTugas === 'DITERIMA')
                                    {{ $item->catatan_opsional ?? '-' }}
                                @elseif($statusTugas === 'DIBATALKAN')
                                    {{ $item->keterangan_batal ?? ($penugasan->catatan ?? '-') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <a href="/kurir/detail/{{ $penugasan->id_penugasan ?? 0 }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Detail Tugas">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($statusTugas === 'DITERIMA' && $item->foto_bukti_sampai)
                                    <a href="{{ asset('storage/' . $item->foto_bukti_sampai) }}" target="_blank" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Lihat Bukti">
                                        <i class="fas fa-image"></i>
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
                {{ $riwayat->links() }}
            </div>
            @else
            <div class="empty-state text-center py-5">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <h5 class="fw-medium">Belum Ada Riwayat</h5>
                <p class="text-muted mb-3">Belum ada tugas pengiriman yang selesai atau dibatalkan.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@php
    function badgeColor($statusTugas) {
        switch ($statusTugas) {
            case 'MENUJU PENGIRIM': return 'warning';
            case 'DITERIMA KURIR': return 'info';
            case 'DALAM_PENGIRIMAN': return 'primary';
            case 'DITERIMA': return 'success';
            case 'DIBATALKAN': return 'danger';
            default: return 'secondary';
        }
    }
@endphp