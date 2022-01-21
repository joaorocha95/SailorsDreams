<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function showCheck($order)
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

    public function sellerCheck()
    {
        if (auth()->check()) {
            $acctype = auth()->user()->acctype;
            return $acctype == 'Seller';
        }
        return false;
    }

    public function logCheck()
    {
        return auth()->check();
    }
}
