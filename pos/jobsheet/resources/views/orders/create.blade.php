@extends('layouts.template')

@php
    $activeMenu = 'Buat pesanan';
    $breadcrumb = (object) [
        'title' => 'Buat Pesanan',
        'list' => ['Home', 'Buat Pesanan']
    ];
@endphp

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/orders-creation.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mt-3 mb-3">
        <ol class="breadcrumb bg-light p-3 rounded">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Buat Pesanan</li>
        </ol>
    </nav>

    <div class="cart-container-top">
        <button class="btn btn-primary cart-btn" id="showCart">
            <i class="fas fa-shopping-cart"></i>
            <span id="cartBadge" class="badge badge-light">0</span>
        </button>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="category-filter"><i class="fas fa-filter mr-2"></i> Filter Kategori:</label>
                        <select id="category-filter" class="form-control">
                            <option value="">Semua Kategori</option>
                            @foreach($itemTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="sort-filter"><i class="fas fa-sort mr-2"></i> Urutkan Berdasarkan:</label>
                        <select id="sort-filter" class="form-control">
                            <option value="name">Nama</option>
                            <option value="price_asc">Harga Terendah</option>
                            <option value="price_desc">Harga Tertinggi</option>
                            <option value="newest">Terbaru</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="search-input"><i class="fas fa-search mr-2"></i> Cari:</label>
                        <input type="text" id="search-input" class="form-control" placeholder="Ketik nama produk...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row" id="products-container">
        <!-- Products will be loaded dynamically -->
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>

    <!-- Cart Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-shopping-cart mr-2"></i> Keranjang Belanja
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="cart-items">
                        <!-- Cart items will be loaded here -->
                    </div>
                    <div id="empty-cart" class="text-center py-4">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <p class="lead">Keranjang belanja Anda kosong</p>
                        <button class="btn btn-outline-primary" data-dismiss="modal">
                            Mulai Belanja
                        </button>
                    </div>
                </div>
                <div class="modal-footer d-block">
                    <div class="row">
                        <div class="col-md-6 text-left">
                            <button type="button" class="btn btn-outline-danger" id="clearCart">
                                <i class="fas fa-trash mr-1"></i> Hapus Semua
                            </button>
                        </div>
                        <div class="col-md-6 text-right">
                            <h5 class="mb-2">Total: <span id="cartTotal" class="text-primary">Rp 0</span></h5>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Lanjut Belanja</button>
                            <button type="button" class="btn btn-success" id="checkoutBtn">
                                <i class="fas fa-check mr-1"></i> Checkout
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-credit-card mr-2"></i> Checkout
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5>Ringkasan Pesanan</h5>
                            <div id="checkout-items" class="mb-4">
                                <!-- Checkout items summary will be loaded here -->
                            </div>
                            
                            <h5>Metode Pembayaran</h5>
                            <div class="payment-methods">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_transfer" value="transfer">
                                    <label class="form-check-label" for="payment_transfer">
                                        <i class="fas fa-university text-primary mr-1"></i> Transfer Bank
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_ewallet" value="ewallet">
                                    <label class="form-check-label" for="payment_ewallet">
                                        <i class="fas fa-wallet text-danger mr-1"></i> E-Wallet
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 border-left">
                            <h5>Rincian Biaya</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td>Subtotal</td>
                                    <td class="text-right" id="checkout-subtotal">Rp 0</td>
                                </tr>
                                <tr>
                                    <td>Diskon</td>
                                    <td class="text-right" id="checkout-discount">Rp 0</td>
                                </tr>
                                <tr class="font-weight-bold">
                                    <td>Total</td>
                                    <td class="text-right" id="checkout-total">Rp 0</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <button type="button" class="btn btn-success" id="placeOrderBtn">
                        <i class="fas fa-check-circle mr-1"></i> Selesaikan Pesanan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Confirmation Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Konfirmasi Pembayaran</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="payment-cash" class="payment-option">
                        <p>Total yang harus dibayar: <strong id="cash-total">Rp 0</strong></p>
                        <div class="form-group">
                            <label for="cash-amount">Jumlah yang dibayarkan:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control" id="cash-amount">
                            </div>
                        </div>
                        <div id="change-container" class="alert alert-success" style="display: none;">
                            Kembalian: <strong id="cash-change">Rp 0</strong>
                        </div>
                    </div>

                    <div id="payment-transfer" class="payment-option" style="display: none;">
                        <div class="text-center mb-4">
                            <i class="fas fa-university fa-3x text-primary"></i>
                            <h4 class="mt-2">Transfer Bank</h4>
                        </div>
                        <p>Silahkan transfer sebesar <strong id="transfer-total">Rp 0</strong> ke rekening:</p>
                        <div class="card">
                            <div class="card-body">
                                <h5>Bank BCA</h5>
                                <p>No. Rekening: 1234567890</p>
                                <p>Atas Nama: BluePos Store</p>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="transfer-proof">Upload Bukti Transfer:</label>
                            <input type="file" class="form-control-file" id="transfer-proof">
                        </div>
                    </div>

                    <div id="payment-ewallet" class="payment-option" style="display: none;">
                        <div class="text-center mb-4">
                            <i class="fas fa-wallet fa-3x text-danger"></i>
                            <h4 class="mt-2">E-Wallet</h4>
                        </div>
                        <p>Scan QR Code berikut untuk membayar <strong id="ewallet-total">Rp 0</strong>:</p>
                        <div class="text-center">
                            <img src="{{ asset('images/qrcode-sample.png') }}" alt="QR Code" class="img-fluid" style="max-width: 200px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <button type="button" class="btn btn-success" id="confirmPaymentBtn">Konfirmasi Pembayaran</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Pesanan Berhasil!</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-5x text-success"></i>
                    </div>
                    <h4>Terima kasih atas pesanan Anda!</h4>
                    <p>Nomor Pesanan: <strong id="order-number">ORD-12345</strong></p>
                    <p class="mb-0">Status pembayaran: <span class="badge badge-success">Lunas</span></p>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Lihat Riwayat Pesanan</a>
                    <button type="button" class="btn btn-success" data-dismiss="modal" id="newOrderBtn">Buat Pesanan Baru</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        const ITEMS_ROUTE = "{{ route('orders.items') }}";
        const PLACE_ORDER_ROUTE = "{{ route('orders.store') }}";
    </script>
    <script src="{{ asset('js/orders-creation.js') }}"></script>
@endsection