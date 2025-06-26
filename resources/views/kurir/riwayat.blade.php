@extends('layouts.kurir')

@section('title', 'Riwayat Pengiriman')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Riwayat Pengiriman</h1>
            <p class="text-muted">Daftar pengiriman yang telah selesai atau dibatalkan</p>
        </div>
        <div>
            <a href="{{ url()->current() }}" class="btn btn-outline-primary">
                <i class="fas fa-sync-alt me-2"></i> Refresh
            </a>
        </div>
    </div>

    <!-- Daftar Riwayat -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Riwayat Pengiriman</h5>
        </div>
        <div class="card-body">
            @if (count($riwayat_pengiriman) > 0)
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
                        @foreach ($riwayat_pengiriman as $riwayat)
                        <tr>
                            <td>{{ $riwayat->id_penugasan }}</td>
                            <td>{{ $riwayat->pengiriman->nomor_resi }}</td>
                            <td>
                                {{ $riwayat->pengiriman->alamatTujuan->alamat }},
                                {{ $riwayat->pengiriman->alamatTujuan->kota }}
                            </td>
                            <td>{{ $riwayat->updated_at->format('d M Y') }}</td>
                            <td>
                                <span class="badge {{ $riwayat->status == 'SELESAI' ? 'bg-success' : 'bg-danger' }} text-white rounded-pill">
                                    {{ $riwayat->status }}
                                </span>
                            </td>
                            <td>
                                <a href="/kurir/detail/{{ $riwayat->id_penugasan }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> Tidak ada riwayat pengiriman.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection