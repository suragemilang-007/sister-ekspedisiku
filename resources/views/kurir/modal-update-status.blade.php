<!-- Modal Update Status -->
<div class="modal fade" id="modalUpdateStatus" tabindex="-1" aria-labelledby="modalUpdateStatusLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalUpdateStatusLabel">Update Status Tugas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="updateStatusForm">
            @csrf
            <input type="hidden" id="id_penugasan" name="id_penugasan">
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="" disabled selected>Pilih Status</option>
                    <option value="MENUNGGU KONFIRMASI">MENUNGGU KONFIRMASI</option>
                    <option value="DIPROSES">DIPROSES</option>
                    <option value="DIBAYAR">DIBAYAR</option>
                    <option value="DIKIRIM">DIKIRIM</option>
                    <option value="DITERIMA">DITERIMA</option>
                    <option value="DIBATALKAN">DIBATALKAN</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="catatan" class="form-label">Catatan</label>
                <textarea class="form-control" id="catatan" name="catatan" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-save me-2"></i> Update Status
            </button>
        </form>
        <script>
        const updateStatusForm = document.getElementById('updateStatusForm');
        if (updateStatusForm) {
            updateStatusForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const id_penugasan = document.getElementById('id_penugasan').value;
                const status = document.getElementById('status').value;
                const catatan = document.getElementById('catatan').value;
                // Tampilkan loading spinner
                const submitBtn = document.querySelector('#updateStatusForm button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
                // Kirim data menggunakan Axios
                axios.post('/kurir/update-status', {
                    id_penugasan: id_penugasan,
                    status: status,
                    catatan: catatan
                })
                .then(function(response) {
                    // Update badge status pengiriman dan tugas di UI jika ada
                    let updated = false;
                    // Badge status pengiriman
                    const badgePengiriman = document.querySelector('.badge-status-pengiriman');
                    if (badgePengiriman) {
                        badgePengiriman.textContent = status;
                        // Ganti warna badge sesuai status
                        badgePengiriman.className = 'badge badge-status-pengiriman ' + getStatusColorClass(status);
                        updated = true;
                    }
                    // Badge status tugas
                    const badgeTugas = document.querySelector('.badge-status-tugas');
                    if (badgeTugas) {
                        badgeTugas.textContent = status;
                        badgeTugas.className = 'badge badge-status-tugas ' + getStatusColorClass(status);
                        updated = true;
                    }
                    // Update catatan jika ada
                    const catatanElem = document.querySelector('.catatan-tugas');
                    if (catatanElem) {
                        catatanElem.textContent = catatan || '-';
                        updated = true;
                    }
                    // Jika tidak ada badge di halaman, reload saja
                    if (!updated) {
                        window.location.reload();
                        return;
                    }
                    // Tutup modal
                    var modal = bootstrap.Modal.getInstance(document.getElementById('modalUpdateStatus'));
                    if (modal) modal.hide();
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Status pengiriman berhasil diperbarui',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                })
                .catch(function(error) {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: error.response?.data?.message || 'Terjadi kesalahan saat memperbarui status',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                })
                .finally(function() {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                });
            });
        }

        // Fungsi untuk menentukan warna badge sesuai status
        function getStatusColorClass(status) {
            switch (status) {
                case 'MENUNGGU KONFIRMASI': return 'bg-secondary text-dark';
                case 'DIPROSES': return 'bg-warning text-dark';
                case 'DIBAYAR': return 'bg-info text-dark';
                case 'DIKIRIM': return 'bg-primary text-white';
                case 'DITERIMA': return 'bg-success text-white';
                case 'DIBATALKAN': return 'bg-danger text-white';
                default: return 'bg-secondary text-dark';
            }
        }

        // Pastikan modal terisi data saat tombol pencil ditekan
        // Fungsi ini bisa dipanggil dari file tugas.blade.php atau inline di sini jika perlu
        function showUpdateStatusModal(id_penugasan, status, catatan) {
            document.getElementById('id_penugasan').value = id_penugasan;
            document.getElementById('status').value = status || '';
            document.getElementById('catatan').value = catatan || '';
            var modal = new bootstrap.Modal(document.getElementById('modalUpdateStatus'));
            modal.show();
        }
        // Inisialisasi event pada tombol pencil jika belum ada
        // (Aman dipanggil ulang, tidak dobel event)
        document.querySelectorAll('.btn-update-status').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                showUpdateStatusModal(
                    btn.getAttribute('data-id-penugasan'),
                    btn.getAttribute('data-status'),
                    btn.getAttribute('data-catatan')
                );
            });
        });

        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('input[name="_token"]').value;
        </script>
      </div>
    </div>
  </div>
</div>
