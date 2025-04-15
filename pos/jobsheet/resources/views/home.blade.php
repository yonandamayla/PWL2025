@extends('layouts.template')

@php
    $activeMenu = 'dashboard';
    $breadcrumb = (object) [
        'title' => 'Dashboard',
        'list' => ['Home', 'Dashboard']
    ];
@endphp

@section('content')
    <div class="container-fluid mt-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light p-2 rounded">
                @foreach ($breadcrumb->list as $item)
                    @if ($loop->last)
                        <li class="breadcrumb-item active" aria-current="page">{{ $item }}</li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ $item }}</a></li>
                    @endif
                @endforeach
            </ol>
        </nav>

        <!-- Welcome Card -->
        <div class="card-header bg-primary text-white d-flex align-items-center">
            <i class="fas fa-store me-3"></i> <!-- Changed me-2 to me-3 for more spacing -->
            <h3 class="card-title mb-0">
                @if(Auth::user()->role_id == 1)
                    Selamat datang di BluePos, Admin!
                @elseif(Auth::user()->role_id == 2)
                    Selamat datang di BluePos, Kasir!
                @else
                    Selamat datang di BluePos!
                @endif
            </h3>
        </div>

        <!-- Quick Stats Section -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 text-center animate__animated animate__fadeInUp">
                    <div class="card-body">
                        <i class="fas fa-shopping-bag fa-2x text-primary mb-3"></i>
                        <h5 class="card-title">Total Penjualan</h5>
                        <h3 class="text-muted">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 text-center animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                    <div class="card-body">
                        <i class="fas fa-exclamation-triangle fa-2x text-warning mb-3"></i>
                        <h5 class="card-title">Stok Rendah</h5>
                        <h3 class="text-muted">{{ $lowStockCount }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 text-center animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                    <div class="card-body">
                        <i class="fas fa-shopping-cart fa-2x text-success mb-3"></i>
                        <h5 class="card-title">Transaksi Hari Ini</h5>
                        <h3 class="text-muted">{{ $todayTransactions }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert Section -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card shadow-sm border-0 animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
                    <div class="card-header bg-warning text-dark d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-3"></i> 
                        <h5 class="card-title mb-0">Item dengan Stok Rendah</h5>
                    </div>
                    <div class="card-body">
                        @if($lowStockItems->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nama Item</th>
                                            <th>Kategori</th>
                                            <th>Harga</th>
                                            <th>Stok</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($lowStockItems as $item)
                                            <tr>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->category->name ?? 'Uncategorized' }}</td>
                                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                                <td>
                                                    <span class="badge bg-danger">{{ $item->stock }}</span>
                                                </td>
                                                <td>
                                                    <a href="{{ url('/items/' . $item->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-edit"></i> Update
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($lowStockCount > 5)
                                <div class="text-end mt-2">
                                    <a href="{{ url('/items?sort=stock&direction=asc') }}" class="btn btn-outline-warning">
                                        Lihat Semua ({{ $lowStockCount }})
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i> Semua item memiliki stok yang cukup.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS -->
    @push('styles')
        <style>
            .card {
                transition: transform 0.3s ease-in-out;
                margin-bottom: 15px;
            }
            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
            }
            .breadcrumb {
                background-color: #f8f9fa;
            }
            .lead {
                font-size: 1.2rem;
            }
            .stats-icon {
                font-size: 2rem;
                margin-bottom: 0.5rem;
            }
        </style>
    @endpush
@endsection