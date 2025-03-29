@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/kategori/import') }}')" class="btn btn-info">Import Kategori</button>
                <a href="{{ url('/kategori/create') }}" class="btn btn-primary">Tambah Data</a>
                <button onclick="modalAction('{{ url('/kategori/create_ajax') }}')" class="btn btn-success">Tambah Data (Ajax)</button>
            </div>
        </div>
        <div class="card-body">
            <!-- Filter Section -->
            <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-sm row text-sm mb-0">
                            <label for="filter_kategori" class="col-md-1 col-form-label">Filter</label>
                            <div class="col-md-3">
                                <select name="filter_kategori" class="form-control form-control-sm filter_kategori">
                                    <option value="">- Semua -</option>
                                    @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->kategori_nama }}">{{ $kategori->kategori_nama }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Nama Kategori</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped table-hover table-sm" id="table_kategori">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }

        window.dataKategori;
        $(document).ready(function () {
            window.dataKategori = $('#table_kategori').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('kategori/list') }}",
                    type: "POST",
                    data: function (d) {
                        d._token = "{{ csrf_token() }}";
                        d.filter_kategori = $('.filter_kategori').val(); // Add filter value
                    }
                },
                columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                    { data: "kategori_kode", orderable: true, searchable: true },
                    { data: "kategori_nama", orderable: true, searchable: true },
                    { data: "aksi", orderable: false, searchable: false }
                ]
            });
            
            // Add change event listener for filter
            $('.filter_kategori').change(function() {
                window.dataKategori.draw(); // Redraw table with new filter value
            });
        });
    </script>
@endpush