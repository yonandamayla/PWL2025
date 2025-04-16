$(document).ready(function () {
    // Initialize DataTable with improved styling
    var table = $('#items-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        order: [[0, 'desc']], // Default order by first column (DT_RowIndex) in descending order
        ajax: {
            url: ITEMS_ROUTE,
            data: function (d) {
                d.item_type_id = $('#type-filter').val();
                d.stock_filter = $('#stock-filter').val();
                d.order_by = 'id'; //  ensure server knows we want to order by ID
                d.order_direction = 'desc'; // And in descending order
            },
            error: function (xhr, error, thrown) {
                console.error('DataTables error:', error, thrown);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat memuat data barang.'
                });
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            {
                data: 'image_url',
                name: 'image',
                orderable: false,
                searchable: false,
                render: function (data) {
                    return `<div class="item-thumbnail-container">
                            <img src="${data}" class="item-thumbnail" alt="Gambar Produk" 
                                onerror="this.onerror=null;this.src='/images/no-image.png';"
                                onload="this.style.display='block';">
                            </div>`;
                }
            },
            { data: 'name', name: 'name' },
            { data: 'type_name', name: 'item_type_id' },
            { data: 'formatted_price', name: 'price' },
            { data: 'stock_status', name: 'stock' },
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
            '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
    });

    // Filter change events
    $('#type-filter, #stock-filter').change(function () {
        table.draw(); // Redraw the table with the filter
    });

    // Reset form errors
    function resetFormErrors() {
        $('#itemForm .is-invalid').removeClass('is-invalid');
        $('#itemForm .invalid-feedback').text('');
    }

    // File input change handler for image preview
    $('#image').change(function () {
        if (this.files && this.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#image-preview').attr('src', e.target.result);
            }

            reader.readAsDataURL(this.files[0]);

            // Update the file input label
            $('.custom-file-label').text(this.files[0].name);
        }
    });

    // Add Item button (only available for admin)
    $('#btn-add-item').click(function () {
        resetFormErrors();
        $('#itemForm')[0].reset();
        $('#item_id').val('');
        $('#image-preview').attr('src', '/images/no-image.png');
        $('.custom-file-label').text('Pilih gambar');
        $('#itemModalLabel').text('Tambah Barang');
        $('#itemModal').modal('show');
    });

    // Save Item (create or update)
$('#saveItem').click(function() {
    resetFormErrors();
    
    var formData = new FormData($('#itemForm')[0]);
    var itemId = $('#item_id').val();
    var url = itemId ? '/items/' + itemId : '/items';
    var method = itemId ? 'POST' : 'POST';
    
    // If updating (itemId exists) and no new image is selected, don't send the image field
    if (itemId && $('#image')[0].files.length === 0) {
        formData.delete('image');
    }
    
    // For PUT/PATCH requests with FormData, we need to append the _method field
    if (itemId) {
        formData.append('_method', 'PUT');
    }
    
    $.ajax({
        url: url,
        type: method,
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            $('#itemModal').modal('hide');
            
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.message,
                timer: 2000,
                showConfirmButton: false
            });
            
            $('#items-table').DataTable().ajax.reload();
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                var errors = xhr.responseJSON.errors;
                $.each(errors, function(key, value) {
                    $('#' + key).addClass('is-invalid');
                    $('#' + key + '-error').text(value[0]);
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: xhr.responseJSON.error || 'Terjadi kesalahan pada server'
                });
            }
        }
    });
});

    // View Item details
    $('#items-table').on('click', '.view-btn', function () {
        var id = $(this).data('id');

        $.ajax({
            url: '/items/' + id,
            method: 'GET',
            success: function (response) {
                var item = response.data;
                $('#view-name').text(item.name);
                $('#view-price').text('Rp ' + parseFloat(item.price).toLocaleString('id-ID'));
                $('#view-category').text(item.item_type ? item.item_type.name : '-');
                $('#view-description').text(item.description || '-');

                // Display stock status with badge
                var stockHtml = item.stock;
                if (item.stock <= 0) {
                    stockHtml += ' <span class="badge badge-danger">Habis</span>';
                } else if (item.stock < 10) {
                    stockHtml += ' <span class="badge badge-warning">Rendah</span>';
                } else {
                    stockHtml += ' <span class="badge badge-success">Tersedia</span>';
                }
                $('#view-stock').html(stockHtml);

                // Display image
                $('#view-image').attr('src', response.image_url);

                $('#viewItemModal').modal('show');
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Gagal memuat detail barang'
                });
            }
        });
    });

    // Edit Item (only available for admin)
    $('#items-table').on('click', '.edit-btn', function () {
        var id = $(this).data('id');
        resetFormErrors();

        // Reset the form before populating with new data
        $('#itemForm')[0].reset();

        $.ajax({
            url: '/items/' + id,
            method: 'GET',
            success: function (response) {
                var item = response.data;
                $('#item_id').val(item.id);
                $('#name').val(item.name);
                $('#description').val(item.description);
                $('#price').val(item.price);
                $('#stock').val(item.stock);
                $('#item_type_id').val(item.item_type_id);

                // Display current image
                $('#image-preview').attr('src', response.image_url);
                // Don't require a new image for edits
                $('#image').removeAttr('required');
                $('.custom-file-label').text('Ganti gambar (opsional)');

                $('#itemModalLabel').text('Edit Barang');
                $('#itemModal').modal('show');
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Gagal memuat data barang'
                });
            }
        });
    });

    // Delete Item confirmation (only available for admin)
    $('#items-table').on('click', '.delete-btn', function () {
        $('#delete_id').val($(this).data('id'));
        $('#deleteModal').modal('show');
    });

    // Confirm Delete
    $('#confirmDelete').click(function () {
        var id = $('#delete_id').val();
        var $button = $(this);

        $button.html('<i class="fas fa-spinner fa-spin"></i> Menghapus...').prop('disabled', true);

        $.ajax({
            url: '/items/' + id,
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
                    text: 'Barang berhasil dihapus',
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