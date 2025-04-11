<!-- filepath: d:\Apps\laragon\www\PWL2025\pos\jobsheet\resources\views\users\index.blade.php -->
@extends('layouts.template')

@section('content')
    <div class="container-fluid">
        <div class="card shadow rounded">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-primary">
                        <i class="fas fa-users mr-2"></i> Data Pengguna
                    </h5>
                    <button class="btn btn-primary btn-sm" id="btn-add-user">
                        <i class="fas fa-plus-circle"></i> Tambah Pengguna
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Role Filter -->
                <div class="row mb-3">
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
                                <th>Status</th>
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
                    <div class="text-center mb-3">
                        <img id="user-avatar" src="{{ asset('images/default-avatar.png') }}" class="img-circle" width="100" height="100">
                    </div>
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
                            <th>Status:</th>
                            <td id="view-status"></td>
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
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // Initialize DataTable with improved styling
            var table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('users.list') }}",
                    data: function(d) {
                        d.role_id = $('#role-filter').val();
                    },
                    error: function (xhr, error, thrown) {
                        console.error('DataTables error:', error, thrown);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan saat memuat data pengguna.'
                        });
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    {
                        data: 'role_id', name: 'role_id', render: function (data) {
                            switch (parseInt(data)) {
                                case 1: return '<span class="badge badge-primary">Admin</span>';
                                case 2: return '<span class="badge badge-info">Kasir</span>';
                                case 3: return '<span class="badge badge-secondary">Customer</span>';
                                default: return '<span class="badge badge-light">Unknown</span>';
                            }
                        }
                    },
                    {
                        data: 'is_active', name: 'is_active', render: function (data) {
                            return data ? 
                                '<span class="badge badge-success">Active</span>' : 
                                '<span class="badge badge-danger">Inactive</span>';
                        }
                    },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                language: {
                    processing: "<div class='spinner-border text-primary' role='status'><span class='sr-only'>Memproses...</span></div>",
                    search: "<i class='fas fa-search'></i> Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data yang tersedia",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    zeroRecords: "Tidak ditemukan data yang sesuai",
                    paginate: {
                        first: "<i class='fas fa-angle-double-left'></i>",
                        last: "<i class='fas fa-angle-double-right'></i>",
                        next: "<i class='fas fa-angle-right'></i>",
                        previous: "<i class='fas fa-angle-left'></i>"
                    }
                },
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                buttons: [
                    {
                        extend: 'copy',
                        className: 'btn-sm btn-secondary',
                        text: '<i class="fas fa-copy"></i> Copy'
                    },
                    {
                        extend: 'excel',
                        className: 'btn-sm btn-success',
                        text: '<i class="fas fa-file-excel"></i> Excel'
                    },
                    {
                        extend: 'pdf',
                        className: 'btn-sm btn-danger',
                        text: '<i class="fas fa-file-pdf"></i> PDF'
                    },
                    {
                        extend: 'print',
                        className: 'btn-sm btn-info',
                        text: '<i class="fas fa-print"></i> Print'
                    }
                ]
            });

            // Role filter change event
            $('#role-filter').change(function() {
                table.draw(); // Redraw the table with the filter
            });

            // Reset form errors
            function resetFormErrors() {
                $('#userForm .is-invalid').removeClass('is-invalid');
                $('#userForm .invalid-feedback').text('');
            }

            // Add User button
            $('#btn-add-user').click(function () {
                resetFormErrors();
                $('#userForm')[0].reset();
                $('#user_id').val('');
                $('#userModalLabel').text('Tambah Pengguna');
                $('#userModal').modal('show');
            });

            // Save User (Create or Update)
            $('#saveUser').click(function () {
                var id = $('#user_id').val();
                var method = id ? 'PUT' : 'POST';
                var url = id ? '/users/' + id : '/users';
                var formData = $('#userForm').serialize();

                // Add loading state to button
                var $button = $(this);
                $button.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $('#userModal').modal('hide');
                        table.ajax.reload();
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data pengguna berhasil disimpan',
                            timer: 1500
                        });
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            resetFormErrors();
                            var errors = xhr.responseJSON.errors;
                            
                            $.each(errors, function(field, messages) {
                                var input = $('#' + field);
                                input.addClass('is-invalid');
                                $('#' + field + '-error').text(messages[0]);
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Terjadi kesalahan. Silakan coba lagi.'
                            });
                        }
                    },
                    complete: function() {
                        // Reset button state
                        $button.html('<i class="fas fa-save mr-1"></i> Simpan').prop('disabled', false);
                    }
                });
            });

            // View User
            $('#users-table').on('click', '.view-btn', function () {
                var id = $(this).data('id');

                $.ajax({
                    url: '/users/' + id,
                    method: 'GET',
                    success: function (response) {
                        var user = response.data;
                        $('#view-name').text(user.name);
                        $('#view-email').text(user.email);
                        
                        var roleText = 'Unknown';
                        switch(parseInt(user.role_id)) {
                            case 1: roleText = 'Admin'; break;
                            case 2: roleText = 'Kasir'; break;
                            case 3: roleText = 'Customer'; break;
                        }
                        
                        $('#view-role').text(roleText);
                        $('#view-status').html(user.is_active ? 
                            '<span class="badge badge-success">Active</span>' : 
                            '<span class="badge badge-danger">Inactive</span>');
                            
                        $('#view-created').text(new Date(user.created_at).toLocaleDateString('id-ID'));
                        $('#viewUserModal').modal('show');
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Gagal memuat detail pengguna'
                        });
                    }
                });
            });

            // Edit User
            $('#users-table').on('click', '.edit-btn', function () {
                var id = $(this).data('id');
                resetFormErrors();

                $.ajax({
                    url: '/users/' + id,
                    method: 'GET',
                    success: function (response) {
                        var user = response.data;
                        $('#user_id').val(user.id);
                        $('#name').val(user.name);
                        $('#email').val(user.email);
                        $('#password').val(''); // Clear password field
                        
                        // Set role dropdown value based on role_id
                        switch(parseInt(user.role_id)) {
                            case 1: $('#role').val('admin'); break;
                            case 2: $('#role').val('kasir'); break;
                            case 3: $('#role').val('customer'); break;
                        }
                        
                        $('#userModalLabel').text('Edit Pengguna');
                        $('#userModal').modal('show');
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Gagal memuat data pengguna'
                        });
                    }
                });
            });

            // Delete User
            $('#users-table').on('click', '.delete-btn', function () {
                $('#delete_id').val($(this).data('id'));
                $('#deleteModal').modal('show');
            });

            // Confirm Delete
            $('#confirmDelete').click(function () {
                var id = $('#delete_id').val();
                var $button = $(this);
                
                $button.html('<i class="fas fa-spinner fa-spin"></i> Menghapus...').prop('disabled', true);

                $.ajax({
                    url: '/users/' + id,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $('#deleteModal').modal('hide');
                        table.ajax.reload();
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Pengguna berhasil dihapus',
                            timer: 1500
                        });
                    },
                    error: function (xhr) {
                        var message = 'Terjadi kesalahan. Silakan coba lagi.';
                        
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            message = xhr.responseJSON.error;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Menghapus',
                            text: message
                        });
                    },
                    complete: function() {
                        $button.html('<i class="fas fa-trash mr-1"></i> Hapus').prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endsection