{{-- resources/views/admin/pengguna/modal-detail.blade.php --}}
<div class="modal fade" id="modalDetailPengguna" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Pengguna</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table">
          <tbody>
            <tr><th>Nama</th><td id="nama"></td></tr>
            <tr><th>Email</th><td id="email"></td></tr>
            <tr><th>Alamat</th><td id="alamat"></td></tr>
            <tr><th>No HP</th><td id="nohp"></td></tr>
            <tr><th>Role</th><td id="role"></td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
