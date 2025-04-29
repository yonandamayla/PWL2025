@empty($supplier)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data supplier yang Anda cari tidak ditemukan
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Tutup</button>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/supplier/' . $supplier->supplier_id . '/delete_ajax') }}" method="POST" id="form-delete">
        @csrf
        @method('DELETE')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hapus Data Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi!</h5>
                        Apakah Anda yakin ingin menghapus supplier berikut?
                    </div>
                    <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <th width="30%">ID Supplier</th>
                            <td>{{ $supplier->supplier_id }}</td>
                        </tr>
                        <tr>
                            <th>Kode Supplier</th>
                            <td>{{ $supplier->supplier_kode }}</td>
                        </tr>
                        <tr>
                            <th>Nama Supplier</th>
                            <td>{{ $supplier->supplier_nama }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $supplier->supplier_alamat }}</td>
                        </tr>
                        <tr>
                            <th>Telepon</th>
                            <td>{{ $supplier->supplier_telp }}</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function() {
            $("#form-delete").submit(function(e) {
                e.preventDefault();
                
                // Disable submit button to prevent double submission
                var submitBtn = $(this).find('button[type="submit"]');
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menghapus...');
                
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'DELETE',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        // Re-enable submit button
                        submitBtn.prop('disabled', false).html('Hapus');
                        
                        if(response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataSupplier.ajax.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // Re-enable submit button
                        submitBtn.prop('disabled', false).html('Hapus');
                        
                        console.error("AJAX Error:", xhr.responseText);
                        
                        let errorMessage = 'Gagal menghapus data. Silakan coba lagi.';
                        
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMessage = response.message;
                            }
                        } catch (e) {
                            // If error response is not JSON
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: errorMessage
                        });
                    }
                });
            });
        });
    </script>
@endempty