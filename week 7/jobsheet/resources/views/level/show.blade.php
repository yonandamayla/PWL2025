@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
        </div>
        <div class="card-body">
            <p><strong>ID Level:</strong> {{ $level->level_id }}</p>
            <p><strong>Nama Level:</strong> {{ $level->level_nama }}</p>
            <a href="{{ url('/level') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
@endsection