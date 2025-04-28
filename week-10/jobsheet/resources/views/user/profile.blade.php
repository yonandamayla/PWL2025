@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="text-center mb-4">
                    <!-- Foto Profil -->
                    @if(auth()->user()->foto_profile)
                        <img src="{{ asset('storage/' . auth()->user()->foto_profile) }}?v={{ time() }}" 
                             alt="Profile Picture" 
                             class="img-circle elevation-2 profile-image" 
                             id="preview-image"
                             style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <img src="{{ asset('adminlte/dist/img/user2-160x160.jpg') }}" 
                             alt="Default Profile" 
                             class="img-circle elevation-2 profile-image"
                             id="preview-image" 
                             style="width: 150px; height: 150px; object-fit: cover;">
                    @endif
                    
                    <!-- Form Upload -->
                    <form action="{{ url('/user/update-photo') }}" method="POST" enctype="multipart/form-data" id="photo-form">
                        @csrf
                        <div class="mt-3">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="foto_profile" name="foto_profile" accept="image/jpeg,image/png,image/jpg">
                                    <label class="custom-file-label" for="foto_profile">Pilih foto</label>
                                </div>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">Upload</button>
                                </div>
                            </div>
                            <small class="form-text text-muted">Format: JPG, JPEG, PNG. Max: 2MB.</small>
                        </div>
                    </form>
                    
                    <!-- Pesan feedback -->
                    <div id="file-selected-message" class="alert alert-info mt-2" style="display: none;">
                        File berhasil dipilih. Klik tombol Upload untuk menyimpan.
                    </div>
                    
                    
                    @if(session('success'))
                        <div class="alert alert-success mt-3">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger mt-3">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger mt-3">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Profil</h3>
                    </div>
                    <div class="card-body">
                        <strong><i class="fas fa-user mr-1"></i> Nama</strong>
                        <p class="text-muted">{{ auth()->user()->nama }}</p>
                        <hr>
                        
                        <strong><i class="fas fa-id-badge mr-1"></i> Username</strong>
                        <p class="text-muted">{{ auth()->user()->username }}</p>
                        <hr>
                        
                        <strong><i class="fas fa-layer-group mr-1"></i> Level</strong>
                        <p class="text-muted">{{ auth()->user()->level->level_nama ?? 'Tidak ada' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var fileInput = document.getElementById('foto_profile');
        var previewImg = document.getElementById('preview-image');
        var fileLabel = document.querySelector('.custom-file-label');
        var fileMessage = document.getElementById('file-selected-message');
        
        fileInput.addEventListener('change', function() {
            var fileName = this.value.split('\\').pop();
            fileLabel.textContent = fileName;
            
            fileMessage.style.display = 'block';
            
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        // Reset form
        document.getElementById('photo-form').reset();
        fileLabel.textContent = 'Pilih foto';
    });
</script>
@endpush