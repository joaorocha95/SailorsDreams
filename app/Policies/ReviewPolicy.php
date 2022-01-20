<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
{
    public function index()
    {
    }


    public function newReview($order)
    {
        return $order->client == auth()->user()->id;
    }

    public function delete()
    {
    }
}
