@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ url('/supplier') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="supplier_nama">Nama Supplier <span class="text-danger">*</span></label>
                    <input type="text" name="supplier_nama" id="supplier_nama" class="form-control" 
                        placeholder="Masukkan nama supplier" value="{{ old('supplier_nama') }}" required>
                    @error('supplier_nama')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="supplier_alamat">Alamat</label>
                    <textarea name="supplier_alamat" id="supplier_alamat" class="form-control" rows="3" 
                        placeholder="Masukkan alamat supplier">{{ old('supplier_alamat') }}</textarea>
                    @error('supplier_alamat')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="supplier_telp">Telepon</label>
                            <input type="text" name="supplier_telp" id="supplier_telp" class="form-control" 
                                placeholder="Masukkan nomor telepon" value="{{ old('supplier_telp') }}">
                            @error('supplier_telp')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="supplier_email">Email</label>
                            <input type="email" name="supplier_email" id="supplier_email" class="form-control" 
                                placeholder="Masukkan email supplier" value="{{ old('supplier_email') }}">
                            @error('supplier_email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="supplier_kontak">Kontak Person</label>
                    <input type="text" name="supplier_kontak" id="supplier_kontak" class="form-control" 
                        placeholder="Masukkan nama kontak person" value="{{ old('supplier_kontak') }}">
                    @error('supplier_kontak')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ url('/supplier') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection