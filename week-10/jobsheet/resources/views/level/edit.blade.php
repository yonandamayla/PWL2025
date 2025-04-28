@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Edit Level</h3>
        </div>
        <div class="card-body">
            <form action="{{ url('/level/' . $level->level_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="level_nama">Nama Level</label>
                    <input type="text" name="level_nama" id="level_nama" class="form-control" value="{{ $level->level_nama }}" required>
                </div>
                <div class="form-group">
                    <label for="level_kode">Kode Level</label>
                    <input type="text" name="level_kode" id="level_kode" class="form-control" value="{{ $level->level_kode }}" readonly>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ url('/level') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        // Generate kode level otomatis berdasarkan nama level
        document.getElementById('level_nama').addEventListener('input', function () {
            const levelNama = this.value;
            const levelKode = levelNama.substring(0, 3).toUpperCase(); // Ambil 3 huruf pertama dan ubah ke huruf besar
            document.getElementById('level_kode').value = levelKode;
        });
    </script>
@endpush