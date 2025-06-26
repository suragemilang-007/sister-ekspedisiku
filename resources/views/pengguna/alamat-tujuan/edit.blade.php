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
                <input type="text" class="form-control" name="kecamatan" value="{{ $data->kecamatan }}" required>
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
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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
