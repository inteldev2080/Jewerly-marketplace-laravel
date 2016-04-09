<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::getBasedOnUser();
        $orders->transform(fn($i) => $i->formatPrice());
        return view('backend.orders.list', compact('orders'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pending()
    {
        $orders = OrderItem::select('orders.*', 'users.email')
                        ->join('orders', 'orders.order_id', '=', 'order_items.order_id')
                        ->join('users', 'users.id', '=', 'orders.user_id')
                        ->where('order_items.status_fulfillment', '=', 1)
                        ->groupBy('order_items.order_id')
                        ->get();
                        // ->toArray();
                        // var_dump($orders);die;
        // $orders->transform(fn($i) => $i->formatPrice());
        return view('backend.orders.pending', compact('orders'));
    }

    /**
     * Return count of the pending orders.
     *
     * @return int count
     */
    public function pending_badge() {
        $count = OrderItem::select('order_items.order_id')
                        ->join('orders', 'orders.order_id', '=', 'order_items.order_id')
                        ->where('order_items.status_fulfillment', '=', 1)
                        ->groupBy('order_items.order_id')
                        ->get()
                        ->toArray();

        return count($count);
    }

    public function status_tracking_set(Request $request, $id) {
        return OrderItem::where('id', $id)->update(['status_tracking' => $request->status]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('backend.orders.show')->with('order', Order::find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // 
        return OrderItem::where('id', $id)->update(['status_fulfillment' => $request->status]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
