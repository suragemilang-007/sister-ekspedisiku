@extends('layouts.admin')

@section('title', 'Zona Pengiriman')

@section('content')
    <div class="container-fluid">

        <!-- Recent Shipments -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Zona Pengiriman</h5>
                <a href="/admin/zona/create" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Tambah Zona Pengiriman
                </a>
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
                                    placeholder="Cari Nama Zona, Kecamatan Asal, Kecamatan Tujuan"
                                    value="{{ request('search') }}">
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-md-4">
                            <div class="d-flex gap-4">
                                <a href="{{ route('admin.zona.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-undo"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body">
                @if (isset($zonaPengirimans) && count($zonaPengirimans) > 0)
                    <!-- Results Info -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-muted small">
                            Menampilkan {{ $zonaPengirimans->firstItem() }} - {{ $zonaPengirimans->lastItem() }}
                            dari {{ $zonaPengirimans->total() }} hasil
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
                                        href="{{ request()->fullUrlWithQuery(['sort_by' => 'kecamatan_asal', 'sort_order' => 'asc']) }}">Kecamatan
                                        Asal</a></li>
                                <li><a class="dropdown-item"
                                        href="{{ request()->fullUrlWithQuery(['sort_by' => 'kecamatan_tujuan', 'sort_order' => 'asc']) }}">Kecamatan
                                        Tujuan</a></li>
                                <li><a class="dropdown-item"
                                        href="{{ request()->fullUrlWithQuery(['sort_by' => 'biaya_tambahan', 'sort_order' => 'asc']) }}">Biaya
                                        Tambahan</a></li>
                                <li><a class="dropdown-item"
                                        href="{{ request()->fullUrlWithQuery(['sort_by' => 'nama_zona', 'sort_order' => 'asc']) }}">Nama
                                        Zona</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover ">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Zona</th>
                                    <th>Nama Layanan</th>
                                    <th>Kecamatan Asal</th>
                                    <th>Kecamatan Tujuan</th>
                                    <th>Biaya Tambahan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($zonaPengirimans as $zona)
                                    <tr class="text-dark">
                                        <td class="fw-medium">{{ $zona->nama_zona }}</td>
                                        <td class="text-dark">
                                            {{ $zona->layananPaket->nama_layanan ?? '-' }}</td>
                                        <td class="text-dark">
                                            {{ $zona->kecamatan_asal ?? '' }}</td>
                                        <td class="text-dark">
                                            {{ $zona->kecamatan_tujuan ?? '' }}
                                        </td>
                                        <td class="text-dark">
                                            {{ $zona->biaya_tambahan ? 'Rp ' . number_format($zona->biaya_tambahan, 0, ',', '.') : '-' }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                {{-- Tombol Edit/Detail --}}
                                                <a href="{{ route('admin.zona.edit', $zona->id_zona) }}"
                                                    class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip"
                                                    title="Edit Zona Pengiriman">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                {{-- Tombol Delete --}}
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="Delete({{ $zona->id_zona }})" data-bs-toggle="tooltip"
                                                    title="Hapus Zona Pengiriman">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted small">
                            Halaman {{ $zonaPengirimans->currentPage() }} dari {{ $zonaPengirimans->lastPage() }}
                            ({{ $zonaPengirimans->total() }} total data)
                        </div>
                        <div>
                            {{ $zonaPengirimans->links('pagination::bootstrap-4') }}
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
                            <a href="/admin/zona/create" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i> Tambah Zona Pengiriman
                            </a>
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
