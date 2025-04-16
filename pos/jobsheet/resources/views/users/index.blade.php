@extends('layouts.template')

@section('content')
    @section('styles')
        <link rel="stylesheet" href="{{ asset('css/users.css') }}">
    @endsection

    @php
        $breadcrumb = (object) [
            'title' => 'Daftar Pengguna'
        ];
    @endphp

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light p-3 rounded">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Daftar Pengguna</li>
            </ol>
        </nav>
        <div>
            <button type="button" class="btn btn-success btn-sm mr-2" data-toggle="modal" data-target="#importModal">
                <i class="fas fa-file-upload mr-1"></i> Import Excel
            </button>
            <a href="{{ route('users.export') }}" class="btn btn-primary btn-sm mr-2">
                <i class="fas fa-file-download mr-1"></i> Export Excel
            </a>
            <button class="btn btn-primary btn-sm" id="btn-add-user">
                <i class="fas fa-plus-circle"></i> Tambah Pengguna
            </button>
        </div>
    </div>


    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Role Filter -->
            <div class="row mb-3 filter-section">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="role-filter"><i class="fas fa-filter mr-1"></i> Filter Berdasarkan Peran:</label>
                        <select id="role-filter" class="form-control form-control-sm">
                            <option value="">Semua Peran</option>
                            <option value="1">Admin</option>
                            <option value="2">Kasir</option>
                            <option value="3">Customer</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="users-table" class="table table-striped table-borderless table-hover nowrap" width="100%">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Peran</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded by AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- User Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="userModalLabel">Tambah Pengguna</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="userForm">
                        @csrf
                        <input type="hidden" name="user_id" id="user_id">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback" id="name-error"></div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback" id="email-error"></div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password</small>
                            <div class="invalid-feedback" id="password-error"></div>
                        </div>
                        <div class="form-group">
                            <label for="role">Peran</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="admin">Admin</option>
                                <option value="kasir">Kasir</option>
                                <option value="customer">Customer</option>
                            </select>
                            <div class="invalid-feedback" id="role-error"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="saveUser">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Anda yakin ingin menghapus pengguna ini?</p>
                    <input type="hidden" id="delete_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View User Modal -->
    <div class="modal fade" id="viewUserModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Detail Pengguna</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless">
                        <tr>
                            <th>Nama:</th>
                            <td id="view-name"></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td id="view-email"></td>
                        </tr>
                        <tr>
                            <th>Peran:</th>
                            <td id="view-role"></td>
                        </tr>
                        <tr>
                            <th>Terdaftar pada:</th>
                            <td id="view-created"></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data Pengguna</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="excel_file">Pilih File Excel</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="excel_file" name="excel_file" required
                                    accept=".xlsx, .xls">
                                <label class="custom-file-label" for="excel_file">Pilih file...</label>
                            </div>
                            <small class="form-text text-muted">Format: Excel (.xlsx, .xls)</small>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-1"></i> File Excel harus memiliki kolom: nama, email, dan role.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        // Define variables for use in external JS file
        const USERS_ROUTE = "{{ route('users.list') }}";
        // Show file name when selecting a file
        $(document).ready(function () {
            $('.custom-file-input').on('change', function () {
                var fileName = $(this).val().split('\\').pop();
                $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
            });
        });
    </script>
    <script src="{{ asset('js/users.js') }}"></script>
@endsection