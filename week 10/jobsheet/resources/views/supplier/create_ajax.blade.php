<form action="{{ url('/supplier/store_ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Supplier <span class="text-danger">*</span></label>
                    <input type="text" name="supplier_nama" id="supplier_nama" class="form-control" required>
                    <small id="error-supplier_nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Alamat <span class="text-danger">*</span></label>
                    <textarea name="supplier_alamat" id="supplier_alamat" class="form-control" required rows="3"></textarea>
                    <small id="error-supplier_alamat" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>No. Telepon <span class="text-danger">*</span></label>
                    <input type="text" name="supplier_telp" id="supplier_telp" class="form-control" required>
                    <small id="error-supplier_telp" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="supplier_email" id="supplier_email" class="form-control">
                    <small id="error-supplier_email" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Kontak Person</label>
                    <input type="text" name="supplier_kontak" id="supplier_kontak" class="form-control">
                    <small id="error-supplier_kontak" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        // Reset error messages
        $('.error-text').text('');
        
        $("#form-tambah").validate({
            rules: {
                supplier_nama: {
                    required: true,
                    minlength: 3,
                    maxlength: 100
                },
                supplier_alamat: {
                    required: true,
                    minlength: 5
                },
                supplier_telp: {
                    required: true,
                    minlength: 5,
                    maxlength: 20
                },
                supplier_email: {
                    email: true
                }
            },
            messages: {
                supplier_nama: {
                    required: "Nama supplier harus diisi",
                    minlength: "Minimal 3 karakter",
                    maxlength: "Maksimal 100 karakter"
                },
                supplier_alamat: {
                    required: "Alamat harus diisi",
                    minlength: "Minimal 5 karakter"
                },
                supplier_telp: {
                    required: "No. telepon harus diisi",
                    minlength: "Minimal 5 karakter",
                    maxlength: "Maksimal 20 karakter"
                },
                supplier_email: {
                    email: "Format email tidak valid"
                }
            },
            submitHandler: function(form) {
                // Disable submit button to prevent double submission
                var submitBtn = $(form).find('button[type="submit"]');
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
                
                // Debug console log
                console.log("Form submission started");
                console.log("Form action:", form.action);
                console.log("Form data:", $(form).serialize());
                
                $.ajax({
                    url: form.action,
                    type: "POST",
                    data: $(form).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        console.log("Response received:", response);
                        
                        // Re-enable submit button
                        submitBtn.prop('disabled', false).html('Simpan');
                        
                        if(response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataSupplier.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            if (response.msgField) {
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-'+prefix).text(val[0]);
                                });
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", xhr.responseText);
                        
                        // Re-enable submit button
                        submitBtn.prop('disabled', false).html('Simpan');
                        
                        // Handle error
                        let errorMessage = 'Gagal mengirim data. Silakan coba lagi.';
                        
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
                return false;
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>