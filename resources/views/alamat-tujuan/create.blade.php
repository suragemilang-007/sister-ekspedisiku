@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow p-4 rounded-4">
        <h4>Tambah Alamat Tujuan</h4>
        <form id="alamatForm">
            @csrf
            <div class="mb-3">
                <label>Nama Penerima</label>
                <input type="text" class="form-control" name="nama_penerima" required>
            </div>
            <div class="mb-3">
                <label>No HP</label>
                <input type="text" class="form-control" name="no_hp" required>
            </div>
            <div class="mb-3">
                <label>Alamat Lengkap</label>
                <textarea class="form-control" name="alamat_lengkap" required></textarea>
            </div>
            <div class="mb-3">
                <label>Kecamatan</label>
                
                <select class="form-select" id="kecamatan" name="kecamatan" required>
                                            <option value="">Pilih kecamatan asal...</option>
                                            @foreach($kecamatanTujuan as $kecamatan)
                                            <option value="{{ $kecamatan }}">{{ $kecamatan }}</option>
                                            @endforeach
                                        </select>
            </div>
            <div class="mb-3">
                <label>Kode Pos</label>
                <input type="text" class="form-control" name="kode_pos" required>
            </div>
            <div class="mb-3">
                <label>Keterangan (opsional)</label>
                <input type="text" class="form-control" name="keterangan_alamat">
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection
<!-- Styles -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<!-- Or for RTL support -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>

$( '#kecamatan' ).select2( {
    theme: "bootstrap-5",
    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    placeholder: $( this ).data( 'placeholder' ),
} );
document.getElementById('alamatForm').addEventListener('submit', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Konfirmasi',
        text: 'Yakin ingin menyimpan alamat ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Simpan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            axios.post("{{ route('alamat-tujuan.store') }}", new FormData(this))
                .then(response => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Alamat tujuan berhasil disimpan.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    this.reset();
                })
                .catch(err => {
                    Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan.', 'error');
                });
        }
    });
});
</script>
@endsection
