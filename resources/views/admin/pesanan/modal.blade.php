<!-- Modal Detail Pengiriman -->
<div class="modal fade" id="modalDetailPengiriman" tabindex="-1" aria-labelledby="detailPengirimanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailPengirimanLabel">Detail Pengiriman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detailContent">
                    <!-- Isi detail akan dimuat dengan JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('tr[data-id]').forEach(row => {
            row.addEventListener('click', function() {
                const id = this.getAttribute('data-id');

                axios.get(`/admin/pesanan/${id}`)
                    .then(res => {
                        const data = res.data;
                        const steps = ['MENUNGGU KONFIRMASI', 'DIPROSES', 'DIBAYAR',
                            'DIKIRIM', 'DITERIMA'
                        ];
                        const progressIndex = steps.indexOf(data.status.toUpperCase());

                        let progressHTML = '';
                        steps.forEach((step, index) => {
                            const isCompleted = index < progressIndex;
                            const isCurrent = index === progressIndex;

                            progressHTML += `
            <div class="text-center flex-fill position-relative">
                <div class="rounded-circle d-flex align-items-center justify-content-center 
                    ${isCompleted ? 'bg-success' : isCurrent ? 'bg-info' : 'bg-secondary'} text-white"
                    style="width: 24px; height: 24px; margin: 0 auto; z-index: 2;">
                    ${isCompleted ? '<i class="fas fa-check"></i>' : index + 1}
                </div>
                <div class="small mt-2 ${isCurrent ? 'fw-bold text-dark' : 'text-muted'}" style="font-size: 0.75rem;">
                    ${step}
                </div>
                ${index < steps.length - 1 ? `
                    <div class="position-absolute top-50 start-100 translate-middle-y"
                        style="width: 100%; height: 3px; background-color: ${index < progressIndex ? '#198754' : '#dee2e6'}; z-index: 1;">
                    </div>` : ''}
            </div>
        `;
                        });

                        // Pindahkan innerHTML ke sini, setelah progressHTML sudah jadi
                        document.getElementById('detailContent').innerHTML = `
        <div class="mb-3">
            <strong>No Resi:</strong> ${data.nomor_resi}<br>
            <strong>Tanggal Pengiriman:</strong> ${data.tanggal_pengiriman}<br>
            <strong>Status:</strong> ${data.status}<br>
            <strong>Biaya Pengiriman:</strong>${data.biaya_pengiriman},-
            <br><br>
            
            <div class="row">
                <div class="col-md-6">
                    <strong>Pengirim:</strong> ${data.pengirim.nama} (${data.pengirim.no_hp})<br>
                    ${data.pengirim.alamat}, ${data.pengirim.kecamatan} (${data.pengirim.kode_pos})
                    <br><br>
                    <strong>Catatan:</strong> ${data.catatan ?? '-'}
                </div>
                <div class="col-md-6">
                    <strong>Penerima:</strong> ${data.penerima.nama} (${data.penerima.no_hp})<br>
                    ${data.penerima.alamat}, ${data.penerima.kecamatan} (${data.penerima.kode_pos})
                    <br><br>
                    <strong>Kurir:</strong> ${data.kurir.nama ?? '-'}
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center position-relative px-2" style="margin: 30px 10px;">
            ${progressHTML}
        </div>
    `;

                        new bootstrap.Modal(document.getElementById(
                            'modalDetailPengiriman')).show();
                    })
                    .catch(err => {
                        console.error('Gagal mengambil detail pengiriman:', err);
                        alert('Gagal mengambil detail pengiriman.');
                    });

            });
        });
    });
</script>
