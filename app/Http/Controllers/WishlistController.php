<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function addProduct($idUser, $idProduct)
    {

        $wishlist = Wishlist::find($idUser);

        $this->authorize('addProduct', $wishlist);
        $wishlist->idProduct = $idProduct;
        $wishlist->save();
        return $wishlist;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function removeProduct($idUser, $idProduct)
    {

        $wishlist = Wishlist::find($idUser);

        $this->authorize('delete', $wishlist);
        $wishlist->delete();

        return $wishlist;
    }
}
