<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    public function index()
    {
    }

    public function messagePage($order)
    {
        $client_id = $order->client;
        $product = Product::find($order->product);
        $seller = $product->seller;
        if (auth()->check()) {
            $acctype = auth()->user()->acctype;
            return auth()->user()->id == $client_id || $acctype == 'Admin' || auth()->user()->id == $seller;
        }
        return false;
    }

    public function sendMessage()
    {
    }

    public function show()
    {
    }
}
