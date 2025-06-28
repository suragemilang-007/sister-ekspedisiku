@extends('layouts.admin')

@section('title', 'Semua Pesanan')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h1 class="mb-4">Semua Pesanan</h1>
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped" id="pesananTable">
                            <thead>
                                <tr>
                                    <th>No Resi</th>
                                    <th>Nama Pengirim</th>
                                    <th>Nama Penerima</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($semuaPesanan as $pesanan)
                                    <tr>
                                        <td>{{ $pesanan->nomor_resi }}</td>
                                        <td>{{ $pesanan->id_pengiriman }}</td>
                                        <td>{{ $pesanan->id_alamatPenjemputan }}</td>
                                        <td>{{ $pesanan->status }}</td>
                                        <td>
                                            <a href="{{ route('admin.pesanan.list') }}" class="btn btn-primary">Penugasan</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
@endsection