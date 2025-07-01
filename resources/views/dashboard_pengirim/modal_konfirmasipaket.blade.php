<div class="modal fade" id="modalKonfirmasiSampai" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-box-check"></i>
          Konfirmasi Pengiriman Sampai
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          <i class="fas fa-times text-white"></i>
        </button>
      </div>
      
     <div class="modal-body">
        <form id="formKonfirmasiSampai" enctype="multipart/form-data">
          <input type="hidden" id="konfirmasi_id_pengiriman" name="id_pengiriman">
          <input type="hidden" id="status" name="status" value="SELESAI">
          <div class="alert alert-warning d-none" id="errorKonfirmasi"></div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success">
              <i class="fas fa-check-circle"></i> Konfirmasi Diterima
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function showModalKonfirmasiSampai(id) {
    $('#konfirmasi_id_pengiriman').val(id);
    $('#errorKonfirmasi').addClass('d-none').text('');
    const modal = new bootstrap.Modal(document.getElementById('modalKonfirmasiSampai'));
    showData(id)
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

// function submitKonfirmasiSampai() {
//     const form = $('#formKonfirmasiSampai')[0];
//     const formData = new FormData(form);
//     try {
//         await axios.post("{{ route('pengiriman.updateStatus') }}", formData);
//         Swal.fire('Berhasil', 'Status berhasil diperbarui!', 'success');
//     } catch (err) {
//         Swal.fire('Gagal', 'Terjadi kesalahan', 'error');
//     }
// }
document.getElementById('formKonfirmasiSampai').addEventListener('submit', async function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    console.log(formData);
    try {
      await axios.post("{{ route('pengiriman.updateStatus.seslai') }}", formData);
      Swal.fire('Berhasil', 'Status berhasil diperbarui!', 'success');
      const modal = bootstrap.Modal.getInstance(document.getElementById('modalKonfirmasiSampai'));
      if (modal) modal.hide();
    } catch (err) {
      Swal.fire('Gagal', 'Terjadi kesalahan', 'error');
    }
});
</script>
