@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow rounded-4 p-4">
        <h4 class="mb-4">Beri Feedback Pengiriman</h4>

        <form id="feedback-form">
            @csrf
            <input type="hidden" name="id_pengiriman" value="{{ $id_pengiriman }}">

            <div class="mb-3">
                <label for="rating">Rating (1 - 5)</label>
                <select name="rating" class="form-control" required>
                    <option value="">Pilih rating</option>
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="mb-3">
                <label for="komentar">Komentar (opsional)</label>
                <textarea name="komentar" class="form-control" rows="3" placeholder="Tulis komentar..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Kirim Feedback</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('feedback-form').addEventListener('submit', function (e) {
    e.preventDefault();

    Swal.fire({
        title: 'Kirim Feedback?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Kirim',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            axios.post('/feedback/store', new FormData(e.target))
                .then(response => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Terima kasih!',
                        text: 'Feedback berhasil dikirim.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                })
                .catch(err => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Feedback gagal dikirim.',
                    });
                });
        }
    });
});
</script>
@endsection
