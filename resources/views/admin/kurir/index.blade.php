@extends('layouts.admin')

@section('title', 'Manajemen Kurir')

@section('content')
    <div class="container-fluid">

        <!-- Card List Kurir -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Manajemen Kurir</h5>
                <a href="{{ route('admin.kurir.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Tambah Kurir
                </a>
            </div>

            <!-- Search -->
            <div class="card-body border-bottom">
                <form method="GET" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" name="search"
                                    placeholder="Cari nama, email, alamat..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-4">
                                <a href="{{ route('admin.kurir.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-undo"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body">
                @if (isset($kurirs) && $kurirs->count() > 0)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-muted small">
                            Menampilkan {{ $kurirs->firstItem() }} - {{ $kurirs->lastItem() }}
                            dari {{ $kurirs->total() }} kurir
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
                                        href="{{ request()->fullUrlWithQuery(['sort_by' => 'nama', 'sort_order' => 'asc']) }}">Nama</a></li>
                                <li><a class="dropdown-item"
                                        href="{{ request()->fullUrlWithQuery(['sort_by' => 'email', 'sort_order' => 'asc']) }}">Email</a></li>
                                <li><a class="dropdown-item"
                                        href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_order' => 'asc']) }}">Status</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>No HP</th>
                                    <th>Status</th>
                                    <th>Alamat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kurirs as $kurir)
                                    <tr>
                                        <td>{{ $kurir->nama }}</td>
                                        <td>{{ $kurir->email }}</td>
                                        <td>{{ $kurir->nohp }}</td>
                                        <td><span class="badge bg-{{ $kurir->status === 'AKTIF' ? 'success' : 'secondary' }}">{{ $kurir->status }}</span></td>
                                        <td>{{ $kurir->alamat }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.kurir.edit', $kurir->id_kurir) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus"
                                                    onclick="deleteKurir({{ $kurir->id_kurir }})">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <div class="text-muted small">
                            Halaman {{ $kurirs->currentPage() }} dari {{ $kurirs->lastPage() }}
                        </div>
                        <div>
                            {{ $kurirs->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                        <h5 class="fw-medium">Tidak ada kurir ditemukan</h5>
                        <p class="text-muted">Silakan tambah kurir baru atau ubah filter pencarian Anda.</p>
                        <a href="{{ route('admin.kurir.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> Tambah Kurir
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function deleteKurir(id) {
                Swal.fire({
                    title: 'Hapus Kurir?',
                    text: "Data kurir akan dihapus melalui Kafka secara asinkron!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(`/admin/kurir/${id}`)
                            .then(res => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: res.data.message,
                                    timer: 2500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            })
                            .catch(err => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Menghapus!',
                                    text: err.response?.data?.message || 'Terjadi kesalahan.',
                                });
                            });
                    }
                });
            }
        </script>
    @endpush
@endsection
