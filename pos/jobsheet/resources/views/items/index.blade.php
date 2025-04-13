@extends('layouts.template')

@section('title', 'Daftar Barang')

@section('content')
    @php
        $breadcrumb = (object) [
            'title' => 'Daftar Barang'
        ];
    @endphp

    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-light p-3 rounded">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Daftar Barang</li>
                        </ol>
                    </nav>
                    <a href="{{ url('/items/create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Barang
                    </a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <!-- Filter options -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Filter Kategori:</label>
                            <select id="item-type-filter" class="form-control select2">
                                <option value="">Semua Kategori</option>
                                @foreach($itemTypes as $itemType)
                                    <option value="{{ $itemType->id }}">{{ $itemType->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Filter Stok:</label>
                            <select id="stock-filter" class="form-control select2">
                                <option value="">Semua Stok</option>
                                <option value="low">Stok Menipis (&lt;10)</option>
                                <option value="out">Stok Habis (0)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="items-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Gambar</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data loaded from pre-loaded variable -->
                            @foreach($items as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        @if($item->photo)
                                            <img src="{{ asset($item->photo) }}" alt="{{ $item->name }}" class="img-thumbnail"
                                                style="max-height: 50px;">
                                        @else
                                            <img src="{{ asset('images/no-image.png') }}" alt="No Image" class="img-thumbnail"
                                                style="max-height: 50px;">
                                        @endif
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->itemType->name ?? '-' }}</td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>
                                        @if($item->stock <= 0)
                                            <span class="badge badge-danger">Habis</span>
                                        @elseif($item->stock < 10)
                                            <span class="badge badge-warning">{{ $item->stock }}</span>
                                        @else
                                            <span class="badge badge-success">{{ $item->stock }}</span>
                                        @endif
                                    </td>
                                    <td>{{ \Illuminate\Support\Str::limit($item->description, 50, '...') }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info btn-sm btn-view"
                                                data-id="{{ $item->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ url('/items/' . $item->id . '/edit') }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm btn-delete"
                                                data-id="{{ $item->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- View Item Modal -->
    <div class="modal fade" id="viewItemModal" tabindex="-1" role="dialog" aria-labelledby="viewItemModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewItemModalLabel">Detail Barang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="viewItemModalContent">
                    <!-- Content will be loaded dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus barang ini?</p>
                </div>
                <div class="modal-footer">
                    <form id="delete-form" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            // Initialize Select2
            $('.select2').select2({
                width: '100%'
            });

            // Preview image before upload
            $("#photo").change(function () {
                readURL(this);
            });

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#preview-image').attr('src', e.target.result);
                        $('#preview-container').removeClass('d-none');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Custom file input
            $(document).on('change', '.custom-file-input', function () {
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass('selected').html(fileName);
            });

            // Display success messages with SweetAlert2
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    timer: 1500,
                    showConfirmButton: false
                });
            @endif

                // Initialize DataTables with pre-loaded data
                var table = $('#items-table').DataTable({
                responsive: true,
                lengthChange: true,
                autoWidth: false
            });

            // Filter by category
            $('#item-type-filter').change(function () {
                var selectedCategoryText = $(this).find('option:selected').text();

                if ($(this).val() === '') {
                    table.column(3).search('').draw();
                } else {
                    // Use exact match for category name
                    table.column(3).search('^' + selectedCategoryText + '$', true, false).draw();
                }
            });

            // Filter by stock status
            $('#stock-filter').change(function () {
                var stockFilter = $(this).val();

                // First clear any existing search
                table.column(5).search('').draw();

                // Then apply the appropriate filter
                if (stockFilter === 'low') {
                    // Custom filtering function for low stock
                    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                        var row = table.row(dataIndex).node();
                        return $(row).find('.badge-warning').length > 0;
                    });
                } else if (stockFilter === 'out') {
                    // Custom filtering function for out of stock
                    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                        var row = table.row(dataIndex).node();
                        return $(row).find('.badge-danger').length > 0;
                    });
                } else {
                    // Remove any custom filtering
                    $.fn.dataTable.ext.search.pop();
                }

                table.draw();
            });

            // Handle View Item
            $(document).on('click', '.btn-view', function () {
                let itemId = $(this).data('id');

                // Fetch item details
                $.ajax({
                    url: "{{ url('/') }}" + `/items/${itemId}/details`, // Use base URL
                    method: 'GET',
                    success: function (response) {
                        // Populate modal with item details
                        let content = `
                                                            <div class="row">
                                                                <div class="col-md-5 text-center">
                                                                    <img src="${response.photo || '/images/no-image.png'}" alt="${response.name}" 
                                                                        class="img-fluid mb-3" style="max-height: 200px;">
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <h4>${response.name}</h4>
                                                                    <p><strong>Kategori:</strong> ${response.item_type?.name || '-'}</p>
                                                                    <p><strong>Harga:</strong> Rp ${parseFloat(response.price).toLocaleString('id-ID')}</p>
                                                                    <p><strong>Stok:</strong> ${response.stock}</p>
                                                                    <p><strong>Deskripsi:</strong> ${response.description || '-'}</p>
                                                                </div>
                                                            </div>
                                                        `;
                        $('#viewItemModalContent').html(content);
                        $('#viewItemModal').modal('show');
                    },
                    error: function (xhr) {
                        toastr.error('Gagal memuat data barang.');
                    }
                });
            });

            // Set up delete confirmation
            $(document).on('click', '.btn-delete', function () {
                let itemId = $(this).data('id');
                $('#delete-form').attr('action', `/items/${itemId}`);
                $('#deleteModal').modal('show');
            });

            // Add success message handling
            @if(session('success'))
                toastr.success("{{ session('success') }}");
            @endif
                    });
    </script>
@endsection