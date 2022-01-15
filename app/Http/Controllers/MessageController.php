<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class MessageController extends Controller
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

        $messages = DB::table('message')->where('associated_order', '=', -1)
            ->get();

        foreach ($orders as $order) {
            $messages->push(Message::find($order->id));
        }

        return view('pages.message', ['orders' => $orders], ['messages' => $messages]);
    }


    public function messagePage($id)
    {
        $order = Order::find($id);
        $product = Product::find($order->product);
        $messages = DB::table('message')->where('associated_order', 'iLIKE', '%' . $id . '%')
            ->get();

        if ($order == null)
            abort(404);
        return view('messages.message', ["order" => $order, "product" => $product, "messages" => $messages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendMessage(Request $request)
    {
        if (!(Auth::check()))
            return redirect('/login');
        error_log($request);

        $message = new Message();

        $message->message_type = $request->input('message_type');
        $message->associated_order = $request->input('associated_order');
        $message->associated_ticket = $request->input('associated_ticket');
        $message->message = $request->input('message');
        $message->sender = auth()->user()->id;

        $message->save();

        error_log($request);
        $order = Order::find($message->associated_order);
        $product = Product::find($order->product);
        $messages = DB::table('message')->where('associated_order', 'iLIKE', '%' . $message->associated_order . '%')
            ->get();
        return view('messages.message ', ["order" => $order, "product" => $product, "messages" => $messages]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }
}
