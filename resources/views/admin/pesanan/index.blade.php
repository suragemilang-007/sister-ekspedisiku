@extends('layouts.admin')

@section('title', 'Pesanan Baru')

@section('content')
    <div class="container-fluid">

        <!-- Recent Shipments -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pesanan Baru</h5>
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
                                    placeholder="Cari Nama Pengirim, Nama Penerima...." value="{{ request('search') }}">
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-md-4">
                            <div class="d-flex gap-4">
                                <a href="{{ route('admin.pesanan.baru.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-undo"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body">
                @if (isset($pesananBaru) && count($pesananBaru) > 0)
                    <!-- Results Info -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-muted small">
                            Menampilkan {{ $pesananBaru->firstItem() }} - {{ $pesananBaru->lastItem() }}
                            dari {{ $pesananBaru->total() }} hasil
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
                                        href="{{ request()->fullUrlWithQuery(['sort_by' => 'id_pengiriman', 'sort_order' => 'desc']) }}">Pengiriman
                                        Terbaru</a></li>
                                </a></li>
                                <li><a class="dropdown-item"
                                        href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_order' => 'asc']) }}">Status</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover ">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Pengirim</th>
                                    <th>Nama Penerima</th>
                                    <th>Jenis Layanan</th>
                                    <th>Total Biaya</th>
                                    <th>Kurir</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pesananBaru as $baru)
                                    <tr class="text-dark">
                                        <td class="fw-medium">
                                            <strong>{{ $baru->alamatPenjemputan->nama_pengirim ?? '-' }}</strong>
                                            <p class="mb-0 text-muted">{{ $baru->alamatPenjemputan->alamat_lengkap ?? '-' }}
                                            </p>
                                        </td>
                                        <td class="text-dark">
                                            <strong>{{ $baru->alamatTujuan->nama_penerima ?? '-' }}</strong>
                                            <p class="mb-0 text-muted">{{ $baru->alamatTujuan->alamat_lengkap ?? '-' }}
                                            </p>
                                        </td>
                                        <td class="text-dark">
                                            {{ $baru->zonaPengiriman->layananPaket->nama_layanan ?? '-' }}</td>
                                        <td class="text-dark">
                                            {{ $baru->total_biaya ? 'Rp ' . number_format($baru->total_biaya, 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="text-dark">{{ $baru->penugasanKurir->kurir->nama ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $baru->status_color }} text-dark rounded-pill">
                                                {{ $baru->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                {{-- Tombol Edit/Detail --}}
                                                @if ($baru->penugasanKurir && $baru->penugasanKurir->kurir)
                                                    <button class="btn btn-sm btn-secondary" disabled
                                                        data-bs-toggle="tooltip" title="Kurir sudah ditugaskan">
                                                        <i class="fas fa-user-check"></i>
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline-primary assign-kurir-btn"
                                                        data-id="{{ $baru->id_pengiriman }}" data-bs-toggle="tooltip"
                                                        title="Assign Kurir">
                                                        <i class="fas fa-user-plus"></i>
                                                    </button>
                                                @endif
                                                {{-- Tombol Delete --}}
                                                @if ($baru->status !== 'DIBATALKAN')
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        data-bs-toggle="tooltip" data-bs-target="#modalBatal"
                                                        data-id="{{ $baru->id_pengiriman }}" title="Batalkan Pesanan">
                                                        <i class="fas fa-times-circle"></i>
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-secondary" disabled
                                                        title="Sudah dibatalkan">
                                                        <i class="fas fa-ban"></i>
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
                            Halaman {{ $pesananBaru->currentPage() }} dari {{ $pesananBaru->lastPage() }}
                            ({{ $pesananBaru->total() }} total data)
                        </div>
                        <div>
                            {{ $pesananBaru->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @else
                    <div class="empty-state text-center py-5">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <h5 class="fw-medium">
                            @if (request('search') || request('status') != 'all' || request('date_from') || request('date_to'))
                                Tidak Ada Data Yang Sesuai
                            @else
                                Belum Ada Pengiriman Baru
                            @endif
                        </h5>
                        <p class="text-muted mb-3">
                            @if (request('search') || request('status') != 'all' || request('date_from') || request('date_to'))
                                Coba ubah filter pencarian.
                            @else
                                Anda belum memiliki pengiriman baru.
                            @endif
                        </p>
                        <div class="d-flex gap-2 justify-content-center">
                            @if (request('search') || request('status') != 'all' || request('date_from') || request('date_to'))
                                <a href="{{ route('admin.pesanan.baru.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-undo me-2"></i> Reset Filter
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Modal Pilih Kurir -->
    <div class="modal fade" id="modalAssignKurir" tabindex="-1" aria-labelledby="assignKurirLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="form-assign-kurir">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="assignKurirLabel">Assign Kurir</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_pengiriman" id="input-id-pengiriman">
                        <div class="mb-3">
                            <label for="id_kurir" class="form-label">Pilih Kurir</label>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary w-100 text-start dropdown-toggle" type="button"
                                    id="dropdownKurirBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                    -- Pilih Kurir Aktif --
                                </button>
                                <ul class="dropdown-menu w-100" aria-labelledby="dropdownKurirBtn"
                                    style="max-height: 200px; overflow-y: auto;">
                                    @forelse ($kurirs->where('status', 'AKTIF') as $kurir)
                                        <li>
                                            <a href="#" class="dropdown-item"
                                                onclick="selectKurir('{{ $kurir->id_kurir }}', '{{ $kurir->nama }}')">
                                                <strong>{{ $kurir->nama }}</strong><br>
                                                <small class="text-muted">{{ $kurir->alamat }}</small>
                                            </a>
                                        </li>
                                    @empty
                                        <li><span class="dropdown-item text-muted">Tidak ada kurir tersedia</span></li>
                                    @endforelse
                                </ul>
                            </div>
                            <input type="hidden" name="id_kurir" id="selected-kurir-id" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan Penugasan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Konfirmasi Pembatalan -->
    <div class="modal fade" id="modalBatal" tabindex="-1" aria-labelledby="modalBatalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="form-batal-pengiriman">
                @csrf
                <input type="hidden" name="id_pengiriman" id="id_pengiriman_batal">
                <input type="hidden" name="status" value="DIBATALKAN">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="modalBatalLabel">Batalkan Pengiriman</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="keterangan_batal" class="form-label">Alasan Pembatalan</label>
                            <textarea class="form-control" name="keterangan_batal" id="keterangan_batal" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Konfirmasi Batalkan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.socket.io/4.3.2/socket.io.min.js"></script>
        <script>
            const socket = io("http://localhost:4000");
            socket.on("update-data-pengiriman", function(data) {
                setTimeout(() => {
                    location.reload();
                }, 300);
            });
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

            function selectKurir(id, nama) {
                document.getElementById('selected-kurir-id').value = id;
                document.getElementById('dropdownKurirBtn').innerText = nama;
            }

            // Script penugasan Kurir
            document.querySelectorAll('.assign-kurir-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const pengirimanId = button.getAttribute('data-id');
                    document.getElementById('input-id-pengiriman').value = pengirimanId;
                    new bootstrap.Modal(document.getElementById('modalAssignKurir')).show();
                });
            });

            document.getElementById('form-assign-kurir').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                fetch("{{ route('admin.assign.kurir') }}", {
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire("Berhasil!", data.message, "success").then(() => location.reload());
                        } else {
                            Swal.fire("Gagal!", data.message, "error");
                        }
                    })
                    .catch(err => {
                        Swal.fire("Error", "Terjadi kesalahan sistem", "error");
                    });
            });

            // Script Batal
            const modal = document.getElementById('modalBatal');

            modal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');

                document.getElementById('id_pengiriman_batal').value = id;
            });

            document.getElementById('form-batal-pengiriman').addEventListener('submit', function(e) {
                e.preventDefault();

                const form = e.target;
                const formData = new FormData(form);

                fetch("{{ route('admin.pesanan.update.status') }}", {
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'ok') {
                            Swal.fire('Berhasil!', 'Pengiriman telah dibatalkan.', 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Gagal', 'Terjadi kesalahan saat membatalkan.', 'error');
                        }
                    })
                    .catch(err => {
                        Swal.fire('Error', 'Gagal menghubungi server.', 'error');
                    });
            });
        </script>
    @endpush
@endsection
