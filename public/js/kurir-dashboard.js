document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Event listener untuk form update status
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
                // Tampilkan notifikasi sukses
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Status pengiriman berhasil diperbarui',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    // Reload halaman setelah berhasil
                    window.location.reload();
                });
            })
            .catch(function(error) {
                console.error('Error:', error);
                
                // Tampilkan notifikasi error
                Swal.fire({
                    title: 'Error!',
                    text: error.response?.data?.message || 'Terjadi kesalahan saat memperbarui status',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            })
            .finally(function() {
                // Kembalikan tombol ke keadaan semula
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
        });
    }

    // Fungsi untuk memuat data dashboard secara real-time (opsional)
    function refreshDashboardData() {
        axios.get('/kurir/dashboard-data')
            .then(function(response) {
                const data = response.data;
                
                // Update statistik
                document.getElementById('total-tugas').textContent = data.stats.total_tugas || 0;
                document.getElementById('sedang-dikirim').textContent = data.stats.sedang_dikirim || 0;
                document.getElementById('selesai').textContent = data.stats.selesai || 0;
                document.getElementById('dibatalkan').textContent = data.stats.dibatalkan || 0;
                
                // Update tabel jika diperlukan
                // ...
            })
            .catch(function(error) {
                console.error('Error refreshing dashboard data:', error);
            });
    }

    // Refresh data setiap 30 detik (opsional)
    // setInterval(refreshDashboardData, 30000);
});