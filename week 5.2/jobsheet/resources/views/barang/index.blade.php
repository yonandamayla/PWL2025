@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('barang/create') }}">Tambah</a>
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
                            <select class="form-control" id="kategori_id" name="kategori_id">
                                <option value="">- Semua Kategori -</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->kategori_id }}">{{ $kategori->kategori_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Kategori Barang</small>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-striped table-hover table-sm" id="table_barang">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
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
            var tableBarang = $('#table_barang').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('barang/list') }}",
                    type: "POST",
                    data: function (d) {
                        d.kategori_id = $('#kategori_id').val(); // Filter berdasarkan kategori_id
                        d.barang_nama = $('#barang_nama').val(); // Filter berdasarkan nama barang
                        d._token = "{{ csrf_token() }}"; // Kirim CSRF token
                    }
                },
                columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                    { data: "barang_kode", orderable: true, searchable: true },
                    { data: "barang_nama", orderable: true, searchable: true },
                    { data: "kategori_nama", orderable: true, searchable: true },
                    { data: "harga_beli_rp", orderable: true, searchable: false },
                    { data: "harga_jual_rp", orderable: true, searchable: false },
                    { data: "aksi", orderable: false, searchable: false }
                ]
            });

            // Reload data ketika filter berubah
            $('#kategori_id').on('change', function () {
                tableBarang.ajax.reload();
            });
            
            // Reload data ketika user mengetik di input pencarian nama barang (dengan delay)
            let typingTimer;
            $('#barang_nama').on('keyup', function () {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(function() {
                    tableBarang.ajax.reload();
                }, 500);
            });
            
            // Clear timeout ketika user masih mengetik
            $('#barang_nama').on('keydown', function () {
                clearTimeout(typingTimer);
            });
        });
    </script>
@endpush