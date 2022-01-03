<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){  
        error_log($request->all());
        /*if (!Auth::check()) return redirect('/products');
        $this->authorize('list', Product::class);
        $products = Auth::products()->orderBy('id')->get();
        return view('pages.products', ['products' => $products]);*/
        $products=Product::all();
        //error_log($products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $seller, $active)
    {
        $product = new Product();

        $this->authorize('create', $product);
        $product->seller = $seller;
        $product->productname = $request->input('productname');
        $product->description = $request->input('description');
        $product->active = $active;
        $product->price = $request->input('price');
        $product->pricePerDay = $request->input('pricePerDay');

        $product->save();
        return $product;
    }

     /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        $this->authorize('show', $product);
        return view('pages.product', ['product' => $product]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $seller, $active)
    {
        $product = Product::find($id);

        $this->authorize('update', $product);
        $product->seller = $seller;
        $product->productname = $request->input('productname');
        $product->description = $request->input('description');
        $product->active = $active;
        $product->price = $request->input('price');
        $product->pricePerDay = $request->input('pricePerDay');

        $product->save();
        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        $product = Product::find($id);

        $this->authorize('delete', $product);
        $product->delete();

        return $product;
    }
   
}

