@extends('layouts.app')

@section('title', 'Dashboard Pengirim')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Dashboard Pengirim</h1>
            <p class="text-muted">Selamat datang kembali, {{ Session::get('user_name') }}!</p>
        </div>
        
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3 d-flex align-items-stretch">
            <div class="card w-100 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary-light rounded-3 p-3">
                        <i class="fas fa-box fa-2x text-primary"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="fw-medium mb-1">Total Pengiriman</h6>
                        <h3 class="fw-bold mb-0" id="total-pengiriman">0</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 d-flex align-items-stretch">
            <div class="card w-100 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success bg-opacity-10 rounded-3 p-3">
                        <i class="fas fa-truck fa-2x text-success"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="fw-medium mb-1">Pengiriman Aktif</h6>
                        <h3 class="fw-bold mb-0" id="pengiriman-aktif">0</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 d-flex align-items-stretch">
            <div class="card w-100 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 bg-danger bg-opacity-10 rounded-3 p-3">
                        <i class="fas fa-tasks fa-2x text-danger"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="fw-medium mb-1">Pengiriman Selesai</h6>
                        <h3 class="fw-bold mb-0" id="pengiriman-selesai">0</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 d-flex align-items-stretch">
            <a href="/dashboard/pengirim/kirim" class="btn btn-primary w-100 h-100 d-flex flex-column justify-content-center align-items-center py-4" style="font-size: 1.1rem;">
                <i class="fas fa-plus fa-2x mb-2"></i>
                Kirim Paket Baru
            </a>
        </div>
    </div>

    <!-- Recent Shipments -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Pengiriman Terbaru</h5>
            <a href="/dashboard/pengirim/history" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="card-body">
            @if(isset($recent_shipments) && count($recent_shipments) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
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
                        <tbody id="recent-shipments-body">
                            
                        </tbody>
                    </table>
                </div>
            @else
                
            @endif
        </div>
    </div>

</div>
@endsection
@include('dashboard_pengirim.modal_detail')
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.socket.io/4.3.2/socket.io.min.js"></script>
<script>
    

    socket.on("update-dashboard-pengirim", function (data) {
        loadDashboard(); 
    });
    function loadDashboard() {
            $.ajax({
                url: "{{ route('dashboard.pengirim') }}",
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    
                    $('#total-pengiriman').text(res.stats.total_pengiriman);
                    $('#pengiriman-aktif').text(res.stats.pengiriman_aktif);
                    $('#pengiriman-selesai').text(res.stats.pengiriman_selesai);

                    // Update table recent shipments
                    let tbody = '';
                    if (res.recent_shipments.length > 0) {
                        function getStatusColor(status) {
                            switch (status) {
                                case 'MENUNGGU KONFIRMASI':
                                    return 'warning';
                                case 'DIPROSES':
                                    return 'primary';
                                case 'DIBAYAR':
                                    return 'info';
                                case 'DIKIRIM':
                                    return 'success';
                                case 'DITERIMA':
                                    return 'primary';
                                case 'DIBATALKAN':
                                    return 'danger';
                                default:
                                    return 'secondary';
                            }
                        }
                        res.recent_shipments.forEach(function (item) {
                            // console.log(item);
                            const statusColor = getStatusColor(item.status);
                            tbody += `
                                <tr class="text-dark">
                                    <td class="fw-medium">${item.nomor_resi}</td>
                                    <td>${item.alamat_tujuan.alamat_lengkap ?? '-'}, ${item.alamat_tujuan.kecamatan ?? ''}, ${item.alamat_tujuan.kode_pos ?? ''}</td>
                                    <td>${item.alamat_tujuan.nama_penerima ?? '-'}</td>
                                    <td>
                                        <span class="badge bg-${statusColor} text-dark rounded-pill">
                                            ${item.status}
                                        </span>
                                    </td>
                                    <td>${new Date(item.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary" 
        onclick="showDetailModal('${item.nomor_resi}')"
        data-bs-toggle="tooltip" 
        title="Detail Pengiriman">
    <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    } else {
                        tbody = `
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="empty-state">
                                        <i class="fas fa-box-open mb-3" style="font-size:2rem;"></i>
                                        <h5 class="fw-medium">Belum Ada Pengiriman</h5>
                                        <p class="text-muted mb-3">Anda belum memiliki riwayat pengiriman. Mulai kirim paket sekarang!</p>
                                        <a href="/dashboard/pengirim/kirim" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i> Kirim Paket
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        `;
                    }

                    $('#recent-shipments-body').html(tbody);
                },
                error: function () {
                    alert("Gagal mengambil data dashboard.");
                }
            });
        }
    $(document).ready(function () {
        loadDashboard(); 
    });

    
</script>
@endsection