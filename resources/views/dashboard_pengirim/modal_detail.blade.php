{{-- resources/views/pengiriman/modal-detail.blade.php --}}
<div class="modal fade" id="modalDetailPengiriman" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Pengiriman</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table">
          <tbody>
            <tr><th>Nomor Resi</th><td id="resi"></td></tr>
            <tr><th>Layanan</th><td id="nama_layanan"></td></tr>
            <tr><th>Deskripsi</th><td id="deskripsi"></td></tr>
            <tr><th>Status</th><td id="status"></td></tr>
            <tr><th>Catatan</th><td id="catatan"></td></tr>
            <tr><th>Alamat Tujuan</th><td id="alamat"></td></tr>
            <tr><th>Nama Penerima</th><td id="nama_penerima"></td></tr>
            <tr><th>No HP Penerima</th><td id="nohp_penerima"></td></tr>
            <tr><th>Nama Kurir</th><td id="kurir"></td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
