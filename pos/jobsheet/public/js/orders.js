$(document).ready(function () {
    // Initialize DataTable - Only for list view
    if (!appData.hasOrderId && !appData.hasView) {
        var columns = [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'order_number', name: 'id' },
            // Conditionally include customer column only for admin or cashier
            ...(appData.isAdminOrCashier ? [{ data: 'customer_name', name: 'user.name' }] : []),
            { data: 'date_formatted', name: 'created_at' },
            { data: 'items_count', name: 'items_count', searchable: false },
            { data: 'total_formatted', name: 'total_price' },
            { data: 'status_label', name: 'status' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ];

        var table = $('#ordersTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: appData.ordersListUrl,
                data: function (d) {
                    d.status = $('#statusFilter').val();
                }
            },
            columns: columns,
            order: [[appData.isAdminOrCashier ? 3 : 2, 'desc']], // Sort by date descending, adjusting for column position
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
                search: "<i class='fas fa-search'></i> Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data yang ditampilkan",
                infoFiltered: "(disaring dari total _MAX_ data)",
                zeroRecords: "Tidak ada data yang cocok",
                emptyTable: "Tidak ada data dalam tabel",
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

        // Auto refresh on filter change
        $('#statusFilter').change(function () {
            table.ajax.reload();
        });
    }

// Process Order with SweetAlert2 confirmation - Only for admin/cashier
$(document).on('click', '.process-btn', function () {
    var orderId = $(this).data('id');
    
    Swal.fire({
        title: 'Proses & Selesaikan Pesanan?',
        text: "Anda akan memproses pesanan ini. Pesanan yang diproses akan segera disiapkan untuk pelanggan.",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Proses!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: '/orders/' + orderId + '/status',
                method: 'POST',
                data: {
                    _token: appData.csrfToken,
                    status: 'completed'
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Selesai!',
                            text: 'Pesanan telah selesai diproses.',
                            icon: 'success',
                            timer: 2000,
                            timerProgressBar: true
                        });
                        
                        // Update UI without reload if on order detail page
                        if (appData.hasOrderId) {
                            // Change button appearance
                            $('.process-btn').remove();
                            
                            // Update status label
                            $('.order-status').html('<span class="badge badge-success">Selesai</span>');
                        } else {
                            // If we're on the list view, just refresh the table row
                            if (typeof table !== 'undefined') {
                                table.ajax.reload(null, false);
                            }
                        }
                    } else {
                        Swal.fire(
                            'Error!',
                            response.message,
                            'error'
                        );
                    }
                },
                error: function (xhr) {
                    Swal.fire(
                        'Error!',
                        'Error: ' + xhr.responseJSON.message,
                        'error'
                    );
                }
            });
        }
    });
});

    // Complete Order - Only for admin/cashier
    if (appData.isAdminOrCashier) {
        $('.complete-btn').click(function () {
            $('#statusInput').val('completed');
            $('#statusModal').modal('show');
        });

        // Change Status - Only for admin/cashier
        $('.status-btn').click(function () {
            $('#statusModal').modal('show');
        });
    }

    // Confirm Status Update
    $('#confirmStatus').click(function () {
        var orderId = $('#statusOrderId').val();
        var status = $('#statusInput').val();

        $.ajax({
            url: '/orders/' + orderId + '/status',
            method: 'POST',
            data: {
                _token: appData.csrfToken,
                status: status
            },
            success: function (response) {
                if (response.success) {
                    $('#statusModal').modal('hide');
                    toastr.success(response.message);

                    if (appData.hasOrderId) {
                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    } else {
                        if (typeof table !== 'undefined') {
                            table.ajax.reload();
                        }
                    }
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (xhr) {
                toastr.error('Error: ' + xhr.responseJSON.message);
            }
        });
    });

    // Customer-specific actions
    if (appData.isCustomer) {
        // Cancel Order - Only for customer
        $(document).on('click', '.cancel-btn', function () {
            var orderId = $(this).data('id');

            Swal.fire({
                title: 'Batalkan Pesanan?',
                text: "Pesanan yang dibatalkan tidak dapat dipulihkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, batalkan!',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/orders/' + orderId + '/status',
                        method: 'POST',
                        data: {
                            _token: appData.csrfToken,
                            status: 'cancelled'
                        },
                        success: function (response) {
                            if (response.success) {
                                toastr.success('Pesanan berhasil dibatalkan');

                                // Redirect to order list after cancellation
                                setTimeout(function () {
                                    window.location.href = '/orders';
                                }, 1500);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function (xhr) {
                            toastr.error('Error: ' + xhr.responseJSON.message);
                        }
                    });
                }
            });
        });

        // Mark as Received - Only for customer
        $(document).on('click', '.received-btn', function () {
            var orderId = $(this).data('id');

            Swal.fire({
                title: 'Terima Pesanan?',
                text: "Konfirmasi bahwa Anda telah menerima pesanan ini",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, sudah diterima!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/orders/' + orderId + '/status',
                        method: 'POST',
                        data: {
                            _token: appData.csrfToken,
                            status: 'completed'
                        },
                        success: function (response) {
                            if (response.success) {
                                toastr.success('Terima kasih. Pesanan telah dikonfirmasi diterima.');

                                // Redirect to order list after confirmation
                                setTimeout(function () {
                                    window.location.href = '/orders';
                                }, 1500);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function (xhr) {
                            toastr.error('Error: ' + xhr.responseJSON.message);
                        }
                    });
                }
            });
        });
    }

    // Auto print when in print view
    if (appData.receiptView) {
        setTimeout(function () {
            window.print();
        }, 800);
    }
});
