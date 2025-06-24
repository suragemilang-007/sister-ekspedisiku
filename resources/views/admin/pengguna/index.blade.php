@extends('layouts.admin')

@section('title', 'Manajemen Admin')

@section('content')
    <div class="container-fluid">

        <!-- Recent Shipments -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Manajemen Admin</h5>
                <a href="/admin/pengguna/create" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Tambah Admin
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
                                    placeholder="Cari nama, email, alamat..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <!-- Action Buttons -->
                        <div class="col-md-4">
                            <div class="d-flex gap-4">
                                <a href="{{ route('admin.pengguna.list') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-undo"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body">
                @if (isset($admins) && count($admins) > 0)
                    <!-- Results Info -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-muted small">
                            Menampilkan {{ $admins->firstItem() }} - {{ $admins->lastItem() }}
                            dari {{ $admins->total() }} hasil
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
                                        href="{{ request()->fullUrlWithQuery(['sort_by' => 'nama', 'sort_order' => 'asc']) }}">Nama</a>
                                </li>
                                <li><a class="dropdown-item"
                                        href="{{ request()->fullUrlWithQuery(['sort_by' => 'email', 'sort_order' => 'asc']) }}">Email</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover ">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>No Telepon</th>
                                    <th colspan="2">Alamat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($admins as $admin)
                                    <tr class="text-dark">
                                        <td class="fw-medium">{{ $admin->nama }}</td>
                                        <td class="text-dark">{{ $admin->email }}</td>
                                        <td class="text-dark">{{ $admin->nohp }}</td>
                                        <td class="text-dark" colspan="2">
                                            {{ $admin->alamat ?? '-' }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                {{-- Tombol Edit/Detail --}}
                                                <a href="{{ route('admin.pengguna.edit', $admin->id_pengguna) }}"
                                                    class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip"
                                                    title="Edit Admin">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                {{-- Tombol Delete --}}
                                                {{-- Penting: Pastikan admin tidak bisa menghapus dirinya sendiri --}}
                                                @if ($admin->id_pengguna != Session::get('user_id'))
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="deleteUser({{ $admin->id_pengguna }})"
                                                        data-bs-toggle="tooltip" title="Hapus Admin">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                @else
                                                    {{-- Opsi: Tampilkan tombol nonaktif atau pesan --}}
                                                    <button type="button" class="btn btn-sm btn-outline-danger" disabled
                                                        data-bs-toggle="tooltip" title="Tidak bisa menghapus akun sendiri">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                @endif
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
                            Halaman {{ $admins->currentPage() }} dari {{ $admins->lastPage() }}
                            ({{ $admins->total() }} total data)
                        </div>
                        <div>
                            {{ $admins->links('pagination::bootstrap-4') }}
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
                                <a href="{{ route('dashboard.pengirim.history') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-undo me-2"></i> Reset Filter
                                </a>
                            @endif
                            <a href="/admin/pengguna/create" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i> Tambah Admin
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
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

            function deleteUser(userId) {
                Swal.fire({
                    title: 'Hapus Pengguna Ini?',
                    text: "Anda tidak akan dapat mengembalikan ini! Permintaan akan dikirim dan diproses secara asinkron.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Panggil route DELETE di Laravel
                        axios.delete('{{ route('admin.pengguna.delete', 'userId') }}'.replace('userId', userId))
                            .then(res => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Permintaan Dikirim!',
                                    text: res.data.message ||
                                        'Permintaan penghapusan pengguna telah dikirim. Pengguna akan segera dihapus.',
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
