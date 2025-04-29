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
                        <th style="width: 200px">Kode Barang</th>
                        <td>{{ $barang->barang_kode }}</td>
                    </tr>
                    <tr>
                        <th>Nama Barang</th>
                        <td>{{ $barang->barang_nama }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>{{ $barang->kategori->kategori_nama }}</td>
                    </tr>
                    <tr>
                        <th>Harga Beli</th>
                        <td>Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Harga Jual</th>
                        <td>Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Margin</th>
                        <td>Rp {{ number_format($barang->harga_jual - $barang->harga_beli, 0, ',', '.') }} 
                            ({{ round(($barang->harga_jual - $barang->harga_beli) / $barang->harga_beli * 100, 2) }}%)
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Dibuat</th>
                        <td>{{ $barang->created_at ? $barang->created_at->format('d-m-Y H:i:s') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Diupdate</th>
                        <td>{{ $barang->updated_at ? $barang->updated_at->format('d-m-Y H:i:s') : '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="mt-3">
                <a href="{{ url('/barang') }}" class="btn btn-secondary">Kembali</a>
                <a href="{{ url('/barang/' . $barang->barang_id . '/edit') }}" class="btn btn-warning">Edit</a>
                <form class="d-inline-block" method="POST" action="{{ url('/barang/' . $barang->barang_id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin menghapus data ini?');">Hapus</button>
                </form>
            </div>
        </div>
    </div>
@endsection