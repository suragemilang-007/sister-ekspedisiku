@extends('layouts.admin')

@section('title', 'Pesanan Baru')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-truck me-2"></i>
                            Penugasan Kurir
                        </h4>
                        <div>
                            <button class="btn btn-success btn-sm" onclick="refreshData()">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filter Section -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select class="form-select" id="statusFilter">
                                    <option value="">Semua Status</option>
                                    <option value="MENUNGGU KONFIRMASI">Menunggu Konfirmasi</option>
                                    <option value="DIPROSES">Diproses</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" class="form-control" id="dateFrom" placeholder="Dari Tanggal">
                            </div>
                            <div class="col-md-3">
                                <input type="date" class="form-control" id="dateTo" placeholder="Sampai Tanggal">
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" id="searchInput"
                                    placeholder="Cari nomor resi atau nama pengirim">
                            </div>
                        </div>

                        <!-- Table Section -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor Resi</th>
                                        <th>Pengirim</th>
                                        <th>Tujuan</th>
                                        <th>Total Biaya</th>
                                        <th>Status</th>
                                        <th>Kurir</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="pengirimanTableBody">
                                    @foreach ($pengiriman as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $item->nomor_resi }}</strong>
                                            </td>
                                            <td>
                                                {{ $item->pengirim->nama ?? 'N/A' }}<br>
                                                <small class="text-muted">{{ $item->pengirim->email ?? '' }}</small>
                                            </td>
                                            <td>
                                                {{ $item->alamatTujuan->nama_penerima ?? 'N/A' }}<br>
                                                <small class="text-muted">{{ $item->alamatTujuan->kecamatan ?? '' }}</small>
                                            </td>
                                            <td>
                                                <strong>Rp {{ number_format($item->total_biaya, 0, ',', '.') }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $item->status_color }}">
                                                    {{ $item->status }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($item->penugasanKurir)
                                                    <strong>{{ $item->penugasanKurir->kurir->nama }}</strong><br>
                                                    <small
                                                        class="text-muted">{{ $item->penugasanKurir->kurir->nohp }}</small>
                                                @else
                                                    <span class="text-muted">Belum ditugaskan</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $item->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if ($item->status == 'MENUNGGU KONFIRMASI')
                                                        <button class="btn btn-primary btn-sm"
                                                            onclick="assignKurir({{ $item->id_pengiriman }})"
                                                            title="Assign Kurir">
                                                            <i class="fas fa-user-plus"></i>
                                                        </button>
                                                    @endif
                                                    @if ($item->penugasanKurir)
                                                        <button class="btn btn-info btn-sm"
                                                            onclick="editAssignment({{ $item->id_pengiriman }})"
                                                            title="Edit Penugasan">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    @endif
                                                    <button class="btn btn-secondary btn-sm"
                                                        onclick="viewDetail({{ $item->id_pengiriman }})"
                                                        title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @if (in_array($item->status, ['MENUNGGU KONFIRMASI', 'DIPROSES']))
                                                        <button class="btn btn-danger btn-sm"
                                                            onclick="cancelPengiriman({{ $item->id_pengiriman }})"
                                                            title="Batalkan">
                                                            <i class="fas fa-times"></i>
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
                        <nav aria-label="Page navigation" id="paginationNav" style="display: none;">
                            <ul class="pagination justify-content-center" id="paginationList">
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Assign Kurir -->
    <div class="modal fade" id="assignKurirModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Kurir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="assignKurirForm">
                        <input type="hidden" id="assign_id_pengiriman" name="id_pengiriman">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nomor Resi</label>
                                <input type="text" class="form-control" id="assign_nomor_resi" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Total Biaya</label>
                                <input type="text" class="form-control" id="assign_total_biaya" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pilih Kurir <span class="text-danger">*</span></label>
                            <select class="form-select" id="assign_id_kurir" name="id_kurir" required>
                                <option value="">Pilih Kurir</option>
                                @foreach ($kurirAktif as $kurir)
                                    <option value="{{ $kurir->id_kurir }}">
                                        {{ $kurir->nama }} - {{ $kurir->nohp }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea class="form-control" id="assign_catatan" name="catatan" rows="3"
                                placeholder="Catatan untuk kurir (opsional)"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="submitAssignKurir()">
                        <i class="fas fa-save"></i> Assign Kurir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cancel Pengiriman -->
    <div class="modal fade" id="cancelModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Batalkan Pengiriman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="cancelForm">
                        <input type="hidden" id="cancel_id_pengiriman">

                        <div class="mb-3">
                            <label class="form-label">Nomor Resi</label>
                            <input type="text" class="form-control" id="cancel_nomor_resi" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan Pembatalan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="cancel_keterangan_batal" name="keterangan_batal" rows="4"
                                placeholder="Masukkan alasan pembatalan..." required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="submitCancel()">
                        <i class="fas fa-ban"></i> Batalkan Pengiriman
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @push('scripts')
        <script>
            // Setup CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Assign Kurir
            function assignKurir(idPengiriman) {
                $.get(`/admin/api/pengiriman-detail/${idPengiriman}`)
                    .done(function(response) {
                        if (response.status === 'success') {
                            console.log(response.data);
                            $('#assign_id_pengiriman').val(response.data.id_pengiriman);
                            $('#assign_nomor_resi').val(response.data.nomor_resi);
                            $('#assign_total_biaya').val('Rp ' + new Intl.NumberFormat('id-ID').format(response.data
                                .total_biaya));
                            $('#assignKurirModal').modal('show');
                        }
                    })
                    .fail(function() {
                        alert('Gagal mengambil data pengiriman');
                    });
            }

            function submitAssignKurir() {
                const formData = {
                    id_pengiriman: $('#assign_id_pengiriman').val(),
                    id_kurir: $('#assign_id_kurir').val(),
                    catatan: $('#assign_catatan').val()
                };

                $.post('/admin/penugasan-kurir', formData)
                    .done(function(response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            $('#assignKurirModal').modal('hide');
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    })
                    .fail(function(xhr) {
                        const response = xhr.responseJSON;
                        if (response && response.errors) {
                            let errorMsg = 'Validasi gagal:\n';
                            for (const field in response.errors) {
                                errorMsg += `- ${response.errors[field].join(', ')}\n`;
                            }
                            alert(errorMsg);
                        } else {
                            alert('Terjadi kesalahan sistem');
                        }
                    });
            }

            // Cancel Pengiriman
            function cancelPengiriman(idPengiriman) {
                $.get(`/admin/api/pengiriman-detail/${idPengiriman}`)
                    .done(function(response) {
                        if (response.status === 'success') {
                            $('#cancel_id_pengiriman').val(response.data.id_pengiriman);
                            $('#cancel_nomor_resi').val(response.data.nomor_resi);
                            $('#cancelModal').modal('show');
                        }
                    })
                    .fail(function() {
                        alert('Gagal mengambil data pengiriman');
                    });
            }

            function submitCancel() {
                const idPengiriman = $('#cancel_id_pengiriman').val();
                const keteranganBatal = $('#cancel_keterangan_batal').val();

                if (!keteranganBatal.trim()) {
                    alert('Keterangan pembatalan harus diisi');
                    return;
                }

                $.post(`/admin/penugasan-kurir/${idPengiriman}/cancel`, {
                        keterangan_batal: keteranganBatal
                    })
                    .done(function(response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            $('#cancelModal').modal('hide');
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    })
                    .fail(function() {
                        alert('Terjadi kesalahan sistem');
                    });
            }

            // View Detail
            function viewDetail(idPengiriman) {
                window.open(`/admin/penugasan-kurir/${idPengiriman}`, '_blank');
            }

            // Edit Assignment
            function editAssignment(idPengiriman) {
                window.location.href = `/admin/penugasan-kurir/${idPengiriman}/edit`;
            }

            // Refresh Data
            function refreshData() {
                location.reload();
            }

            // Filter functionality
            $('#statusFilter, #dateFrom, #dateTo, #searchInput').on('change keyup', function() {
                loadPengirimanData();
            });

            function loadPengirimanData() {
                const params = {
                    status: $('#statusFilter').val(),
                    date_from: $('#dateFrom').val(),
                    date_to: $('#dateTo').val(),
                    search: $('#searchInput').val()
                };

                $.get('/admin/api/pengiriman-list', params)
                    .done(function(response) {
                        if (response.status === 'success') {
                            updateTable(response.data);
                            updatePagination(response.pagination);
                        }
                    })
                    .fail(function() {
                        alert('Gagal memuat data');
                    });
            }

            function updateTable(data) {
                let html = '';
                data.forEach((item, index) => {
                    html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td><strong>${item.nomor_resi}</strong></td>
                        <td>
                            ${item.pengirim?.nama || 'N/A'}<br>
                            <small class="text-muted">${item.pengirim?.email || ''}</small>
                        </td>
                        <td>
                            ${item.alamat_tujuan?.nama_penerima || 'N/A'}<br>
                            <small class="text-muted">${item.alamat_tujuan?.kecamatan || ''}</small>
                        </td>
                        <td><strong>Rp ${new Intl.NumberFormat('id-ID').format(item.total_biaya)}</strong></td>
                        <td><span class="badge bg-${getStatusColor(item.status)}">${item.status}</span></td>
                        <td>
                            ${item.penugasan_kurir ? 
                                `<strong>${item.penugasan_kurir.kurir.nama}</strong><br><small class="text-muted">${item.penugasan_kurir.kurir.nohp}</small>` : 
                                '<span class="text-muted">Belum ditugaskan</span>'
                            }
                        </td>
                        <td>${new Date(item.created_at).toLocaleDateString('id-ID')}</td>
                        <td>
                            <div class="btn-group" role="group">
                                ${item.status === 'MENUNGGU KONFIRMASI' ? 
                                    `<button class="btn btn-primary btn-sm" onclick="assignKurir(${item.id_pengiriman})" title="Assign Kurir"><i class="fas fa-user-plus"></i></button>` : 
                                    ''
                                }
                                ${item.penugasan_kurir ? 
                                    `<button class="btn btn-info btn-sm" onclick="editAssignment(${item.id_pengiriman})" title="Edit Penugasan"><i class="fas fa-edit"></i></button>` : 
                                    ''
                                }
                                <button class="btn btn-secondary btn-sm" onclick="viewDetail(${item.id_pengiriman})" title="Lihat Detail"><i class="fas fa-eye"></i></button>
                                ${['MENUNGGU KONFIRMASI', 'DIPROSES'].includes(item.status) ? 
                                    `<button class="btn btn-danger btn-sm" onclick="cancelPengiriman(${item.id_pengiriman})" title="Batalkan"><i class="fas fa-times"></i></button>` : 
                                    ''
                                }
                            </div>
                        </td>
                    </tr>
                `;
                });
                $('#pengirimanTableBody').html(html);
            }

            function getStatusColor(status) {
                switch (status) {
                    case 'MENUNGGU KONFIRMASI':
                        return 'warning';
                    case 'DIPROSES':
                        return 'primary';
                    case 'DIBAYAR':
                        return 'info';
                    case 'DIKIRIM':
                        return 'success';
                    case 'DITERIMA':
                        return 'primary';
                    case 'DIBATALKAN':
                        return 'danger';
                    default:
                        return 'secondary';
                }
            }

            function updatePagination(pagination) {
                if (pagination.last_page > 1) {
                    $('#paginationNav').show();
                    // Implement pagination logic here
                } else {
                    $('#paginationNav').hide();
                }
            }
        </script>
    @endpush
@endsection
