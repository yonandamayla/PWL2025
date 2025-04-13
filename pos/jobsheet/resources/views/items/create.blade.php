@extends('layouts.template')

@section('title', 'Tambah Barang Baru')

@section('content_header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah Barang Baru</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/items') }}">Barang</a></li>
                        <li class="breadcrumb-item active">Tambah Baru</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Form Tambah Barang</h3>
                    </div>
                    
                    <form method="POST" action="{{ url('/items') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Nama Barang <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                            id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="item_type_id">Kategori <span class="text-danger">*</span></label>
                                        <select class="form-control select2 @error('item_type_id') is-invalid @enderror" 
                                            id="item_type_id" name="item_type_id" required>
                                            <option value="">Pilih Kategori</option>
                                            @foreach($itemTypes as $type)
                                                <option value="{{ $type->id }}" {{ old('item_type_id') == $type->id ? 'selected' : '' }}>
                                                    {{ $type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('item_type_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="price">Harga <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                                id="price" name="price" value="{{ old('price') }}" min="0" required>
                                        </div>
                                        @error('price')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="stock">Stok <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                            id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" required>
                                        @error('stock')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description">Deskripsi</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                            id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="photo">Foto Barang</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input @error('photo') is-invalid @enderror" 
                                                    id="photo" name="photo" accept="image/*">
                                                <label class="custom-file-label" for="photo">Pilih file</label>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">Format: jpg, jpeg, png. Maks: 2MB</small>
                                        @error('photo')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Preview</label>
                                        <div class="text-center">
                                            <img id="preview" src="{{ asset('images/no-image.png') }}" 
                                                class="img-fluid img-thumbnail" style="max-height: 200px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ url('/items') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(function() {
        // Initialize Select2
        $('.select2').select2({
            width: '100%'
        });
        
        // Preview image before upload
        $("#photo").change(function() {
            readURL(this);
        });
        
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    $('#preview').attr('src', e.target.result);
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        // Custom file input
        $(document).on('change', '.custom-file-input', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass('selected').html(fileName);
        });
    });
</script>
@endsection