<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = auth()->user()->id;
        if ($id == null)
            abort(404);
        $orders = DB::table('order')->where('client', '=', $id)
            ->get();

        return view('pages.orders', ['orders' => $orders]);
    }

    function dateDiff($date1, $date2)
    {
        $date1_ts = strtotime($date1);
        $date2_ts = strtotime($date2);
        $diff = $date2_ts - $date1_ts;
        return round($diff / 86400);
    }

    protected function Purchase($product, $user_id)
    {
        $order = new Order();
        $order->order_type = 'Purchase';

        $order->product = $product->id;
        $order->client = $user_id;
        $order->total_price = $product->price;
        $order->save();
        return $order;
    }

    protected function Loan($product, $user_id, $loan_start, $loan_end)
    {
        if ($product->priceperday == 0 || $product->priceperday == null)
            abort(404);

        $order = new Order();
        $order->order_type = 'Loan';

        $order->product = $product->id;
        $order->client = $user_id;
        $order->total_price = $product->priceperday;
        $order->save();
        return $order;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //error_log("Request para criar uma nova order------------ " . $request->input('id'));

        if (!(Auth::check()))
            return redirect('/login');

        $product_id = $request->input('id');
        $user_id = auth()->user()->id;
        $product = Product::find($product_id);
        if (!$product->active)
            abort(401);
        if ($request->order_type == 'Purchase')
            $order = $this->Purchase(
                $product,
                $user_id
            );
        elseif ($request->order_type == 'Loan')
            $order = $this->Loan(
                $product,
                $user_id,
                $request->input('loan_start'),
                $request->input('loan_end')
            );



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
        $product = Product::find($order->product);

        if ($order == null)
            abort(404);
        return view('orders.order', ["order" => $order, "product" => $product]);
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
