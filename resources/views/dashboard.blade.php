<x-app-layout>
    <style>
        .pur {
            width: 100%;
            margin-bottom: 8px;
        }
    </style>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-9">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card py-3">
                        <div class="card-body">
                            <h4 class="card-title">{{ $carts }} Products</h4>
                            <h6 class="card-subtitle mb-2 text-muted">in your cart</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card py-3">
                        <div class="card-body">
                            <h4 class="card-title">{{ $wishlists }} Products</h4>
                            <h6 class="card-subtitle mb-2 text-muted">in your wishlist</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card py-3">
                        <div class="card-body">
                            <h4 class="card-title">{{ $orders }} Products</h4>
                            <h6 class="card-subtitle mb-2 text-muted">you ordered</h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Your Purchases</div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($purchases as $good)
                            @foreach ($good->items as $item)
                                <div class="col-xl-2 col-lg-3">
                                    <div class="card">
                                        <div class="card-body">
                                            @if ($item->product_variant == 0)
                                                <img src="{{ $item->product->uploads->getImageOptimizedFullName(400) }}"
                                                    alt="" style="width: 100%;" class="mb-2">
                                                <a href="{{ url('products/') . '/' . $item->product->slug }}">
                                                    <h6>{{ $item->product_name }}</h6>
                                                </a>
                                            @else
                                                <img src="{{ $item->product->uploads->getImageOptimizedFullName(400) }}"
                                                alt="" style="width: 100%;" class="mb-2">
                                                <a href="{{ url('products/') . '/' . $item->product->slug }}">
                                                    <h6>{{ $item->product_name }} - {{ $item->product_variant_name }}</h6>
                                                </a>
                                            @endif
                                            <a class="btn btn-primary pur" id="download" href="{{ url('/product/download/') . $item->id }}">
                                                <i class="bi bi-download"></i> Download
                                            </a>
                                            <button class="btn btn-danger pur">
                                                <i class="bi bi-link"></i> Create Item
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
