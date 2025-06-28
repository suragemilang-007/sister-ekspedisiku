@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow p-4 rounded-4">
        <h4>Edit Alamat Tujuan</h4>
        <form id="editForm">
            @csrf
            <div class="mb-3">
                <label>Nama Penerima</label>
                <input type="text" class="form-control" name="nama_penerima" value="{{ $data->nama_penerima }}" required>
            </div>
            <div class="mb-3">
                <label>No HP</label>
                <input type="text" class="form-control" name="no_hp" value="{{ $data->no_hp }}" required>
            </div>
            <div class="mb-3">
                <label>Alamat Lengkap</label>
                <textarea class="form-control" name="alamat_lengkap" required>{{ $data->alamat_lengkap }}</textarea>
            </div>
            <div class="mb-3">
                <label>Kecamatan</label>
                <select class="form-select" id="kecamatan" name="kecamatan" required>
                    <option value="">Pilih kecamatan asal...</option>
                    @foreach($kecamatanAsal as $kecamatan)
                        <option value="{{ $kecamatan }}" {{ $data->kecamatan == $kecamatan ? 'selected' : '' }}>{{ $kecamatan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Kode Pos</label>
                <input type="text" class="form-control" name="kode_pos" value="{{ $data->kode_pos }}" required>
            </div>
            <div class="mb-3">
                <label>Keterangan (opsional)</label>
                <input type="text" class="form-control" name="keterangan_alamat" value="{{ $data->keterangan_alamat }}">
            </div>
            <button type="submit" class="btn btn-primary">Perbarui</button>
            <a href="{{ route('alamat-tujuan.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection

@section('scripts')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<!-- Or for RTL support -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
        $( '#kecamatan' ).select2( {
    theme: "bootstrap-5",
    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    placeholder: $( this ).data( 'placeholder' ),
} );
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    Swal.fire({
        title: 'Yakin simpan perubahan?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Simpan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData(this);
            axios.post("{{ route('alamat-tujuan.update', $data->uid) }}", formData)
                .then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Alamat tujuan berhasil diperbarui.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => window.location.href = "{{ route('alamat-tujuan.index') }}");
                })
                .catch(() => {
                    Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan.', 'error');
                });
        }
    });
});
</script>
@endsection
