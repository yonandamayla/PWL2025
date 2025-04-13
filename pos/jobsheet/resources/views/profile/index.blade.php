@extends('layouts.template')

@section('content')
    @php
        $breadcrumb = (object) [
            'title' => 'Profil'
        ];
    @endphp

    <!-- Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light p-3 rounded">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profil</li>
            </ol>
        </nav>
    </div>

    <!-- Profile Content -->
    <div class="container-fluid p-0">
        <div class="row">
            <!-- Profile Sidebar -->
            <div class="col-md-4 col-lg-3">
                <div class="card profile-card mb-4">
                    <div class="card-body text-center pt-4 pb-4">
                        <div class="profile-image-container mb-3">
                            <img src="{{ auth()->user()->profile_picture ? asset(auth()->user()->profile_picture) : asset('images/default-avatar.png') }}"
                                class="profile-image" alt="{{ auth()->user()->name }}">
                            <button type="button" class="change-photo-btn" data-toggle="modal"
                                data-target="#changePhotoModal">
                                <i class="fas fa-camera"></i>
                            </button>
                        </div>
                        <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                        <span class="profile-role-badge {{ strtolower(auth()->user()->getRoleName()) }}">
                            {{ auth()->user()->getRoleName() }}
                        </span>
                        <p class="text-muted mt-3 mb-0">
                            <i class="fas fa-envelope mr-2"></i>{{ auth()->user()->email }}
                        </p>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Menu Profil</h5>
                    </div>
                    <div class="list-group list-group-flush profile-menu">
                        <a href="#personal-info" class="list-group-item list-group-item-action active"
                            data-toggle="tab">
                            <i class="fas fa-user-edit mr-2"></i> Informasi Pribadi
                        </a>
                        <a href="#security" class="list-group-item list-group-item-action" data-toggle="tab">
                            <i class="fas fa-lock mr-2"></i> Keamanan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Content -->
            <div class="col-md-8 col-lg-9">
                <div class="tab-content">
                    <!-- Personal Information Tab -->
                    <div class="tab-pane fade show active" id="personal-info">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Informasi Pribadi</h5>
                            </div>
                            <div class="card-body">
                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                <form action="{{ route('profile.update') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="name">Nama Lengkap</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', auth()->user()->name) }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Alamat Email</label>
                                        <input type="email"
                                            class="form-control @error('email') is-invalid @enderror" id="email"
                                            name="email" value="{{ old('email', auth()->user()->email) }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Security Tab -->
                    <div class="tab-pane fade" id="security">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Keamanan</h5>
                            </div>
                            <div class="card-body">
                                @if(session('password_success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="fas fa-check-circle mr-2"></i> {{ session('password_success') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                <form action="{{ route('profile.password') }}" method="POST"
                                    id="passwordChangeForm">
                                    @csrf
                                    <div class="form-group">
                                        <label for="current_password">Kata Sandi Saat Ini</label>
                                        <input type="password"
                                            class="form-control @error('current_password') is-invalid @enderror"
                                            id="current_password" name="current_password">
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="password">Kata Sandi Baru</label>
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation">
                                    </div>

                                    <button type="submit" class="btn btn-primary" id="changePasswordBtn">
                                        <i class="fas fa-key mr-2"></i> Ubah Kata Sandi
                                    </button>
                                </form>

                                <!-- Success message that will appear immediately after submission -->
                                <div class="alert alert-success mt-3 d-none" id="passwordSuccessMsg">
                                    <i class="fas fa-check-circle mr-2"></i> Kata sandi berhasil diperbarui.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Photo Upload Modal -->
    <div class="modal fade" id="changePhotoModal" tabindex="-1" role="dialog" aria-labelledby="changePhotoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePhotoModalLabel">Ubah Foto Profil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="photoUploadForm" action="{{ route('profile.photo') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="text-center mb-4">
                            <div id="imagePreviewContainer" class="mb-3">
                                <img id="imagePreview"
                                    src="{{ auth()->user()->profile_picture ? asset(auth()->user()->profile_picture) : asset('images/default-avatar.png') }}"
                                    alt="Preview" class="img-fluid rounded-circle"
                                    style="width: 150px; height: 150px; object-fit: cover;">
                            </div>

                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="profile_picture" name="profile_picture"
                                    accept="image/*">
                                <label class="custom-file-label" for="profile_picture">Pilih Gambar</label>
                            </div>

                            <small class="form-text text-muted">
                                Format yang diperbolehkan: JPG, JPEG, PNG. Ukuran maksimal: 2MB.
                            </small>
                        </div>

                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Foto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@push('js')
    <script src="{{ asset('js/profile.js') }}"></script>
@endpush