@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ url('/level') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="level_nama">Nama Level</label>
                    <input type="text" name="level_nama" id="level_nama" class="form-control"
                        placeholder="Masukkan nama level" required>
                </div>
                <div class="form-group">
                    <label for="level_kode">Kode Level</label>
                    <input type="text" name="level_kode" id="level_kode" class="form-control"
                        placeholder="Kode level akan dihasilkan otomatis" readonly>
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