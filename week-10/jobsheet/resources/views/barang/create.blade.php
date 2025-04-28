@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ url('/barang') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="barang_nama">Nama Barang</label>
                    <input type="text" name="barang_nama" id="barang_nama" class="form-control" 
                        placeholder="Masukkan nama barang" value="{{ old('barang_nama') }}" required>
                    @error('barang_nama')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="kategori_id">Kategori</label>
                    <select name="kategori_id" id="kategori_id" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->kategori_id }}" {{ old('kategori_id') == $kategori->kategori_id ? 'selected' : '' }}>
                                {{ $kategori->kategori_nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="harga_beli">Harga Beli</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" name="harga_beli" id="harga_beli" class="form-control" 
                                    placeholder="Masukkan harga beli" value="{{ old('harga_beli') }}" required>
                            </div>
                            @error('harga_beli')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="harga_jual">Harga Jual</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" name="harga_jual" id="harga_jual" class="form-control" 
                                    placeholder="Masukkan harga jual" value="{{ old('harga_jual') }}" required>
                            </div>
                            @error('harga_jual')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ url('/barang') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        // Validasi harga jual harus lebih besar dari harga beli
        document.getElementById('harga_jual').addEventListener('change', function() {
            const hargaBeli = parseFloat(document.getElementById('harga_beli').value) || 0;
            const hargaJual = parseFloat(this.value) || 0;
            
            if (hargaJual <= hargaBeli) {
                alert('Harga jual sebaiknya lebih besar dari harga beli');
            }
        });
    </script>
@endpush