<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Policies\MessagePolicy;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        if ($id == null)
            abort(404);

        if ($user->acctype == 'Client') {
            $orders = DB::table('users')
                ->join('order', 'users.id', '=', 'order.seller')
                ->where('client', '=', $id)
                ->get();
        } else if ($user->acctype == 'Seller') {
            $orders = DB::table('users')
                ->join('order', 'users.id', '=', 'order.client')
                ->where('seller', '=', $id)
                ->get();
        }
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
        $pol = new MessagePolicy();
        if ($pol->messagePage($order)) {


            $product = Product::find($order->product);
            $messages = DB::table('message')
                ->join('users', 'users.id', '=', 'message.sender')
                ->where('associated_order', 'iLIKE', '%' . $id . '%')
                ->get();

            if ($order == null)
                abort(404);
            return view('messages.message', ["order" => $order, "product" => $product, "messages" => $messages]);
        }

        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendMessage(Request $request)
    {
        $message = new Message();

        $message->message_type = $request->input('message_type');
        $message->associated_order = $request->input('associated_order');
        $message->associated_ticket = $request->input('associated_ticket');
        $message->message = $request->input('message');
        $message->sender = auth()->user()->id;

        $message->save();
        $order = Order::find($message->associated_order);
        $product = Product::find($order->product);
        $messages = DB::table('message')->where('associated_order', 'iLIKE', '%' . $message->associated_order . '%')
            ->get();
        return back()->with(["order" => $order, "product" => $product, "messages" => $messages]);
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
