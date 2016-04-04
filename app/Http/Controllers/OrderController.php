<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::getBasedOnUser();
        $orders->transform(fn($i) => $i->formatPrice());

        return view('orders.index', compact('orders'));
    }

    public function show($orderId)
    {
        $order = Order::with('items', 'items.product:id,name,slug,product_thumbnail,is_digital')->where('order_id', $orderId)->first();

        return view('orders.show', compact('order'));
    }

    public function update(UpdateOrderRequest $req, Order $order)
    {
        $this->authorize('edit', $order);

        $order->adminUpdate($req);

        return redirect()->route('orders.show', $order);
    }
}
