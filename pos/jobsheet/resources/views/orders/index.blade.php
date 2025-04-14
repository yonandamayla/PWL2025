@extends('layouts.template')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/orders.css') }}">
@endsection

@section('content')
<div class="container-fluid no-print">
    @if(isset($view) && $view == 'receipt')
    <!-- Print Receipt View -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light p-3 rounded">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Daftar Transaksi</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orders.index', ['order_id' => $order->id]) }}">Detail Transaksi</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cetak Struk</li>
            </ol>
        </nav>
        <div>
            <button onclick="window.print()" class="btn btn-primary btn-sm">
                <i class="fas fa-print"></i> Cetak
            </button>
            <a href="{{ route('orders.index', ['order_id' => $order->id]) }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="receipt-container">
        <!-- Enhanced Receipt Design -->
        <div class="receipt">
            <div class="header">
                <div class="text-center mb-2">
                    <i class="fas fa-shopping-cart fa-2x text-primary"></i>
                </div>
                <div class="store-name">BluePos</div>
                <div class="store-info">Jl. Contoh No. 123, Kota</div>
                <div class="store-info">Tel: (021) 1234-5678</div>
            </div>

            <div class="divider"></div>

            <div class="order-info">
                <div><span class="label">No. Transaksi:</span> {{ $orderNumber }}</div>
                <div><span class="label">Tanggal:</span> {{ Carbon\Carbon::parse($order->order_date ?? $order->created_at)->format('d/m/Y H:i') }}</div>
                <div><span class="label">Kasir:</span> {{ Auth::user()->name }}</div>
                <div><span class="label">Pelanggan:</span> {{ $order->user->name }}</div>
            </div>

            <div class="divider"></div>

            <table class="items">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="right">Qty</th>
                        <th class="right">Harga</th>
                        <th class="right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->item->name }}</td>
                        <td class="right">{{ $item->quantity }}</td>
                        <td class="right">{{ number_format($item->item->price, 0, ',', '.') }}</td>
                        <td class="right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="divider"></td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="3">Subtotal</td>
                        <td class="right">{{ number_format($order->total_price, 0, ',', '.') }}</td>
                    </tr>
                    @if($order->discount > 0)
                    <tr>
                        <td colspan="3">Diskon ({{ $order->discount }}%)</td>
                        <td class="right">{{ number_format($order->total_price * $order->discount / 100, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    <tr class="total-row">
                        <td colspan="3">Total Bayar</td>
                        <td class="right">{{ number_format($order->total_price - ($order->total_price * $order->discount / 100), 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="divider"></div>

            <!-- Barcode -->
            <div class="receipt-barcode">
                <div class="text-monospace">*{{ $orderNumber }}*</div>
            </div>

            <div class="footer">
                <p>Terima kasih telah berbelanja di BluePos</p>
                <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
            </div>
        </div>
    </div>

    @elseif(isset($order_id))
    <!-- Order Detail View -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light p-3 rounded">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Daftar Transaksi</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Transaksi</li>
            </ol>
        </nav>
        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pesanan #{{ $orderNumber }}</h6>
                    <div>
                        @if($order->status == 'pending' && (Auth::user()->role_id == 1 || Auth::user()->role_id == 2))
                        <button class="btn btn-sm btn-primary process-btn" data-id="{{ $order->id }}">
                            <i class="fas fa-tasks"></i> Proses Pesanan
                        </button>
                        @endif
                        @if($order->status == 'processing' && (Auth::user()->role_id == 1 || Auth::user()->role_id == 2))
                        <button class="btn btn-sm btn-success complete-btn" data-id="{{ $order->id }}">
                            <i class="fas fa-check"></i> Selesaikan
                        </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row order-info">
                        <div class="col-md-6">
                            <h5>Informasi Pelanggan</h5>
                            <p><strong>Nama:</strong> {{ $order->user->name }}</p>
                            <p><strong>Email:</strong> {{ $order->user->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Informasi Pesanan</h5>
                            <p><strong>Tanggal:</strong> {{ Carbon\Carbon::parse($order->order_date ?? $order->created_at)->format('d M Y H:i') }}</p>
                            <p>
                                <strong>Status:</strong> 
                                @if($order->status == 'pending')
                                    <span class="badge badge-warning order-status">Menunggu</span>
                                @elseif(strpos($order->status, 'processing') !== false)
                                    <span class="badge badge-info order-status">Diproses</span>
                                @elseif($order->status == 'completed')
                                    <span class="badge badge-success order-status">Selesai</span>
                                @elseif($order->status == 'cancelled')
                                    <span class="badge badge-danger order-status">Dibatalkan</span>
                                @else
                                    <span class="badge badge-secondary order-status">{{ $order->status }}</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered order-table">
                            <thead>
                                <tr>
                                    <th width="5%">No.</th>
                                    <th>Nama Item</th>
                                    <th width="15%">Harga</th>
                                    <th width="10%">Jumlah</th>
                                    <th width="15%">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->item->name }}</td>
                                    <td>Rp {{ number_format($item->item->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">Total Harga:</th>
                                    <th>Rp {{ number_format($order->total_price, 0, ',', '.') }}</th>
                                </tr>
                                @if($order->discount > 0)
                                <tr>
                                    <th colspan="4" class="text-right">Diskon ({{ $order->discount }}%):</th>
                                    <th>Rp {{ number_format($order->total_price * $order->discount / 100, 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-right">Total Bayar:</th>
                                    <th>Rp {{ number_format($order->total_price - ($order->total_price * $order->discount / 100), 0, ',', '.') }}</th>
                                </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>

                    <div class="receipt-actions">
                        <a href="{{ route('orders.index', ['view' => 'receipt', 'order_id' => $order->id]) }}" class="btn btn-info">
                            <i class="fas fa-print"></i> Cetak Struk
                        </a>
                        
                        @if($order->status == 'pending' && (Auth::user()->role_id == 1 || Auth::user()->role_id == 2))
                        <button class="btn btn-warning status-btn" data-id="{{ $order->id }}">
                            <i class="fas fa-edit"></i> Ubah Status
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @else
    <!-- Orders List View with Updated Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light p-3 rounded">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Daftar Transaksi</li>
            </ol>
        </nav>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <!-- Modified Filter - Narrower Width -->
            <div class="row mb-3 filter-section">
                <div class="col-12">
                    <div class="filter-wrapper">
                        <div style="width: 300px;">
                            <div class="form-group">
                                <label>Status Pesanan</label>
                                <select class="form-control form-control-sm" id="statusFilter" name="status">
                                    <option value="">Semua Status</option>
                                    <option value="pending">Menunggu</option>
                                    <option value="processing">Diproses</option>
                                    <option value="completed">Selesai</option>
                                    <option value="cancelled">Dibatalkan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="ordersTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>No.</th>
                            <th>Order ID</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Jumlah Item</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded via DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Only visible when printing -->
<div class="receipt-print-container only-print">
    @isset($order)
    <!-- Enhanced Receipt Design for print -->
    <div class="receipt">
        <div class="header">
            <div class="text-center mb-2">
                <i class="fas fa-shopping-cart fa-2x text-primary"></i>
            </div>
            <div class="store-name">BluePos</div>
            <div class="store-info">Jl. Contoh No. 123, Kota</div>
            <div class="store-info">Tel: (021) 1234-5678</div>
        </div>

        <div class="divider"></div>

        <div class="order-info">
            <div><span class="label">No. Transaksi:</span> {{ $orderNumber }}</div>
            <div><span class="label">Tanggal:</span> {{ Carbon\Carbon::parse($order->order_date ?? $order->created_at)->format('d/m/Y H:i') }}</div>
            <div><span class="label">Kasir:</span> {{ Auth::user()->name }}</div>
            <div><span class="label">Pelanggan:</span> {{ $order->user->name }}</div>
        </div>

        <div class="divider"></div>

        <table class="items">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="right">Qty</th>
                    <th class="right">Harga</th>
                    <th class="right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->item->name }}</td>
                    <td class="right">{{ $item->quantity }}</td>
                    <td class="right">{{ number_format($item->item->price, 0, ',', '.') }}</td>
                    <td class="right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="4" class="divider"></td>
                </tr>
                <tr class="total-row">
                    <td colspan="3">Subtotal</td>
                    <td class="right">{{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
                @if($order->discount > 0)
                <tr>
                    <td colspan="3">Diskon ({{ $order->discount }}%)</td>
                    <td class="right">{{ number_format($order->total_price * $order->discount / 100, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td colspan="3">Total Bayar</td>
                    <td class="right">{{ number_format($order->total_price - ($order->total_price * $order->discount / 100), 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="divider"></div>

        <!-- Barcode -->
        <div class="receipt-barcode">
            <div class="text-monospace">*{{ $orderNumber }}*</div>
        </div>

        <div class="footer">
            <p>Terima kasih telah berbelanja di BluePos</p>
            <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
        </div>
    </div>
    @endisset
</div>

<!-- Process Order Modal -->
<div class="modal fade" id="processModal" tabindex="-1" aria-labelledby="processModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="processModalLabel">Proses Pesanan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Anda akan memproses pesanan ini. Pesanan yang diproses akan segera disiapkan untuk pelanggan.</p>
                <form id="processForm">
                    <input type="hidden" id="orderIdInput" name="order_id" value="{{ $order->id ?? '' }}">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmProcess">Proses Pesanan</button>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Update Status Pesanan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="statusForm">
                    <input type="hidden" id="statusOrderId" name="order_id" value="{{ $order->id ?? '' }}">
                    <div class="form-group">
                        <label for="statusInput">Status</label>
                        <select class="form-control" id="statusInput" name="status">
                            <option value="processing">Diproses</option>
                            <option value="completed">Selesai</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmStatus">Update Status</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Add app data for JavaScript -->
<script>
    var appData = {
        ordersListUrl: "{{ route('orders.list') }}",
        csrfToken: "{{ csrf_token() }}",
        hasOrderId: {{ isset($order_id) ? 'true' : 'false' }},
        hasView: {{ isset($view) ? 'true' : 'false' }},
        receiptView: {{ isset($view) && $view == 'receipt' ? 'true' : 'false' }}
    };
</script>
<script src="{{ asset('js/orders.js') }}"></script>
@endsection