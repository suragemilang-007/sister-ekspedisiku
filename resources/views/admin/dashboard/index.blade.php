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
            <div class="d-flex align-items-center gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small" id="lastUpdate">Terakhir update: {{ now()->format('H:i:s') }}</span>
                </div>
                <div class="connection-status">
                    <span class="badge bg-secondary" id="connectionStatus">
                        <i class="fas fa-circle me-1"></i>Menghubungkan...
                    </span>
                </div>
                <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-sync-alt me-1"></i>Refresh
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card">
                    <a href="{{ route('admin.pesanan.baru.index') }}"
                        class="card-link-overlay text-decoration-none text-dark">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-primary-light rounded-3 p-3">
                                    <i class="fas fa-box fa-2x text-primary"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="fw-medium mb-1">Pesanan Baru</h6>
                                    <h3 class="fw-bold mb-0" id="pengirimanBaru">{{ $stats['pengiriman_baru'] ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <a href="{{ route('admin.kurir.index') }}" class="card-link-overlay text-decoration-none text-dark">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 bg-success bg-opacity-10 rounded-3 p-3">
                                    <i class="fas fa-truck fa-2x text-success"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="fw-medium mb-1">Total Kurir</h6>
                                    <h3 class="fw-bold mb-0" id="totalKurir">{{ $stats['total_kurir'] ?? 0 }}</h3>
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
                            <div class="flex-shrink-0 bg-warning bg-opacity-10 rounded-3 p-3">
                                <i class="fas fa-square-check fa-2x text-warning"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="fw-medium mb-1">Total Sukses</h6>
                                <h3 class="fw-bold mb-0" id="pengirimanSelesai">{{ $stats['pengiriman_selesai'] ?? 0 }}</h3>
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
                                    <h3 class="fw-bold mb-0" id="jumlahAdmin">{{ $stats['jumlah_admin'] ?? 0 }}</h3>
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

            </div>
            <div class="card-body">
                <div class="accordion" id="accordionPengiriman">
                    @foreach ($recent_shipments as $shipment)
                        @php
                            $resi = $shipment->nomor_resi;
                            $status = strtoupper($shipment->status);
                            $statuses = ['MENUNGGU KONFIRMASI', 'DIPROSES', 'DIBAYAR', 'DIKIRIM', 'DITERIMA'];
                            $progressIndex = $status === 'DIBATALKAN' ? -1 : array_search($status, $statuses);
                            $segmentPercent = 100 / count($statuses);
                        @endphp
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-{{ $resi }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-{{ $resi }}" aria-expanded="false"
                                    aria-controls="collapse-{{ $resi }}">
                                    <div class="w-100 d-flex justify-content-between align-items-center">
                                        <div><strong>{{ $resi }}</strong></div>
                                        <!--    <span class="badge bg-{{ $shipment->status_color }}">{{ $status }}</span> -->
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse-{{ $resi }}" class="accordion-collapse collapse"
                                aria-labelledby="heading-{{ $resi }}" data-bs-parent="#accordionPengiriman">
                                <div class="accordion-body">
                                    <div class="row mb-2">
                                        <div class="col-md-6"><strong>Tanggal:</strong>
                                            {{ $shipment->created_at->format('d M Y') }}</div>
                                        <div class="col-md-6"><strong>Kurir:</strong> {{ $shipment->kurir->nama ?? '-' }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Pengirim:</strong>
                                            <p class="mb-0">{{ $shipment->alamatPenjemputan->nama_pengirim ?? '-' }}
                                                (<span
                                                    class="text-muted">{{ $shipment->alamatPenjemputan->no_hp ?? '-' }}</span>)
                                            </p>
                                            <small>{{ $shipment->alamatPenjemputan->alamat_lengkap ?? '-' }}, </small>
                                            <small>{{ $shipment->alamatPenjemputan->kecamatan ?? '-' }}
                                                ({{ $shipment->alamatPenjemputan->kode_pos ?? '-' }})</small>
                                            <strong class="d-block mt-2">Catatan:</strong>
                                            <p class="mb-0">{{ $shipment->catatan_opsional ?? '-' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Penerima:</strong>
                                            <p class="mb-0">{{ $shipment->alamatTujuan->nama_penerima ?? '-' }} (<span
                                                    class="text-muted">{{ $shipment->alamatTujuan->no_hp ?? '-' }}</span>)
                                            </p>
                                            <small>{{ $shipment->alamatTujuan->alamat_lengkap ?? '-' }}, </small>
                                            <small>{{ $shipment->alamatTujuan->kecamatan ?? '-' }}
                                                ({{ $shipment->alamatTujuan->kode_pos ?? '-' }})</small>
                                        </div>
                                    </div>

                                    {{-- Step Progress Bar --}}
                                    <div class="mt-4">
                                        @if ($status === 'DIBATALKAN')
                                            <div class="text-center text-danger fw-bold">
                                                <i class="fas fa-times-circle me-2"></i> DIBATALKAN
                                            </div>
                                        @else
                                            @php
                                                $stepClasses = [
                                                    'MENUNGGU KONFIRMASI',
                                                    'DIPROSES',
                                                    'DIBAYAR',
                                                    'DIKIRIM',
                                                    'DITERIMA',
                                                ];
                                            @endphp
                                            <div class="d-flex justify-content-between align-items-center position-relative px-2"
                                                style="margin: 30px 10px;">
                                                @foreach ($stepClasses as $index => $step)
                                                    @php
                                                        $isCompleted = $index < $progressIndex;
                                                        $isCurrent = $index === $progressIndex;
                                                    @endphp
                                                    <div class="text-center flex-fill position-relative">
                                                        {{-- Titik / Circle --}}
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center 
                        {{ $isCompleted ? 'bg-success text-white' : ($isCurrent ? 'bg-info text-white' : 'bg-secondary text-white') }}"
                                                            style="width: 24px; height: 24px; margin: 0 auto; z-index: 2; position: relative;">
                                                            @if ($isCompleted)
                                                                <i class="fas fa-check"></i>
                                                            @else
                                                                {{ $index + 1 }}
                                                            @endif
                                                        </div>

                                                        {{-- Label --}}
                                                        <div class="small mt-2 {{ $isCurrent ? 'fw-bold text-dark' : 'text-muted' }}"
                                                            style="font-size: 0.75rem;">
                                                            {{ $step }}
                                                        </div>

                                                        {{-- Garis --}}
                                                        @if ($index < count($stepClasses) - 1)
                                                            <div class="position-absolute top-50 start-100 translate-middle-y"
                                                                style="width: 100%; height: 3px; background-color: {{ $index < $progressIndex ? '#198754' : '#dee2e6' }}; z-index: 1;">
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

    </div>

    <!-- Real-time Notification Toast -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="statusUpdateToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="fas fa-bell text-primary me-2"></i>
                <strong class="me-auto">Update Status Pengiriman</strong>
                <small class="text-muted" id="toastTime"></small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastMessage">
                Status pengiriman telah diperbarui secara real-time.
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // WebSocket Connection
            const socket = io('http://localhost:4000');
            const connectionStatus = document.getElementById('connectionStatus');
            const lastUpdate = document.getElementById('lastUpdate');
            const toast = new bootstrap.Toast(document.getElementById('statusUpdateToast'));

            // Connection status handling
            socket.on('connect', function() {
                console.log('Connected to WebSocket server');
                connectionStatus.className = 'badge bg-success';
                connectionStatus.innerHTML = '<i class="fas fa-circle me-1"></i>Terhubung';

                // Join admin room
                socket.emit('join-room', 'admin');
            });

            socket.on('disconnect', function() {
                console.log('Disconnected from WebSocket server');
                connectionStatus.className = 'badge bg-danger';
                connectionStatus.innerHTML = '<i class="fas fa-circle me-1"></i>Terputus';
            });

            socket.on('connect_error', function(error) {
                console.log('Connection error:', error);
                connectionStatus.className = 'badge bg-warning';
                connectionStatus.innerHTML = '<i class="fas fa-circle me-1"></i>Error Koneksi';
            });

            // Listen for status updates
            socket.on('update-status', function(data) {
                console.log('Received status update:', data);

                // Update last update time
                lastUpdate.textContent = 'Terakhir update: ' + new Date().toLocaleTimeString('id-ID');

                // Update status badge if exists
                const statusBadge = document.querySelector(`[data-resi="${data.resi}"]`);
                if (statusBadge) {
                    const oldStatus = statusBadge.textContent.trim();
                    statusBadge.textContent = data.status;

                    // Update badge color based on status
                    statusBadge.className = 'badge rounded-pill status-badge';
                    switch (data.status) {
                        case 'DITERIMA_KURIR':
                            statusBadge.classList.add('bg-info', 'text-dark');
                            break;
                        case 'DALAM_PENGIRIMAN':
                            statusBadge.classList.add('bg-warning', 'text-dark');
                            break;
                        case 'SELESAI':
                            statusBadge.classList.add('bg-success', 'text-dark');
                            break;
                        case 'DIBATALKAN':
                            statusBadge.classList.add('bg-danger', 'text-dark');
                            break;
                        default:
                            statusBadge.classList.add('bg-secondary', 'text-dark');
                    }

                    // Show notification if status changed
                    if (oldStatus !== data.status) {
                        showStatusUpdateNotification(data);
                    }
                }

                // Refresh dashboard stats
                refreshDashboardStats();
            });

            function showStatusUpdateNotification(data) {
                const toastMessage = document.getElementById('toastMessage');
                const toastTime = document.getElementById('toastTime');

                toastMessage.innerHTML = `
            <strong>Resi: ${data.resi}</strong><br>
            Status: <span class="badge bg-primary">${data.status}</span><br>
            ${data.catatan ? `Catatan: ${data.catatan}` : ''}
        `;
                toastTime.textContent = new Date().toLocaleTimeString('id-ID');

                toast.show();
            }

            function refreshDashboardStats() {
                // You can implement AJAX call to refresh stats if needed
                // For now, we'll just update the last update time
                console.log('Dashboard stats refreshed');
            }

            // Auto-refresh every 30 seconds as fallback
            setInterval(refreshDashboardStats, 30000);
        });
    </script>
@endsection
