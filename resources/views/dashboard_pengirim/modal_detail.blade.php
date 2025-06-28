{{-- resources/views/pengiriman/modal-detail.blade.php --}}
<div class="modal fade" id="modalDetailPengiriman" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-truck-fast"></i>
                    Pelacakan Paket
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times text-white"></i>
                </button>
            </div>
            
            <div class="modal-body">
                <!-- Status Card -->
                <div class="status-card">
                    <div class="status-header">
                        <div>
                            <h6 class="mb-1 text-muted">Nomor Resi</h6>
                            <div class="resi-number" id="resi">-</div>
                        </div>
                        <div class="status-badge menunggu" id="statusBadge">
                            <i class="fas fa-clock"></i>
                            <span id="status">Menunggu</span>
                        </div>
                    </div>
                    
                </div>

                <!-- Service Info -->
                <div class="info-section">
                    <div class="info-section-header">
                        <i class="fas fa-box"></i>
                        Informasi Layanan
                    </div>
                    <div class="info-section-body">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-shipping-fast"></i>
                                Layanan
                            </div>
                            <div class="info-value" id="nama_layanan">-</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-file-alt"></i>
                                Deskripsi
                            </div>
                            <div class="info-value" id="deskripsi">-</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-sticky-note"></i>
                                Catatan
                            </div>
                            <div class="info-value" id="catatan">-</div>
                        </div>
                    </div>
                </div>

                <!-- Destination Info -->
                <div class="info-section">
                    <div class="info-section-header">
                        <i class="fas fa-map-marker-alt"></i>
                        Informasi Tujuan
                    </div>
                    <div class="info-section-body">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-location-dot"></i>
                                Alamat
                            </div>
                            <div class="info-value" id="alamat">-</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-user"></i>
                                Penerima
                            </div>
                            <div class="info-value" id="nama_penerima">-</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-phone"></i>
                                No. HP
                            </div>
                            <div class="info-value" id="nohp_penerima">-</div>
                        </div>
                    </div>
                </div>

                <!-- Courier Info -->
                <div class="info-section">
                    <div class="info-section-header">
                        <i class="fas fa-motorcycle"></i>
                        Informasi Kurir
                    </div>
                    <div class="info-section-body">
                        <div class="kurir-card" id="kurirCard">
                            <div class="kurir-avatar">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="kurir-info flex-grow-1">
                                <h6 id="kurir">Belum ditugaskan</h6>
                                <div class="text-muted">Kurir Ekspedisiku</div>
                            </div>
                            <div id="kurirStatus">
                                <i class="fas fa-clock text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-track" onclick="refreshTracking()">
                    <i class="fas fa-sync-alt"></i>
                    Refresh Status
                </button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.socket.io/4.3.2/socket.io.min.js"></script>

<script>
  socket.on("update-data-pengiriman", function (data) {
      const currentId = $('#modalDetailPengiriman').data('id');
      setTimeout(() => {
        showData(currentId); 
    }, 300);
      
    });
  function showDetailModal(id) {
    console.log("ID Pengiriman:", id);
    $('#modalDetailPengiriman').data('id', id);
    showData(id);
    const modal = new bootstrap.Modal(document.getElementById('modalDetailPengiriman'));
    modal.show();
}
  function showData(id) {
    $.ajax({
      url: "{{ route('dashboard.pengirim.detail', ['id' => 'ID_PLACEHOLDER']) }}".replace('ID_PLACEHOLDER', id),
      type: "GET",
      dataType: "json",
      success: function(response) {
        if (response.status === "success") {
          const data = response.data.pengiriman;
          const datalayanan = response.data.layanan;
          $("#resi").text(data.nomor_resi);
          $("#nama_layanan").text(datalayanan.nama_layanan);
          $("#deskripsi").text(datalayanan.deskripsi);
          $("#status").text(data.status);
          $("#catatan").text(data.catatan_opsional || '-');

          $("#alamat").text(data.alamat_tujuan && data.alamat_tujuan.alamat_lengkap ? data.alamat_tujuan.alamat_lengkap : '-');
          $("#nama_penerima").text(data.alamat_tujuan && data.alamat_tujuan.nama_penerima ? data.alamat_tujuan.nama_penerima : '-');
          $("#nohp_penerima").text(data.alamat_tujuan && data.alamat_tujuan.no_hp ? data.alamat_tujuan.no_hp : '-');

          $("#kurir").text(data.kurir && data.kurir.nama ? data.kurir.nama : 'Belum ditugaskan');

          
        }
      }
    });
  }
  function refreshTracking(){
    const currentId = $('#modalDetailPengiriman').data('id');
      showData(currentId);
  }
</script>


<style>
    .modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }

    .modal-header {
        background: var(--accent-gradient);
        color: white;
        border: none;
        padding: 1.5rem 2rem;
        position: relative;
    }

    .modal-header .modal-title {
        font-weight: 700;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .modal-header .btn-close {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        opacity: 1;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-header .btn-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
    }

    .modal-body {
        padding: 0;
        background: #f8fafc;
    }

    /* Status Card */
    .status-card {
        background: white;
        margin: 1.5rem 2rem;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border-left: 5px solid var(--accent-blue);
        position: relative;
        overflow: hidden;
    }

    .status-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: linear-gradient(45deg, rgba(14, 165, 233, 0.1), rgba(59, 130, 246, 0.05));
        border-radius: 50%;
        transform: translate(30px, -30px);
    }

    .status-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .status-badge.menunggu {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: white;
        animation: pulse 2s infinite;
    }

    .status-badge.diproses {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
        color: white;
    }

    .status-badge.dikirim {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
        animation: tracking 3s infinite;
    }

    .status-badge.selesai {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .status-badge.dibatalkan {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    @keyframes pulse {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(251, 191, 36, 0.7); }
        70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(251, 191, 36, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(251, 191, 36, 0); }
    }

    @keyframes tracking {
        0% { transform: translateX(0); }
        50% { transform: translateX(5px); }
        100% { transform: translateX(0); }
    }

    .resi-number {
        font-family: 'Courier New', monospace;
        font-size: 1.25rem;
        font-weight: bold;
        color: var(--primary-dark);
        letter-spacing: 1px;
    }

    /* Info Sections */
    .info-section {
        background: white;
        margin: 0 2rem 1.5rem;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .info-section-header {
        background: var(--primary-dark);
        color: white;
        padding: 1rem 1.5rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .info-section-body {
        padding: 1.5rem;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-color);
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: var(--text-muted);
        min-width: 140px;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-value {
        flex: 1;
        color: var(--primary-dark);
        font-weight: 500;
    }

    .info-value.empty {
        color: var(--text-muted);
        font-style: italic;
    }

    /* Kurir Card */
    .kurir-card {
        background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        border: 2px solid #bae6fd;
        border-radius: 12px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .kurir-avatar {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: var(--accent-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
    }

    .kurir-info h6 {
        margin: 0;
        color: var(--primary-dark);
        font-weight: 600;
    }

    .kurir-info .text-muted {
        font-size: 0.875rem;
    }

    /* Footer */
    .modal-footer {
        background: white;
        border: none;
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: center;
    }

    .btn-track {
        background: var(--accent-gradient);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-track:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(14, 165, 233, 0.3);
        color: white;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .modal-dialog {
            margin: 0.5rem;
        }
        
        .info-section {
            margin: 0 1rem 1rem;
        }
        
        .status-card {
            margin: 1rem;
        }
        
        .info-item {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .info-label {
            min-width: auto;
        }
    }
</style>
