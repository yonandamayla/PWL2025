@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('supplier/create') }}">Tambah</a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="supplier_kode" name="supplier_kode">
                                <option value="">- Semua -</option>
                                <option value="SUP001">PT. Elektronik Jaya</option>
                                <option value="SUP002">CV. Fashion Indonesia</option>
                                <option value="SUP003">UD. Makanan Sehat</option>
                                <option value="SUP004">PT. Minuman Prima</option>
                                <option value="SUP005">PT. Farma Indonesia</option>
                            </select>
                            <small class="form-text text-muted">Supplier</small>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-striped table-hover table-sm" id="table_supplier">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Supplier</th>
                        <th>Nama Supplier</th>
                        <th>Telepon</th>
                        <th>Email</th>
                        <th>Kontak Person</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        $(document).ready(function () {
            var tableSupplier = $('#table_supplier').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('supplier/list') }}",
                    type: "POST",
                    data: function (d) {
                        d.supplier_kode = $('#supplier_kode').val(); // Filter berdasarkan supplier_kode
                        d._token = "{{ csrf_token() }}"; // Kirim CSRF token
                    }
                },
                columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                    { data: "supplier_kode", orderable: true, searchable: true },
                    { data: "supplier_nama", orderable: true, searchable: true },
                    { data: "supplier_telp", orderable: true, searchable: true },
                    { data: "supplier_email", orderable: true, searchable: true },
                    { data: "supplier_kontak", orderable: true, searchable: true },
                    { data: "aksi", orderable: false, searchable: false }
                ]
            });

            // Reload data ketika filter supplier_kode berubah
            $('#supplier_kode').on('change', function () {
                tableSupplier.ajax.reload();
            });
        });
    </script>
@endpush