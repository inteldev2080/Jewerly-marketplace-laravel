@extends('backend.layouts.app', ['activePage' => 'orders', 'title' => 'Order', 'navName' => 'orderslist', 'activeButton' => 'catalogue'])

@section('content')
    <style>
        .order-status {
            width: max-content;
            padding: 2px 4px;
        }
    </style>

    <div class="page-header">
        <div class="row align-items-end">
            <h1 class="page-header-title">Order</h1>
        </div>
    </div>

    <div class="container">
        <div class="col-lg-10 col-md-12 py-4 mx-auto checkout-wrap">
            <div class="order-items-card border-bottom py-4 mb-4">
                <div class="row">
                    <div class="col-lg-3 col-6 mb-2">
                        <div class="w-100 fs-18 fw-600">Order number</div>
                        <div class="fs-14 text-primary">#{{ $order->order_id }}</div>
                    </div>
                    <div class="col-lg-3 col-6 mb-2">
                        <div class="w-100 fs-18 fw-600">Payment status</div>
                        <div class="fs-14" title="{{ $order->status_payment_reason }}">
                            {{ ucwords(Config::get('constants.oder_payment_status')[$order->status_payment]) }}
                        </div>
                    </div>
                    <div class="col-lg-3 col-6 mb-2">
                        <div class="w-100 fs-18 fw-600">Fufilment status</div>
                        <div class="fs-14 ">
                            @php
                                $status = 'Fulfilled';
                                foreach ($order->items as $key => $item) {
                                    if ($item->status_fulfillment == '1') {
                                        $status = 'Unfulfilled';
                                    }
                                }
                                
                                echo $status;
                            @endphp
                        </div>
                    </div>
                    <div class="col-lg-3 col-6 mb-2">
                        <div class="w-100 fs-18 fw-600">Date created</div>
                        <div class="fs-14 ">{{ date('F d, Y', strtotime($order->created_at)) }}</div>
                    </div>
                </div>
            </div>

            @foreach ($order->items as $key => $item)
                <div class="order-items-card pb-4">
                    <div class="row">
                        <div class="col-lg-2 col-3">
                            <img src="{{ asset('uploads/all/' . $item->product->uploads->file_name) }}" alt=""
                                class="thumbnail border w-100">
                        </div>
                        <div class="col-lg-10 col-9">
                            <div class="order-item-title fs-24 mt-2 fw-600">
                                @php
                                    if ($item->product_variant != 0) {
                                        echo $item->product_name . ' - ' . $item->product_variant_name;
                                    } else {
                                        echo $item->product_name;
                                    }
                                @endphp
                            </div>
                            <div class="order-item-qty-price fs-16 mt-2"><span class="fw-600">Quantity</span>
                                {{ $item->quantity }} | <span class="fw-600">Price</span>
                                ${{ number_format($item->price / 100, 2) }}
                            </div>
                            @if (!$item->product->is_digital)
                                <div class="is_downloadable fw-600 fs-16 mt-2" data-item-id="{{ $item->id }}"
                                    data-product-id="{{ $item->product->id }}"
                                    data-product-digital-assets="{{ $item->product->digital_download_assets }}">
                                    @if (!$item->product->digital_download_assets)
                                        <span class="fw-900 fs-14 badge bg-danger">No digital asset attached</span>
                                        <div class="order-item-title fs-17 fw-600 mt-2">File anavailable. Please contact
                                            support.</div>
                                        <div class="card-body digital-assets-file-wrap">
                                            <label class="btn text-primary mt-2 p-0 getFileManagerModel cursor-pointer" onclick="openFileMangerModal(event)">Select
                                                asset</label>
                                            <input type="hidden" class="digital_assets" name="digital_download_assets"
                                                value="{{ $item->product->digital_download_assets }}">
                                        </div>
                                    @else
                                        <span class="fw-900 fs-14 badge bg-success">Digital asset attached</span>
                                        <span class="fw-900 fs-14 mt-2 d-block" class=""
                                            data-product-id="{{ $item->id }}">{{ $item->product->digitalImage->file_original_name . '.' . $item->product->digitalImage->extension }}</span>
                                        <div class="card-body digital-assets-file-wrap">
                                            <label class="btn text-primary mt-2 p-0 getFileManagerModel cursor-pointer" onclick="openFileMangerModal(event)">Select
                                                asset</label>
                                            <input type="hidden" class="digital_assets" name="digital_download_assets"
                                                value="{{ $item->product->digital_download_assets }}">
                                        </div>
                                    @endif
                                    @php
                                        $orderStatus = Config::get('constants.order_item_status_fulfillment');
                                    @endphp
                                    <div class="d-flex mt-2">
                                        <select class="order-status form-select" data-item-id="{{ $item->id }}"
                                            style="width: 120px;height:35.75px;padding-left:10px">
                                            @foreach ($orderStatus as $key => $status)
                                                @if ($key != 0)
                                                    <option @if ($item->status_fulfillment == $key) selected @endif
                                                        value="{{ $key }}">{{ $status }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <input class='@if($item->status_fulfillment != 2) d-none @endif track_number form-control mx-2' type='text'
                                            placeholder='Tracking Number ' value='{{ $item->status_tracking }}'
                                            style="width: 100px;" />
                                    </div>
                                    <button
                                        class='save_track_number btn btn-sm btn-primary mt-2 @if ($item->status_fulfillment != 2) d-none @endif'
                                        onclick="changeStatusTracking(event)">save</button>
                                </div>
                            @endif
                        </div>
                        <!--<div class="col-lg-2">${{ number_format($item->price / 100, 2) }}</div>-->
                    </div>
                </div>
            @endforeach

            <div class="col-lg-4">
                <h5 class="fs-18 py-2 fw-600">Billing Address</h5>
                @include('includes.validation-form')
                <x-order-info :order="$order" />
            </div>
        </div>
    </div>

    <div id="fileManagerContainer"></div>

    <script>
        $(function() {
            $('.order-status').change(function() {
                var orderItemId = $(this).attr('data-item-id');
                if ($(this).val() == '2') {
                    $(this).closest(".is_downloadable").find('.track_number').removeClass('d-none');
                    $(this).closest(".is_downloadable").find('.save_track_number').removeClass('d-none');
                } else {
                    $(this).closest(".is_downloadable").find(".track_number").addClass('d-none');
                    $(this).closest(".is_downloadable").find(".save_track_number").addClass('d-none');
                }
                $.ajax({
                    url: "{{ url('backend/orders/item') }}" + "/" + orderItemId,
                    type: 'put',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        status: $(this).val()
                    },
                    success: function(data) {
                        console.log(data)
                    }
                })
            });

        });

        function changeStatusTracking(e) {
            var orderItemId = $(e.target).closest(".is_downloadable").attr("data-item-id");
            $.ajax({
                url: "{{ url('backend/orders/status_tracking/') }}" + "/" + orderItemId,
                type: 'put',
                data: {
                    "_token": "{{ csrf_token() }}",
                    status: $(e.target).closest(".is_downloadable").find(".track_number").val()
                },
                async: false, 
                success: function(data) {
                    console.log(data)
                }
            })

            var productId = $(e.target).closest(".is_downloadable").attr("data-product-id");
            $.ajax({
                url: "{{ url('backend/products/update_digital_assets/') }}" + "/" + productId,
                type: 'put',
                async: false, 
                data: {
                    "_token": "{{ csrf_token() }}",
                    value: $(e.target).closest(".is_downloadable").find(".digital_assets").val()
                },
                success: function(data) {
                    console.log(data)
                }
            })
            location.reload();
        }

        function openFileMangerModal(e) {
            var target = $(e.target);
            $.ajax({
                url: "{{ route('backend.file.show') }}",
                success: function(data) {
                    if (!$.trim($('#fileManagerContainer').html()))
                        $('#fileManagerContainer').html(data);

                    $('#fileManagerModal').modal('show');

                    const getSelectedItem = function(selectedId, filePath) {
                        $(target).closest(".is_downloadable").find(".digital_assets").val(
                            selectedId);
                    }
                    var digital_assets = $(target).closest(".is_downloadable").find(
                        ".digital_assets").val();
                    if (digital_assets == '') digital_assets = [];
                    setSelectedItemsCB(getSelectedItem, [digital_assets], false);
                    $(target).closest(".is_downloadable").find(".save_track_number").removeClass(
                        'd-none');
                }
            })
        }
    </script>
@endsection
