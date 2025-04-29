@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 200px">Kode Supplier</th>
                        <td>{{ $supplier->supplier_kode }}</td>
                    </tr>
                    <tr>
                        <th>Nama Supplier</th>
                        <td>{{ $supplier->supplier_nama }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $supplier->supplier_alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Telepon</th>
                        <td>{{ $supplier->supplier_telp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $supplier->supplier_email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Kontak Person</th>
                        <td>{{ $supplier->supplier_kontak ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Dibuat</th>
                        <td>{{ $supplier->created_at ? $supplier->created_at->format('d-m-Y H:i:s') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Diupdate</th>
                        <td>{{ $supplier->updated_at ? $supplier->updated_at->format('d-m-Y H:i:s') : '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="mt-3">
                <a href="{{ url('/supplier') }}" class="btn btn-secondary">Kembali</a>
                <a href="{{ url('/supplier/' . $supplier->supplier_id . '/edit') }}" class="btn btn-warning">Edit</a>
                <form class="d-inline-block" method="POST" action="{{ url('/supplier/' . $supplier->supplier_id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin menghapus data ini?');">Hapus</button>
                </form>
            </div>
        </div>
    </div>
@endsection