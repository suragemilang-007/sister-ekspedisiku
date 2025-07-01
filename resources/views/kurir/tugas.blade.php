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
                        @php
                            $statusPengiriman = $tugas_item->pengiriman->status;
                            // Mapping status pengiriman ke status tugas
                            if ($statusPengiriman === 'DIPROSES') {
                                $statusTugas = 'MENUJU PENGIRIM';
                            } elseif ($statusPengiriman === 'DIBAYAR') {
                                $statusTugas = 'DITERIMA KURIR';
                            } elseif ($statusPengiriman === 'DIKIRIM') {
                                $statusTugas = 'DALAM_PENGIRIMAN';
                            } elseif ($statusPengiriman === 'DITERIMA') {
                                $statusTugas = 'DITERIMA';
                            } elseif ($statusPengiriman === 'DIBATALKAN') {
                                $statusTugas = 'DIBATALKAN';
                            } else {
                                $statusTugas = $tugas_item->status;
                            }
                        @endphp
                        @if($statusTugas !== 'DITERIMA' && $statusTugas !== 'DIBATALKAN')
                        <tr class="text-dark">
                            <td class="fw-medium">{{ $tugas_item->id_penugasan }}</td>
                            <td class="text-dark">{{ $tugas_item->pengiriman->nomor_resi }}</td>
                            <td class="text-dark">
                                {{ $tugas_item->pengiriman->alamatTujuan->alamat_lengkap }}
                            </td>
                            <td class="text-dark">{{ $tugas_item->created_at->format('d M Y') }}</td>
                            <td>
                                <span class="badge bg-{{ badgeColor($statusTugas) }} text-dark rounded-pill">
                                    {{ $statusTugas }}
                                </span>
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary me-2 btn-update-status" data-id-penugasan="{{ $tugas_item->id_penugasan }}" data-status="{{ $tugas_item->status }}" data-catatan="{{ $tugas_item->catatan }}" data-bs-toggle="tooltip" title="Update Status">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                @if($statusTugas !== 'SELESAI' && $statusTugas !== 'DIBATALKAN')
                                    @if($statusPengiriman === 'MENUNGGU KONFIRMASI' || $statusPengiriman === 'DIPROSES')
                                        <button class="btn btn-sm btn-warning btn-konfirmasi-bayar" 
                                            data-id-penugasan="{{ $tugas_item->id_penugasan }}"
                                            data-id-pengiriman="{{ $tugas_item->id_pengiriman }}">
                                            Konfirmasi Pembayaran
                                        </button>
                                    @elseif($statusPengiriman === 'DIBAYAR')
                                        <button class="btn btn-sm btn-info btn-mulai-pengiriman" 
                                            data-id-penugasan="{{ $tugas_item->id_penugasan }}"
                                            data-id-pengiriman="{{ $tugas_item->id_pengiriman }}">
                                            Mulai Pengiriman
                                        </button>
                                    @elseif($statusPengiriman === 'DIKIRIM')
                                        <button class="btn btn-sm btn-success btn-konfirmasi-pengiriman" 
                                            data-id-penugasan="{{ $tugas_item->id_penugasan }}"
                                            data-id-pengiriman="{{ $tugas_item->id_pengiriman }}">
                                            Konfirmasi Pengiriman
                                        </button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @endif
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

        // Handle Konfirmasi Pembayaran
        document.querySelectorAll('.btn-konfirmasi-bayar').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const idPenugasan = btn.dataset.idPenugasan;
                const idPengiriman = btn.dataset.idPengiriman;
                fetch('/kurir/pengiriman/update-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        id_pengiriman: idPengiriman,
                        status: 'DIBAYAR'
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'ok') {
                        // Update penugasan_kurir
                        fetch('/kurir/penugasan/update-status', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                id_penugasan: idPenugasan,
                                status: 'DITERIMA KURIR'
                            })
                        })
                        .then(res2 => res2.json())
                        .then(data2 => {
                            if(data2.status === 'success') {
                                alert('Status pembayaran dikonfirmasi!');
                                location.reload();
                            } else {
                                alert('Gagal update penugasan kurir!');
                            }
                        });
                    } else {
                        alert('Gagal update status pengiriman!');
                    }
                });
            });
        });
        // Handle Mulai Pengiriman
        document.querySelectorAll('.btn-mulai-pengiriman').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const idPenugasan = btn.dataset.idPenugasan;
                const idPengiriman = btn.dataset.idPengiriman;
                fetch('/kurir/pengiriman/update-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        id_pengiriman: idPengiriman,
                        status: 'DIKIRIM'
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'ok') {
                        // Update penugasan_kurir
                        fetch('/kurir/penugasan/update-status', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                id_penugasan: idPenugasan,
                                status: 'DALAM_PENGIRIMAN'
                            })
                        })
                        .then(res2 => res2.json())
                        .then(data2 => {
                            if(data2.status === 'success') {
                                alert('Pengiriman dimulai!');
                                location.reload();
                            } else {
                                alert('Gagal update penugasan kurir!');
                            }
                        });
                    } else {
                        alert('Gagal update status pengiriman!');
                    }
                });
            });
        });
        // Handle Konfirmasi Pengiriman
        document.querySelectorAll('.btn-konfirmasi-pengiriman').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var modal = new bootstrap.Modal(document.getElementById('modalKonfirmasiPengiriman'));
                document.getElementById('modal-id-penugasan').value = btn.dataset.idPenugasan;
                document.getElementById('modal-id-pengiriman').value = btn.dataset.idPengiriman;
                modal.show();
            });
        });
        // Modal logic: tampilkan form sesuai tombol
        document.getElementById('btn-modal-diterima').onclick = function() {
            document.getElementById('form-diterima').style.display = 'block';
            document.getElementById('form-dibatalkan').style.display = 'none';
        };
        document.getElementById('btn-modal-dibatalkan').onclick = function() {
            document.getElementById('form-diterima').style.display = 'none';
            document.getElementById('form-dibatalkan').style.display = 'block';
        };
        // Submit form diterima
        document.getElementById('form-diterima').onsubmit = function(e) {
            e.preventDefault();
            const idPenugasan = document.getElementById('modal-id-penugasan').value;
            const idPengiriman = document.getElementById('modal-id-pengiriman').value;
            const waktuSampai = document.getElementById('waktu_sampai').value;
            const catatanOpsional = document.getElementById('catatan_opsional').value;
            const fotoBukti = document.getElementById('foto_bukti_sampai').files[0];
            const formData = new FormData();
            formData.append('id_pengiriman', idPengiriman);
            formData.append('status', 'DITERIMA');
            formData.append('tanggal_sampai', waktuSampai);
            formData.append('catatan_opsional', catatanOpsional);
            formData.append('foto_bukti_sampai', fotoBukti);
            fetch('/kurir/pengiriman/update-status', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'ok') {
                    // Update penugasan_kurir ke SELESAI
                    fetch('/kurir/penugasan/update-status', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            id_penugasan: idPenugasan,
                            status: 'SELESAI'
                        })
                    })
                    .then(res2 => res2.json())
                    .then(data2 => {
                        if(data2.status === 'success') {
                            alert('Pengiriman dikonfirmasi diterima!');
                            location.reload();
                        } else {
                            alert('Gagal update penugasan kurir!');
                        }
                    });
                } else {
                    alert('Gagal update status pengiriman!');
                }
            });
        };
        // Submit form dibatalkan
        document.getElementById('form-dibatalkan').onsubmit = function(e) {
            e.preventDefault();
            const idPenugasan = document.getElementById('modal-id-penugasan').value;
            const idPengiriman = document.getElementById('modal-id-pengiriman').value;
            const keteranganBatal = document.getElementById('keterangan_batal').value;
            fetch('/kurir/pengiriman/update-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    id_pengiriman: idPengiriman,
                    status: 'DIBATALKAN',
                    keterangan_batal: keteranganBatal
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'ok') {
                    // Update penugasan_kurir ke DIBATALKAN
                    fetch('/kurir/penugasan/update-status', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            id_penugasan: idPenugasan,
                            status: 'DIBATALKAN',
                            catatan: keteranganBatal
                        })
                    })
                    .then(res2 => res2.json())
                    .then(data2 => {
                        if(data2.status === 'success') {
                            alert('Pengiriman dibatalkan!');
                            location.reload();
                        } else {
                            alert('Gagal update penugasan kurir!');
                        }
                    });
                } else {
                    alert('Gagal update status pengiriman!');
                }
            });
        };
        // Handle Update Status (tombol aksi)
        document.querySelectorAll('.btn-update-status').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const idPenugasan = btn.dataset.idPenugasan;
                const statusTugas = btn.dataset.status;
                const catatan = btn.dataset.catatan;

                // Tampilkan modal dengan data yang sesuai
                var modal = new bootstrap.Modal(document.getElementById('modalUpdateStatus'));
                document.getElementById('modal-id-penugasan-update').value = idPenugasan;
                document.getElementById('modal-status-update').value = statusTugas;
                document.getElementById('modal-catatan-update').value = catatan;
                modal.show();
            });
        });
        // Handle Update Status (icon pencil)
        document.querySelectorAll('.btn-update-status').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const idPenugasan = btn.getAttribute('data-id-penugasan');
                const status = btn.getAttribute('data-status');
                const catatan = btn.getAttribute('data-catatan');
                // Set value ke modal
                document.getElementById('modal-id-penugasan').value = idPenugasan;
                document.getElementById('modal-status').value = status;
                document.getElementById('modal-catatan').value = catatan || '';
                // Reset form
                document.getElementById('updateStatusForm').reset();
                // Set status dan catatan lagi (karena reset)
                document.getElementById('modal-status').value = status;
                document.getElementById('modal-catatan').value = catatan || '';
                // Show modal
                var modal = new bootstrap.Modal(document.getElementById('modalUpdateStatus'));
                modal.show();
            });
        });
        // Submit form update status
        document.getElementById('form-update-status').onsubmit = function(e) {
            e.preventDefault();
            const idPenugasan = document.getElementById('modal-id-penugasan-update').value;
            const statusTugas = document.getElementById('modal-status-update').value;
            const catatan = document.getElementById('modal-catatan-update').value;

            fetch('/kurir/penugasan/update-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    id_penugasan: idPenugasan,
                    status: statusTugas,
                    catatan: catatan
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    alert('Status tugas berhasil diperbarui!');
                    location.reload();
                } else {
                    alert('Gagal memperbarui status tugas!');
                }
            });
        };
        // Submit form update status (modal)
        document.getElementById('updateStatusForm').onsubmit = function(e) {
            e.preventDefault();
            const idPenugasan = document.getElementById('modal-id-penugasan').value;
            const status = document.getElementById('modal-status').value;
            const catatan = document.getElementById('modal-catatan').value;
            fetch('/kurir/penugasan/update-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    id_penugasan: idPenugasan,
                    status: status,
                    catatan: catatan
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    alert('Status tugas berhasil diperbarui!');
                    location.reload();
                } else {
                    alert(data.message || 'Gagal memperbarui status tugas!');
                }
            });
        };
    });
