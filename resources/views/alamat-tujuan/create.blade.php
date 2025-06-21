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
                <input type="text" class="form-control" name="kecamatan" required>
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

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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
