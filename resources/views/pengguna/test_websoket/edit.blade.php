@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Ubah Status Pengiriman</h3>
    <form id="formUpdateStatus">
        @csrf
        <input type="hidden" name="id_pengiriman" value="{{ $pengiriman->id_pengiriman }}">
        <div class="mb-3">
            <label for="status">Status</label>
            <select name="status" class="form-control" required>
                @foreach(['MENUNGGU KONFIRMASI', 'DIPROSES', 'DIBAYAR', 'DIKIRIM', 'DITERIMA', 'DIBATALKAN'] as $status)
                    <option value="{{ $status }}" {{ $pengiriman->status == $status ? 'selected' : '' }}>
                        {{ $status }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3" id="fieldKeteranganBatal" style="display: none;">
            <label>Keterangan Pembatalan</label>
            <textarea name="keterangan_batal" class="form-control">{{ $pengiriman->keterangan_batal }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelector('[name="status"]').addEventListener('change', function () {
    document.getElementById('fieldKeteranganBatal').style.display = (this.value === 'DIBATALKAN') ? 'block' : 'none';
});

document.getElementById('formUpdateStatus').addEventListener('submit', async function (e) {
    e.preventDefault();
    const formData = new FormData(this);

    try {
        await axios.post("{{ route('pengiriman.updateStatus') }}", formData);
        Swal.fire('Berhasil', 'Status berhasil diperbarui!', 'success');
    } catch (err) {
        Swal.fire('Gagal', 'Terjadi kesalahan', 'error');
    }
});
</script>
@endsection
