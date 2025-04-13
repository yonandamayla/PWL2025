<section class="content-header breadcrumb-container">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1 class="page-title">{{ $breadcrumb->title ?? 'Page Title' }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if(isset($breadcrumb->items))
                        @foreach($breadcrumb->items as $item)
                            <li class="breadcrumb-item {{ isset($item->active) && $item->active ? 'active' : '' }}">
                                @if(isset($item->active) && $item->active)
                                    {{ $item->text }}
                                @else
                                    <a href="{{ $item->link }}">{{ $item->text }}</a>
                                @endif
                            </li>
                        @endforeach
                    @endif
                </ol>
            </div>
        </div>
    </div>
</section>