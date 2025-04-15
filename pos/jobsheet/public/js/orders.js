$(document).ready(function() {
    // Initialize DataTable - Only for list view
    if (!appData.hasOrderId && !appData.hasView) {
        var table = $('#ordersTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: appData.ordersListUrl,
                data: function(d) {
                    d.status = $('#statusFilter').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'order_number', name: 'id'},
                {data: 'customer_name', name: 'user.name'},
                {data: 'date_formatted', name: 'created_at'},
                {data: 'items_count', name: 'items_count', searchable: false},
                {data: 'total_formatted', name: 'total_price'},
                {data: 'status_label', name: 'status'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false}
            ],
            order: [[3, 'desc']], // Sort by date descending
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
        $('#statusFilter').change(function() {
            table.ajax.reload();
        });
    }
    
    // Process Order - Modified to set status directly to completed
    $(document).on('click', '.process-btn', function() {
        var orderId = $(this).data('id');
        $('#orderIdInput').val(orderId);
        $('#processModal').modal('show');
    });
    
    // Complete Order
    $('.complete-btn').click(function() {
        $('#statusInput').val('completed');
        $('#statusModal').modal('show');
    });
    
    // Change Status
    $('.status-btn').click(function() {
        $('#statusModal').modal('show');
    });
    
    // Confirm Process - Modified to change status to completed
    $('#confirmProcess').click(function() {
        var orderId = $('#orderIdInput').val();
        
        $.ajax({
            url: '/orders/' + orderId + '/status',
            method: 'POST',
            data: {
                _token: appData.csrfToken,
                status: 'completed' // Directly set to completed
            },
            success: function(response) {
                if (response.success) {
                    $('#processModal').modal('hide');
                    toastr.success('Pesanan berhasil diproses'); // Modified message
                    
                    if (appData.hasOrderId) {
                        setTimeout(function() {
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
            error: function(xhr) {
                toastr.error('Error: ' + xhr.responseJSON.message);
            }
        });
    });
    
    // Confirm Status Update
    $('#confirmStatus').click(function() {
        var orderId = $('#statusOrderId').val();
        var status = $('#statusInput').val();
        
        $.ajax({
            url: '/orders/' + orderId + '/status',
            method: 'POST',
            data: {
                _token: appData.csrfToken,
                status: status
            },
            success: function(response) {
                if (response.success) {
                    $('#statusModal').modal('hide');
                    toastr.success(response.message);
                    
                    if (appData.hasOrderId) {
                        setTimeout(function() {
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
            error: function(xhr) {
                toastr.error('Error: ' + xhr.responseJSON.message);
            }
        });
    });
    
    // Auto print when in print view
    if (appData.receiptView) {
        setTimeout(function() {
            window.print();
        }, 800); 
    }
});