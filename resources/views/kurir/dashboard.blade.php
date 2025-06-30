@extends('layouts.kurir')

@section('title', 'Dashboard Kurir')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Dashboard Kurir</h1>
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
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary-light rounded-3 p-3">
                            <i class="fas fa-box fa-2x text-primary"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="fw-medium mb-1">Total Tugas</h6>
                            <h3 class="fw-bold mb-0" id="totalTugas">{{ $stats['total_tugas'] ?? 0 }}</h3>
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
                            <i class="fas fa-truck fa-2x text-warning"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="fw-medium mb-1">Sedang Dikirim</h6>
                            <h3 class="fw-bold mb-0" id="sedangDikirim">{{ $stats['sedang_dikirim'] ?? 0 }}</h3>
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
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="fw-medium mb-1">Selesai</h6>
                            <h3 class="fw-bold mb-0" id="selesai">{{ $stats['selesai'] ?? 0 }}</h3>
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
                            <i class="fas fa-times-circle fa-2x text-danger"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="fw-medium mb-1">Dibatalkan</h6>
                            <h3 class="fw-bold mb-0" id="dibatalkan">{{ $stats['dibatalkan'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tugas Terbaru -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Tugas Terbaru</h5>
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small" id="lastUpdate">Terakhir update: {{ now()->format('H:i:s') }}</span>
                <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-sync-alt me-1"></i>Refresh
                </a>
            </div>
        </div>
        <div class="card-body">
            @if (isset($tugas_terbaru) && count($tugas_terbaru) > 0)
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
                    <tbody id="tugasTableBody">
                        @foreach ($tugas_terbaru as $tugas)
                        <tr class="text-dark" data-resi="{{ $tugas->pengiriman->nomor_resi }}">
                            <td class="fw-medium">{{ $tugas->id_penugasan }}</td>
                            <td class="text-dark">{{ $tugas->pengiriman->nomor_resi }}</td>
                            <td class="text-dark">
                                {{ $tugas->pengiriman->alamatTujuan->alamat_lengkap }}
                            </td>
                            <td class="text-dark">{{ $tugas->created_at->format('d M Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $tugas->pengiriman->status_color }} text-dark rounded-pill status-badge" 
                                      data-resi="{{ $tugas->pengiriman->nomor_resi }}">
                                    {{ str_replace('KURIRI', 'KURIR', $tugas->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="/kurir/detail/{{ $tugas->id_penugasan }}"
                                    class="btn btn-sm btn-outline-primary me-2"
                                    data-bs-toggle="tooltip"
                                    title="Detail Tugas">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/kurir/update/{{ $tugas->id_penugasan }}"
                                    class="btn btn-sm btn-outline-success"
                                    data-bs-toggle="tooltip"
                                    title="Update Status">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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

<!-- Real-time Notification Toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="statusUpdateToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="fas fa-bell text-primary me-2"></i>
            <strong class="me-auto">Update Status</strong>
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
    // Inisialisasi tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // WebSocket Connection
    const socket = io('http://localhost:3001');
    const connectionStatus = document.getElementById('connectionStatus');
    const lastUpdate = document.getElementById('lastUpdate');
    const toast = new bootstrap.Toast(document.getElementById('statusUpdateToast'));

    // Connection status handling
    socket.on('connect', function() {
        console.log('Connected to WebSocket server');
        connectionStatus.className = 'badge bg-success';
        connectionStatus.innerHTML = '<i class="fas fa-circle me-1"></i>Terhubung';
        
        // Join kurir room
        socket.emit('join-room', 'kurir');
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
            switch(data.status) {
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
        fetch('/kurir/dashboard-data')
            .then(response => response.json())
            .then(data => {
                if (data.stats) {
                    document.getElementById('totalTugas').textContent = data.stats.total_tugas;
                    document.getElementById('sedangDikirim').textContent = data.stats.sedang_dikirim;
                    document.getElementById('selesai').textContent = data.stats.selesai;
                    document.getElementById('dibatalkan').textContent = data.stats.dibatalkan;
                }
            })
            .catch(error => {
                console.error('Error refreshing dashboard stats:', error);
            });
    }

    // Auto-refresh every 30 seconds as fallback
    setInterval(refreshDashboardStats, 30000);
});
</script>
@endsection