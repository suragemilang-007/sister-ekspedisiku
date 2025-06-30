@extends('layouts.kurir')

@section('title', 'Update Status Pengiriman')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Update Status Pengiriman</h1>
            <p class="text-muted">Update status pengiriman untuk penugasan #{{ $penugasan->id_penugasan }}</p>
        </div>
        <a href="{{ route('kurir.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Form Update Status -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Pengiriman</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Nomor Resi</label>
                                <input type="text" class="form-control" value="{{ $penugasan->pengiriman->nomor_resi }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Status Saat Ini</label>
                                <input type="text" class="form-control" value="{{ $penugasan->status }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Alamat Tujuan</label>
                        <textarea class="form-control" rows="3" readonly>{{ $penugasan->pengiriman->alamatTujuan->alamat_lengkap }}</textarea>
                    </div>

                    <hr>

                    <form id="updateStatusForm">
                        @csrf
                        <input type="hidden" name="id_penugasan" value="{{ $penugasan->id_penugasan }}">
                        
                        <div class="mb-3">
                            <label for="status" class="form-label fw-medium">Update Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">Pilih status baru</option>
                                <option value="MENUNGGU KONFIRMASI" {{ $penugasan->status == 'MENUNGGU KONFIRMASI' ? 'disabled' : '' }}>
                                    MENUNGGU KONFIRMASI
                                </option>
                                <option value="DIPROSES" {{ $penugasan->status == 'DIPROSES' ? 'disabled' : '' }}>
                                    DIPROSES
                                </option>
                                <option value="DIBAYAR" {{ $penugasan->status == 'DIBAYAR' ? 'disabled' : '' }}>
                                    DIBAYAR
                                </option>
                                <option value="DIKIRIM" {{ $penugasan->status == 'DIKIRIM' ? 'disabled' : '' }}>
                                    DIKIRIM
                                </option>
                                <option value="DITERIMA" {{ $penugasan->status == 'DITERIMA' ? 'disabled' : '' }}>
                                    DITERIMA
                                </option>
                                <option value="DIBATALKAN" {{ $penugasan->status == 'DIBATALKAN' ? 'disabled' : '' }}>
                                    DIBATALKAN
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="catatan" class="form-label fw-medium">Catatan (Opsional)</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="3" 
                                placeholder="Tambahkan catatan tentang status pengiriman..."></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-2"></i>Update Status
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Riwayat Status -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Riwayat Status</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($penugasan->pengiriman->pelacakan->sortByDesc('created_at') as $tracking)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="fw-medium">{{ $tracking->status }}</h6>
                                <p class="text-muted small mb-1">{{ $tracking->created_at->format('d M Y H:i') }}</p>
                                @if($tracking->keterangan)
                                <p class="small">{{ $tracking->keterangan }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h6>Memperbarui Status...</h6>
                <p class="text-muted small">Mohon tunggu sebentar</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content {
    background: #f8f9fa;
    padding: 12px;
    border-radius: 8px;
    border-left: 3px solid #0d6efd;
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('updateStatusForm');
    const submitBtn = document.getElementById('submitBtn');
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const status = formData.get('status');
        
        if (!status) {
            alert('Silakan pilih status baru');
            return;
        }

        // Show loading modal
        loadingModal.show();
        submitBtn.disabled = true;

        fetch('/kurir/update-status', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id_penugasan: formData.get('id_penugasan'),
                status: status,
                catatan: formData.get('catatan')
            })
        })
        .then(response => response.json())
        .then(data => {
            loadingModal.hide();
            submitBtn.disabled = false;

            if (data.success) {
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    confirmButtonText: 'OK'
                }).then((result) => {
                    // Redirect to dashboard
                    window.location.href = '/kurir/dashboard';
                });
            } else {
                // Show error message
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message,
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            loadingModal.hide();
            submitBtn.disabled = false;
            
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                text: 'Gagal memperbarui status. Silakan coba lagi.',
                confirmButtonText: 'OK'
            });
        });
    });
});
</script>
@endsection