<div class="modal fade" id="modalLacakPengiriman" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-truck-fast"></i>
                    Masukan Nomor Resi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times text-white"></i>
                </button>
            </div>
            
            <div class="modal-body">
                <form id="formLacakPaket" onsubmit="return false;">
                    <div class="mb-3">
                        <label for="noResi" class="form-label">Nomor Resi</label>
                        <input type="text" class="form-control" id="noResi" name="noResi" placeholder="Masukkan nomor resi" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" onclick="$('#modalLacakPengiriman').modal('hide'); showDetailModal(noResi.value);">
                        <i class="fas fa-search"></i>
                        Lacak Paket
                    </button>
                </form>            
            </div>
            <div class="modal-footer">
                
            </div>
        </div>
    </div>
</div>
@include('dashboard_pengirim.modal_detail')
<script>    
    function showLacakModal() {
    $("#modalLacakPengiriman").modal('show');
}
</script>