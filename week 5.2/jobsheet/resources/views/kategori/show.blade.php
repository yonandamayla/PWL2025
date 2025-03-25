@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
        </div>
        <div class="card-body">
            <p><strong>ID Kategori:</strong> {{ $kategori->kategori_id }}</p>
            <p><strong>Nama Kategori:</strong> {{ $kategori->kategori_nama }}</p>
            <a href="{{ url('/kategori') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
@endsection