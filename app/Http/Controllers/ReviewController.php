<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Policies\ReviewPolicy;


class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $reviews = DB::table('review')->where('to_user', '=', $id)
            ->get();

        $user = User::find($id);

        return view('pages.reviews', ['reviews' => $reviews, 'id' => $id, 'from' => $user->username]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function newReviewForm($id)
    {
        $order = Order::find($id);
        $pol = new ReviewPolicy();
        if ($pol->newReview($order)) {
            return view('pages.newReview', ["order" => $order]);
        }

        abort(404);
    }

    public function newReview(Request $request, $id)
    {
        $order = Order::find($id);
        $pol = new ReviewPolicy();
        if ($pol->newReview($order)) {

            $product = Product::find($order->product);

            $review = new Review();
            $review->orderid = $order->id;
            $review->to_user = $product->seller;
            $review->from_user = $order->client;
            $review->rating = $request->input('rating');
            $review->comment = $request->input('comment');

            $review->save();

            return redirect()->route('user.id', ['id' => $order->seller]);
        }
        abort(404);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
    }
}
