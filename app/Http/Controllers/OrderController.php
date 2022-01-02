<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::check()) return redirect('/login');
        $this->authorize('list', Order::class);
        $orders = Auth::user()->orders()->orderBy('id')->get();
        return view('pages.orders', ['orders' => $orders]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $product, $client, $order_status, $order_type, $loan_start, $loan_end)
    {
        $order = new Order();

        $this->authorize('create', $order);

        $order->product = $product;
        $order->client = $client;
        $order->order_status = $order_status;
        $order->order_type = $order_type;
        $order->loan_start = $loan_start;
        $order->loan_end = $loan_end;
        $order->total_price = $request->input('total_price');
        $order->save();

        return $order;
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);
        $this->authorize('show', $order);
        return view('pages.order', ['order' => $order]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $order_status)
    {
        $order = Order::find($id);

        $this->authorize('update', $order);
        $order->order_status = $order_status;
        $order->save();

        return $order;
    }
}
