<x-app-layout page-title="My Orders">
<div class="container">
    <div class="col-lg-8 col-md-10 py-8 mx-auto checkout-wrap">
        <h1 class="fw-800">Order history</h1>
        <p class="pb-4">Check the status of recent orders, view order details, and fufilment status.</p>

        <x-orders-table :orders="$orders"/>
        {{$orders->links()}}

    </div>
</div>
</x-app-layout>
