<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;


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
    public function create(Request $request, $id, $to, $from)
    {
        $review = new Review();

        $this->authorize('create', $review);
        $review->id = $id;
        $review->to = $to;
        $review->from = $from;
        $review->rating = $request->input('rating');
        $review->comment = $request->input('comment');
        $review->date = date("Y/m/d");
        $review->save();

        return $review;
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
