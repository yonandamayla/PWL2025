@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar Supplier</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/supplier/import') }}')" class="btn btn-info">Import Supplier</button>
                <a href="{{ url('/supplier/export_excel') }}" class="btn btn-primary">Export Excel</a>
                <a href="{{ url('/supplier/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file- pdf"></i> Export Pdf</a>
                <button onclick="modalAction('{{ url('/supplier/create_ajax') }}')" class="btn btn-success">Tambah Data (Ajax)</button>
            </div>
        </div>

        <div class="card-body">
            <!-- Filter Section -->
            <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-sm row text-sm mb-0">
                            <label for="filter_name" class="col-md-1 col-form-label">Filter</label>
                            <div class="col-md-3">
                                <select name="filter_name" class="form-control form-control-sm filter_name">
                                    <option value="">- Semua -</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->supplier_nama }}">{{ $supplier->supplier_nama }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Nama Supplier</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Suppliers Table -->
            <table class="table table-bordered table-striped table-hover table-sm" id="table_supplier">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Supplier</th>
                    <th>Nama Supplier</th>
                    <th>Alamat Supplier</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Modal for Import Form -->
    <div id="modal-supplier" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false" data-width="75%"></div>
@endsection

@push('js')
<script>
    function modalAction(url = '') {
        $('#modal-supplier').load(url, function() {
            $('#modal-supplier').modal('show');
        });
    }

    var tableSupplier;
    $(document).ready(function() {
        tableSupplier = $('#table_supplier').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": "{{ url('supplier/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function (d) {
                    d.filter_name = $('.filter_name').val();
                }
            },
            columns: [
                {
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "supplier_kode",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "supplier_nama",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "supplier_alamat",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "aksi",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Add change event listener for filter
        $('.filter_name').change(function() {
            tableSupplier.draw(); // Redraw table with new filter value
        });

        $('#table_supplier_filter input').unbind().bind().on('keyup', function(e) {
            if (e.keyCode == 13) {
                tableSupplier.search(this.value).draw();
            }
        });
    });
</script>
@endpush