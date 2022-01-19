<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $reviews = Auth::review()->user()->get();
        return view('pages.review.user', ['review' => $reviews]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function newReviewForm($id)
    {

        $order = Order::find($id);
        return view('pages.newReview', ["order" => $order]);
    }

    public function newReview(Request $request, $id)
    {
        $order = Order::find($id);
        $product = Product::find($order->product);
        error_log("----------------------------------" .  $order);
        $review = new Review();
        $review->orderid = $order->id;
        $review->to_user = $product->seller;
        $review->from_user = $order->client;
        $review->rating = $request->input('rating');
        $review->comment = $request->input('comment');

        $review->save();

        return view('products.product', ["product" => $product]);
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
        $review = Review::find($id);
        $this->authorize('update', $review);
        $review->rating = $request->input('rating');
        $review->comment = $request->input('comment');
        $review->save();
        return $review;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        $review = Review::find($id);
        $this->authorize('delete', $review);
        $review->delete();
        return $review;
    }
}
