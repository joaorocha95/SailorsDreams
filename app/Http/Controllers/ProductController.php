<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $products = DB::table('product')->where('productname', 'iLIKE', '%' . $request->term . '%')
            ->get();
        //error_log("Produtos encontrados: " . $products);

        return view('pages.products', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $product = new Product();
        $id = auth()->user()->id;
        if ($id == null)
            abort(404);
        $this->authorize('create', $product);
        $product->seller = $id;
        $product->productname = $request->input('productname');
        $product->description = $request->input('description');
        $product->active = $request->input('active');
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
        //error_log("-----------------------------------------" . $product);
        if ($product == null)
            abort(404);
        return view('products.product', ["product" => $product]);
    }

    public function showNewForm()
    {
        return view('products.new');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function myProducts()
    {
        $id = auth()->user()->id;
        if ($id == null)
            abort(404);
        $product = DB::table('product')->where('seller', '=', $id)
            ->get();
        error_log("-----------------------------------------" . $product);

        return view('pages.productManager', ['productManager' => $product]);
    }

    public function showUpdateForm($id)
    {
        $product = Product::find($id);
        //error_log("-----------------------------------------" . $product);
        if ($product == null)
            abort(404);
        return view('products.edit', ["product" => $product]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function updatepage($id, Request $request)
    {
        $product = Product::find($id);
        //error_log("-----------------------------------------" . $product);
        if ($product == null)
            abort(404);
        $this->authorize('create', $product);
        $product->productname = $request->input('productname');
        $product->description = $request->input('description');
        $product->active = $request->input('active');
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
