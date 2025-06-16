@extends('layouts.app')

@section('title', 'Dashboard Pengirim')

@section('content')
<div class="container-fluid">
    <!-- Header dengan animasi fade-in -->
    <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeIn">
        <div>
            <h1 class="fw-bold mb-1">Dashboard Pengirim</h1>
            <p class="text-muted">Selamat datang kembali, {{ Session::get('user_name') }}!</p>
        </div>
        <a href="/dashboard/pengirim/kirim" 
           class="btn btn-primary shadow-sm transition-all hover:-translate-y-1"
           data-bs-toggle="tooltip"
           title="Buat pengiriman baru">
            <i class="fas fa-plus me-2"></i> Kirim Paket Baru
        </a>
    </div>

    <!-- Statistics Cards dengan animasi fade-up -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3 animate__animated animate__fadeInUp animate__delay-1s">
            <div class="card h-100 transition-all hover:-translate-y-1">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-box fa-2x text-primary"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="fw-medium mb-1">Total Pengiriman</h6>
                            <h3 class="fw-bold mb-0" id="totalPengiriman">{{ $stats['total_pengiriman'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 animate__animated animate__fadeInUp animate__delay-2s">
            <div class="card h-100 transition-all hover:-translate-y-1">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-truck fa-2x text-success"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="fw-medium mb-1">Pengiriman Aktif</h6>
                            <h3 class="fw-bold mb-0" id="pengirimanAktif">{{ $stats['pengiriman_aktif'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 animate__animated animate__fadeInUp animate__delay-3s">
            <div class="card h-100 transition-all hover:-translate-y-1">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-warning bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-star fa-2x text-warning"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="fw-medium mb-1">Rating Rata-rata</h6>
                            <h3 class="fw-bold mb-0" id="ratingAvg">{{ number_format($stats['rating_avg'] ?? 0, 1) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 animate__animated animate__fadeInUp animate__delay-4s">
            <div class="card h-100 transition-all hover:-translate-y-1">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-danger bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-bell fa-2x text-danger"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="fw-medium mb-1">Notifikasi Baru</h6>
                            <h3 class="fw-bold mb-0" id="notifCount">{{ $stats['unread_notifications'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Shipments dengan animasi fade-up -->
    <div class="card mb-4 animate__animated animate__fadeInUp animate__delay-5s">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0">Pengiriman Terbaru</h5>
            <a href="/dashboard/pengirim/history" 
               class="btn btn-sm btn-outline-primary transition-all hover:-translate-y-1"
               data-bs-toggle="tooltip"
               title="Lihat semua riwayat pengiriman">
                Lihat Semua
            </a>
        </div>
        <div class="card-body p-0">
            @if(isset($recent_shipments) && count($recent_shipments) > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 py-3">No. Resi</th>
                                <th class="border-0 py-3">Tujuan</th>
                                <th class="border-0 py-3">Status</th>
                                <th class="border-0 py-3">Tanggal Kirim</th>
                                <th class="border-0 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_shipments as $shipment)
                                <tr class="transition-all hover:bg-light">
                                    <td class="py-3">{{ $shipment->no_resi }}</td>
                                    <td class="py-3">{{ $shipment->alamat_tujuan }}</td>
                                    <td class="py-3">
                                        <span class="badge bg-{{ $shipment->status_color }} rounded-pill">
                                            {{ $shipment->status }}
                                        </span>
                                    </td>
                                    <td class="py-3">{{ $shipment->created_at->format('d M Y') }}</td>
                                    <td class="py-3">
                                        <div class="btn-group">
                                            <a href="/dashboard/pengirim/lacak/{{ $shipment->no_resi }}" 
                                               class="btn btn-sm btn-outline-primary me-2 transition-all hover:-translate-y-1"
                                               data-bs-toggle="tooltip"
                                               title="Lacak status pengiriman">
                                                <i class="fas fa-search"></i>
                                            </a>
                                            <a href="/dashboard/pengirim/detail/{{ $shipment->id }}" 
                                               class="btn btn-sm btn-outline-secondary transition-all hover:-translate-y-1"
                                               data-bs-toggle="tooltip"
                                               title="Lihat detail pengiriman">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state text-center py-5">
                    <div class="empty-state-icon mb-4">
                        <i class="fas fa-box-open fa-4x text-muted mb-3 animate__animated animate__bounce animate__infinite"></i>
                    </div>
                    <h5 class="fw-medium">Belum Ada Pengiriman</h5>
                    <p class="text-muted mb-4">Anda belum memiliki riwayat pengiriman. Mulai kirim paket sekarang!</p>
                    <a href="/dashboard/pengirim/kirim" 
                       class="btn btn-primary shadow-sm transition-all hover:-translate-y-1"
                       data-bs-toggle="tooltip"
                       title="Buat pengiriman baru">
                        <i class="fas fa-plus me-2"></i> Kirim Paket
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Notifications dengan animasi fade-up -->
    <div class="card animate__animated animate__fadeInUp animate__delay-6s">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0">Notifikasi Terbaru</h5>
            <a href="/dashboard/pengirim/notifikasi" 
               class="btn btn-sm btn-outline-primary transition-all hover:-translate-y-1"
               data-bs-toggle="tooltip"
               title="Lihat semua notifikasi">
                Lihat Semua
            </a>
        </div>
        <div class="card-body p-0">
            @if(isset($recent_notifications) && count($recent_notifications) > 0)
                <div class="list-group list-group-flush">
                    @foreach($recent_notifications as $notification)
                        <div class="list-group-item border-0 py-3 px-4 transition-all hover:bg-light">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-circle {{ $notification->read_at ? 'text-muted' : 'text-primary' }} me-2" style="font-size: 8px;"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-1 fw-medium">{{ $notification->title }}</p>
                                    <p class="mb-1 text-muted">{{ $notification->message }}</p>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                @if(!$notification->read_at)
                                    <div class="ms-3">
                                        <form action="/dashboard/pengirim/notifikasi/{{ $notification->id }}/read" 
                                              method="POST" 
                                              class="d-inline mark-as-read-form">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm btn-light transition-all hover:-translate-y-1" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Tandai sudah dibaca">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state text-center py-5">
                    <div class="empty-state-icon mb-4">
                        <i class="fas fa-bell-slash fa-4x text-muted mb-3 animate__animated animate__swing animate__infinite"></i>
                    </div>
                    <h5 class="fw-medium">Tidak Ada Notifikasi</h5>
                    <p class="text-muted">Anda akan menerima notifikasi ketika ada update status pengiriman.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Link CSS Animate.css untuk animasi -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

@endsection

@section('scripts')
<script>
    // Inisialisasi tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // Loading state untuk form mark as read
    document.querySelectorAll('.mark-as-read-form').forEach(form => {
        form.addEventListener('submit', function() {
            const button = this.querySelector('button[type="submit"]')
            if (button) {
                button.disabled = true
                button.innerHTML = `
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                `
            }
        })
    })

    // Smooth scroll behavior
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault()
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            })
        })
    })
</script>
@endsection