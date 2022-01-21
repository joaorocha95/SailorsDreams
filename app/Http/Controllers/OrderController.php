<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Policies\OrderPolicy;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pol = new OrderPolicy();

        if ($pol->logCheck()) {
            $id = auth()->user()->id;
            $user = User::find($id);
            if ($user->acctype == 'Client') {
                $orders = DB::table('product')
                    ->join('order', 'product.id', '=', 'order.product')
                    ->where('order.client', '=', $id)
                    ->get();
            } else if ($user->acctype == 'Seller') {
                $orders = DB::table('order')
                    ->join('product', 'product.id', '=', 'order.product')
                    ->where('order.seller', '=', $id)
                    ->get();
            }
            return view('pages.orders', ['orders' => $orders]);
        }

        abort(404);
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
        $pol = new OrderPolicy();

        if ($pol->logCheck()) {
            $order = new Order();
            $order->order_type = 'Purchase';

            $order->product = $product->id;
            $order->seller = $product->seller;
            $order->client = $user_id;
            $order->total_price = $product->price;
            $order->save();

            $user = User::find($user_id);
            if ($user == null)
                abort(404);

            $user->acctype = 'Client';
            $user->save();
            return $order;
        }

        abort(404);
    }

    protected function Loan($product, $user_id, $loan_start, $loan_end)
    {
        $pol = new OrderPolicy();

        if ($pol->logCheck()) {
            if ($product->priceperday == 0 || $product->priceperday == null)
                abort(404);

            $order = new Order();
            $order->order_type = 'Loan';

            $order->product = $product->id;
            $order->seller = $product->seller;
            $order->client = $user_id;
            $order->total_price = $product->priceperday;
            $order->save();

            $user = User::find($user_id);
            if ($user == null)
                abort(404);

            $user->acctype = 'Client';
            $user->save();

            return $order;
        }

        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $pol = new OrderPolicy();

        if ($pol->logCheck()) {

            $product_id = $request->input('id');
            $user_id = auth()->user()->id;
            $product = Product::find($product_id);
            if (!$product->active || $product->seller == $user_id)
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

            $messages = DB::table('message')->where('associated_order', 'iLIKE', '%' . $order->id . '%')
                ->get();

            return redirect()->route('messagePage.id', [$order->id]);
        }

        //"{{ url('/login') }}"
        //return redirect()->route('messagePage.id', [$order->id]);
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pol = new OrderPolicy();
        $order = Order::find($id);
        if ($pol->showCheck($order)) {
            $product = Product::find($order->product);
            $messages = DB::table('message')->where('associated_order', 'iLIKE', '%' . $id . '%')
                ->get();

            if ($order == null) {
                abort(404);
            }
            return view('orders.order', ["order" => $order, "product" => $product, "messages" => $messages]);
        }
        abort(404);
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
        $pol = new OrderPolicy();

        if ($pol->sellerCheck()) {
            $order = Order::find($id);

            $this->authorize('update', $order);
            $order->order_status = $order_status;
            $order->save();

            return $order;
        }

        abort(404);
    }

    public function endOrder($id)
    {
        $pol = new OrderPolicy();

        if ($pol->sellerCheck()) {
            $order = Order::find($id);
            if ($order == null)
                abort(404);

            $order->order_status = 'Transaction_Completed';

            $order->save();

            return back()->with(["id" => $id]);
        }

        abort(404);
    }

    public function cancelOrder($id)
    {
        $pol = new OrderPolicy();

        if ($pol->sellerCheck()) {
            $order = Order::find($id);
            if ($order == null)
                abort(404);

            $order->order_status = 'Transaction_Failed';

            $order->save();

            return back()->with(["id" => $id]);
        }

        abort(404);
    }
}
