@extends('layouts.template')

@section('content')
    @section('styles')
        <link rel="stylesheet" href="{{ asset('css/items.css') }}">
    @endsection

    <div class="d-flex justify-content-between align-items-center mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light p-3 rounded">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Daftar Barang</li>
            </ol>
        </nav>
        @if(auth()->user()->role_id == 1) {{-- Only show add button for admin --}}
        <button class="btn btn-primary btn-sm" id="btn-add-item">
            <i class="fas fa-plus-circle"></i> Tambah Barang
        </button>
        @endif
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Filters -->
            <div class="row mb-3 filter-section">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="type-filter"><i class="fas fa-filter mr-1"></i> Filter Berdasarkan Kategori:</label>
                        <select id="type-filter" class="form-control form-control-sm">
                            <option value="">Semua Kategori</option>
                            @foreach($itemTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="stock-filter"><i class="fas fa-cubes mr-1"></i> Filter Berdasarkan Stok:</label>
                        <select id="stock-filter" class="form-control form-control-sm">
                            <option value="">Semua Stok</option>
                            <option value="available">Tersedia</option>
                            <option value="low">Stok Rendah <10</option>
                            <option value="out">Habis</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="items-table" class="table table-striped table-borderless table-hover nowrap" width="100%">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="15%">Gambar</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded by AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Item Modal -->
    <div class="modal fade" id="itemModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="itemModalLabel">Tambah Barang</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="itemForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="item_id" id="item_id">
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="name">Nama Barang</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                    <div class="invalid-feedback" id="name-error"></div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="description">Deskripsi</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                    <div class="invalid-feedback" id="description-error"></div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="price">Harga (Rp)</label>
                                            <input type="number" class="form-control" id="price" name="price" min="0" required>
                                            <div class="invalid-feedback" id="price-error"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="stock">Stok</label>
                                            <input type="number" class="form-control" id="stock" name="stock" min="0" required>
                                            <div class="invalid-feedback" id="stock-error"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="item_type_id">Kategori</label>
                                    <select class="form-control" id="item_type_id" name="item_type_id" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach($itemTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="item_type_id-error"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="image">Gambar</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="image" name="image" accept="image/*">
                                        <label class="custom-file-label" for="image">Pilih gambar</label>
                                    </div>
                                    <div class="invalid-feedback" id="image-error"></div>
                                    <div class="mt-3 text-center">
                                        <img id="image-preview" src="{{ asset('images/no-image.png') }}" 
                                             alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="saveItem">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Anda yakin ingin menghapus barang ini?</p>
                    <input type="hidden" id="delete_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Item Modal -->
    <div class="modal fade" id="viewItemModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Detail Barang</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <img id="view-image" src="{{ asset('images/no-image.png') }}" 
                                 class="img-fluid rounded" style="max-height: 250px;">
                        </div>
                        <div class="col-md-8">
                            <h4 id="view-name" class="font-weight-bold mb-3">Nama Barang</h4>
                            
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Kategori:</strong></p>
                                    <p id="view-category" class="mb-3">-</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Stok:</strong></p>
                                    <p id="view-stock" class="mb-3">-</p>
                                </div>
                            </div>
                            
                            <p class="mb-1"><strong>Harga:</strong></p>
                            <p id="view-price" class="mb-3 text-primary font-weight-bold">-</p>
                            
                            <p class="mb-1"><strong>Deskripsi:</strong></p>
                            <p id="view-description" class="mb-0">-</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        // Define variables for use in external JS file
        const ITEMS_ROUTE = "{{ route('items.list') }}";
        const IS_ADMIN = {{ auth()->user()->role_id == 1 ? 'true' : 'false' }};
    </script>
    <script src="{{ asset('js/items.js') }}"></script>
@endsection