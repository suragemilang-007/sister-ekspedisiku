@extends('layouts.admin')

@section('title', 'Semua Pesanan')

@section('content')
    <div class="container-fluid">

        <!-- Recent Shipments -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Semua Pesanan</h5>
            </div>

            <!-- Search and Filter Section -->
            <div class="card-body border-bottom">
                <form method="GET" id="filterForm">
                    <div class="row g-3">
                        <!-- Search -->
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" name="search"
                                    placeholder="Cari Nama Pengirim, Nama Penerima, Kurir"
                                    value="{{ request('search') }}">
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-md-4">
                            <div class="d-flex gap-4">
                                <a href="{{ route('admin.pesanan.list') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-undo"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body">
                @if (isset($semuaPesanan) && count($semuaPesanan) > 0)
                    <!-- Results Info -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-muted small">
                            Menampilkan {{ $semuaPesanan->firstItem() }} - {{ $semuaPesanan->lastItem() }}
                            dari {{ $semuaPesanan->total() }} hasil
                            @if (request('search'))
                                untuk pencarian "<strong>{{ request('search') }}</strong>"
                            @endif
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-sort me-1"></i> Urutkan
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item"
                                        href="{{ request()->fullUrlWithQuery(['sort_by' => 'nama_pengirim', 'sort_order' => 'asc']) }}">Nama Pengirim</a></li>
                                <li><a class="dropdown-item"
                                        href="{{ request()->fullUrlWithQuery(['sort_by' => 'nama_penerima', 'sort_order' => 'asc']) }}">Nama Penerima</a></li>
                                <li><a class="dropdown-item"
                                        href="{{ request()->fullUrlWithQuery(['sort_by' => 'kurir', 'sort_order' => 'asc']) }}">Nama Kurir</a></li>
                                <li><a class="dropdown-item"
                                        href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_order' => 'asc']) }}">Status</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover ">
                            <thead class="table-light">
                                <tr>
                                    <th>No Resi</th>
                                    <th>Nama Pengirim</th>
                                    <th>Nama Penerima</th>
                                    <th>Kurir</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($semuaPesanan as $semua)
                                    <tr class="text-dark">
                                         <td class="text-dark">
                                            {{ $semua->nomor_resi ?? '-' }}</td>
                                        <td class="fw-medium">{{ $semua->alamatPenjemputan->nama_pengirim ?? '-' }}</td>
                                        <td class="text-dark">
                                            {{ $semua->alamatTujuan->nama_penerima ?? '-' }}</td>
                                        <td class="text-dark">
                                            {{ $semua->kurir->nama ?? '' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $semua->status_color }} text-dark rounded-pill">
                                                {{ $semua->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted small">
                            Halaman {{ $semuaPesanan->currentPage() }} dari {{ $semuaPesanan->lastPage() }}
                            ({{ $semuaPesanan->total() }} total data)
                        </div>
                        <div>
                            {{ $semuaPesanan->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @else
                    <div class="empty-state text-center py-5">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <h5 class="fw-medium">
                            @if (request('search') || request('status') != 'all' || request('date_from') || request('date_to'))
                                Tidak Ada Data Yang Sesuai
                            @else
                                Belum Ada Pengiriman
                            @endif
                        </h5>
                        <p class="text-muted mb-3">
                            @if (request('search') || request('status') != 'all' || request('date_from') || request('date_to'))
                                Coba ubah filter pencarian atau buat pengiriman baru.
                            @else
                                Anda belum memiliki riwayat pengiriman. Mulai kirim paket sekarang!
                            @endif
                        </p>
                        <div class="d-flex gap-2 justify-content-center">
                            @if (request('search') || request('status') != 'all' || request('date_from') || request('date_to'))
                                <a href="{{ route('admin.zona.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-undo me-2"></i> Reset Filter
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @include('dashboard_pengirim.modal_detail')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Auto-submit form when filters change
                const filterForm = document.getElementById('filterForm');
                const statusSelect = filterForm.querySelector('select[name="status"]');
                const dateInputs = filterForm.querySelectorAll('input[type="date"]');

                statusSelect.addEventListener('change', function() {
                    filterForm.submit();
                });

                dateInputs.forEach(input => {
                    input.addEventListener('change', function() {
                        filterForm.submit();
                    });
                });

                // Search with delay
                const searchInput = filterForm.querySelector('input[name="search"]');
                let searchTimeout;

                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        filterForm.submit();
                    }, 500); // 500ms delay
                });


            });

            function Delete(idZona) {
                Swal.fire({
                    title: 'Hapus Zona Pengiriman',
                    text: "Apakah Anda yakin ingin menghapus zona pengiriman ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete('{{ route('admin.zona.delete', 'idZona') }}'.replace('idZona', idZona))
                            .then(res => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Permintaan Dikirim!',
                                    text: res.data.message ||
                                        'Permintaan penghapusan zona pengiriman telah dikirim. Zona Pengiriman akan segera dihapus.',
                                    timer: 3000,
                                    showConfirmButton: false
                                }).then(() => {
                                    // Setelah request berhasil dikirim, reload halaman untuk melihat perubahan
                                    // (setelah consumer selesai memproses dan menghapus dari DB)
                                    location.reload();
                                });
                            })
                            .catch(err => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: err.response?.data?.message ||
                                        'Gagal mengirim permintaan penghapusan.',
                                });
                            });
                    }
                });
            }
        </script>
    @endpush
@endsection
