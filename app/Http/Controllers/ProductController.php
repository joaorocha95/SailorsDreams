<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
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
        $category = new Category();
        $id = auth()->user()->id;
        if ($id == null)
            abort(404);

        $product->seller = $id;
        $product->productname = $request->input('productname');
        $product->description = $request->input('description');

        if (isset($_POST['active']))
            $product->active = true;
        else
            $product->active = false;

        $product->img = $request->input('img');
        $product->price = $request->input('price');
        $product->priceperday = $request->input('priceperday');

        $category->name = $request->input('name');
        $category->product_id = $product->id;

        $product->save();
        error_log('BOOOOOOOOOOOOOOOOAS');
        error_log($product->id);
        error_log($category->product_id);


        $category->save();
        return redirect('products');
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
        if ($product == null)
            abort(404);
        return view('products.product', ["product" => $product]);
    }

    public function showNewForm()
    {
        $categories = DB::table('category')
            ->select('name')
            ->distinct()
            ->get();
        return view('products.new', ["category" => $categories]);
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

        return view('pages.productManager', ['productManager' => $product]);
    }

    public function showUpdateForm($id)
    {
        $product = Product::find($id);
        $categories = DB::table('category')
            ->select('name')
            ->distinct()
            ->get();
        if ($product == null)
            abort(404);
        return view('products.edit', ["product" => $product], ["category" => $categories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function updatepage(Request $request, $id)
    {
        $product = Product::find($id);
        if ($product == null)
            abort(404);

        if ($request->input('productname') != null)
            $product->productname = $request->input('productname');

        if ($request->input('description') != null)
            $product->description = $request->input('description');

        if (isset($_POST['active']))
            $product->active = true;
        else
            $product->active = false;

        if ($request->input('img') != null)
            $product->img = $request->input('img');

        if ($request->input('price') != null)
            $product->price = $request->input('price');

        if ($request->input('priceperday') != null)
            $product->priceperday = $request->input('priceperday');

        error_log($product);

        $product->save();
        return redirect('products');
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
