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
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small" id="lastUpdate">Terakhir update: {{ now()->format('H:i:s') }}</span>
                    <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-sync-alt me-1"></i>Refresh
                    </a>
                </div>
            </div>
            <div class="card-body">
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
                        <tbody id="shipmentsTableBody">
                            @foreach ($recent_shipments as $shipment)
                                <tr class="text-dark" data-resi="{{ $shipment->nomor_resi }}">
                                    <td class="fw-medium">{{ $shipment->nomor_resi }}</td>
                                    <td class="text-dark">{{ $shipment->created_at->format('d M Y') }}</td>
                                    <td class="text-dark">
                                        <strong>{{ $shipment->alamatPenjemputan->nama_pengirim ?? '-' }}</strong>
                                        <p>{{ $shipment->alamatPenjemputan->alamat_lengkap ?? '-' }}</p>
                                    </td>
                                    <td class="text-dark">
                                        <strong>{{ $shipment->alamatTujuan->nama_penerima ?? '-' }}</strong>
                                        <p>{{ $shipment->alamatTujuan->alamat_lengkap ?? '-' }}</p>
                                    </td>
                                    <td class="text-dark">
                                        {{ $shipment->kurir->nama ?? '-' }}
                                    <td>
                                        <span
                                            class="badge bg-{{ $shipment->status_color }} text-dark rounded-pill status-badge"
                                            data-resi="{{ $shipment->nomor_resi }}">
                                            {{ $shipment->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
