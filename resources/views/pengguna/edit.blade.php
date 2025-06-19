@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Pengaturan Akun</h3>
    <form id="form-update">
        @csrf
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ $pengguna->nama }}">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $pengguna->email }}">
        </div>
        <div class="mb-3">
            <label>Tanggal Lahir</label>
            <input type="date" name="tgl_lahir" class="form-control" value="{{ $pengguna->tgl_lahir }}">
        </div>
        <div class="mb-3">
            <label>No HP</label>
            <input type="text" name="nohp" class="form-control" value="{{ $pengguna->nohp }}">
        </div>
        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control">{{ $pengguna->alamat }}</textarea>
        </div>
        <div class="mb-3">
            <label>Jenis Kelamin</label>
            <select name="kelamin" class="form-control">
                <option value="L" {{ $pengguna->kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ $pengguna->kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
    </form>

    <hr>

    <h4>Ganti Password</h4>
    <form id="form-password">
        @csrf
        <div class="mb-3">
            <label>Password Baru</label>
            <input type="password" name="password" class="form-control">
        </div>
        <button type="submit" class="btn btn-warning">Update Password</button>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.getElementById('form-update').addEventListener('submit', function (e) {
    e.preventDefault();

    Swal.fire({
        title: 'Simpan Perubahan?',
        text: "Pastikan data yang diisi sudah benar.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, simpan!',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            axios.post('/pengguna/update-info', new FormData(e.target))
                .then(res => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Informasi pengguna berhasil diperbarui.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                })
                .catch(err => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Gagal memperbarui informasi.',
                    });
                });
        }
    });
});


document.getElementById('form-password').addEventListener('submit', function (e) {
    e.preventDefault();

    Swal.fire({
        title: 'Ubah Password?',
        text: "Pastikan password baru sudah benar.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, ubah!',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            axios.post('/pengguna/update-password', new FormData(e.target))
                .then(res => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Password Diperbarui!',
                        text: 'Password berhasil diubah.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                })
                .catch(err => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Gagal memperbarui password.',
                    });
                });
        }
    });
});

</script>
@endsection
