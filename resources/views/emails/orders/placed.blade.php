<div align="center">
    <a href="{{route('index')}}">
        <img src="{{asset('img/logo.png')}}" width="100" alt="logo.png">
    </a>
</div>
<h1 align="center">Order has been Placed!</h1>
<p align="center">
    Tracking number of the order: {{$tracking_number}}.
</p>
<table style="margin-left: auto; margin-right: auto;">
    <thead>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order_items as $order_item)
            <tr>
                <td>
                    <a href="{{route('products.show', $order_item->id)}}">
                        {{$order_item->name}}
                    </a>
                </td>
                <td>${{$order_item->price}}</td>
                <td>{{$order_item->qty}}</td>
            </tr>
        @endforeach
    </tbody>
</table>