</script>

<!-- Modal Konfirmasi Pengiriman -->
<div class="modal fade" id="modalKonfirmasiPengiriman" tabindex="-1" aria-labelledby="modalKonfirmasiPengirimanLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalKonfirmasiPengirimanLabel">Konfirmasi Status Akhir Pengiriman</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="modal-id-penugasan">
        <input type="hidden" id="modal-id-pengiriman">
        <div class="d-flex justify-content-center mb-3">
          <button class="btn btn-success me-2" id="btn-modal-diterima">Diterima</button>
          <button class="btn btn-danger" id="btn-modal-dibatalkan">Dibatalkan</button>
        </div>
        <form id="form-diterima" style="display:none;" enctype="multipart/form-data">
          <div class="mb-2">
            <label for="waktu_sampai" class="form-label">Waktu Sampai</label>
            <input type="datetime-local" class="form-control" id="waktu_sampai" name="waktu_sampai" required>
          </div>
          <div class="mb-2">
            <label for="catatan_opsional" class="form-label">Catatan Opsional</label>
            <textarea class="form-control" id="catatan_opsional" name="catatan_opsional"></textarea>
          </div>
          <div class="mb-2">
            <label for="foto_bukti_sampai" class="form-label">Foto Bukti Sampai</label>
            <input type="file" class="form-control" id="foto_bukti_sampai" name="foto_bukti_sampai" accept="image/*" required>
          </div>
          <button type="submit" class="btn btn-primary">Konfirmasi Diterima</button>
        </form>
        <form id="form-dibatalkan" style="display:none;">
          <div class="mb-2">
            <label for="keterangan_batal" class="form-label">Keterangan Batal</label>
            <textarea class="form-control" id="keterangan_batal" name="keterangan_batal" required></textarea>
          </div>
          <button type="submit" class="btn btn-danger">Konfirmasi Pembatalan</button>
        </form>
      </div>
    </div>
  </div>
</div>

@include('kurir.modal-update-status')
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