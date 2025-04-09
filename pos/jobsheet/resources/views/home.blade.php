@extends('layouts.template')

@section('content')
    <div class="container-fluid mt-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light p-2 rounded">
                @foreach ($breadcrumb->list as $item)
                    @if ($loop->last)
                        <li class="breadcrumb-item active" aria-current="page">{{ $item }}</li>
                    @else
                        <li class="breadcrumb-item"><a href="#">{{ $item }}</a></li>
                    @endif
                @endforeach
            </ol>
        </nav>

        <!-- Welcome Card -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm border-0 animate__animated animate__fadeIn">
                    <div class="card-header bg-primary text-white d-flex align-items-center">
                        <i class="fas fa-store me-2"></i>
                        <h3 class="card-title mb-0">{{ $welcomeMessage }}</h3>
                    </div>
                    <div class="card-body">
                        <p class="lead text-muted">
                            {{ $welcomeMessage }} Jelajahi produk dan mulai transaksi sekarang!
                        </p>
                        <div class="d-flex gap-3 mt-4">
                            <a href="{{ route('products.index') }}" class="btn btn-outline-primary d-flex align-items-center">
                                <i class="fas fa-box-open me-2"></i> Lihat Produk
                            </a>
                            <a href="{{ route('transactions.create') }}" class="btn btn-success d-flex align-items-center">
                                <i class="fas fa-cart-plus me-2"></i> Mulai Transaksi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Section -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 text-center animate__animated animate__fadeInUp">
                    <div class="card-body">
                        <i class="fas fa-boxes fa-2x text-primary mb-3"></i>
                        <h5 class="card-title">Total Produk</h5>
                        <h3 class="text-muted">150</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 text-center animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                    <div class="card-body">
                        <i class="fas fa-shopping-cart fa-2x text-success mb-3"></i>
                        <h5 class="card-title">Transaksi Hari Ini</h5>
                        <h3 class="text-muted">25</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 text-center animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                    <div class="card-body">
                        <i class="fas fa-users fa-2x text-info mb-3"></i>
                        <h5 class="card-title">Pengguna Aktif</h5>
                        <h3 class="text-muted">10</h3>
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
        </style>
    @endpush
@endsection