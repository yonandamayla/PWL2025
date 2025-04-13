$(document).ready(function () {
    // Initialize DataTable with improved styling
    var table = $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: USERS_ROUTE,
            data: function (d) {
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
    $('#role-filter').change(function () {
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

                    $.each(errors, function (field, messages) {
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
            complete: function () {
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
                switch (parseInt(user.role_id)) {
                    case 1: roleText = 'Admin'; break;
                    case 2: roleText = 'Kasir'; break;
                    case 3: roleText = 'Customer'; break;
                }

                $('#view-role').text(roleText);
                $('#view-created').text(new Date(user.created_at).toLocaleDateString('id-ID'));
                $('#viewUserModal').modal('show');
            },
            error: function () {
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
                switch (parseInt(user.role_id)) {
                    case 1: $('#role').val('admin'); break;
                    case 2: $('#role').val('kasir'); break;
                    case 3: $('#role').val('customer'); break;
                }

                $('#userModalLabel').text('Edit Pengguna');
                $('#userModal').modal('show');
            },
            error: function () {
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
            complete: function () {
                $button.html('<i class="fas fa-trash mr-1"></i> Hapus').prop('disabled', false);
            }
        });
    });
});