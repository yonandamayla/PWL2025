@extends('adminlte::page')

{{-- Extend and customize the browser title --}}
@section('title')
    {{ config('adminlte.title') }} @hassection('subtitle') | @yield('subtitle') @endif
@stop

{{-- Extend and customize the page content header --}}
@section('content_header')
    @hassection('content_header_title')
        <h1 class="text-muted">
            @yield('content_header_title')
            @hassection('content_header_subtitle')
                <small class="text-dark">
                    <i class="fas fa-xs fa-angle-right text-muted"></i>
                    @yield('content_header_subtitle')
                </small>
            @endif
        </h1>
    @endif
@stop

{{-- Rename section content to content_body --}}
@section('content')
    @yield('content_body')
@stop

{{-- Create a common footer --}}
@section('footer')
    <div class="float-right">
        Version: {{ config('app.version', '1.0.0') }}
    </div>
    <strong>
        <a href="{{ config('app.company_url', '#') }}">
            {{ config('app.company_name', 'My company') }}
        </a>
    </strong>
@stop

{{-- Add the menu customization --}}
@section('adminlte_menu')
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Existing menu items (if any) -->
            <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link">
                    <i class="nav-icon fas fa-home"></i>
                    <p>Home</p>
                </a>
            </li>
            <!-- Add the Manage Kategori menu item -->
            <li class="nav-item">
                <a href="{{ url('/kategori') }}" class="nav-link">
                    <i class="nav-icon fas fa-list"></i>
                    <p>Manage Kategori</p>
                </a>
            </li>
        </ul>
    </nav>
@endsection

{{-- Add common Javascript/Jquery code --}}
@push('js')
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
@endpush
@stack('scripts')

{{-- Add common CSS customizations --}}
@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css" />
    <style type="text/css">
        .card-header {
            border-bottom: none;
        }
        .card-title {
            font-weight: 600;
        }
    </style>
@endpush