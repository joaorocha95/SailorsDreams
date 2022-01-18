<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\File;

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminIndex(Request $request)
    {
        $products = DB::table('product')->where('productname', 'iLIKE', '%' . $request->term . '%')
            ->get();
        //error_log("Produtos encontrados: " . $products);

        return view('admin.products', compact('products'));
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

        $product->seller = $id;
        $product->productname = $request->input('productname');
        $product->description = $request->input('description');
        if (isset($_POST['active']))
            $product->active = true;
        else
            $product->active = false;


        $path = $request->file('img')->store('public/images');
        $product->img = $path;



        $product->price = $request->input('price');
        $product->priceperday = $request->input('priceperday');
        $product->save();

        $category = new Category();
        $category->product_id = $product->id;
        $category->name = $request->input('name');
        $category->save();

        return view('products.product', ["product" => $product]);
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function adminShow($id)
    {
        $product = Product::find($id);
        if ($product == null)
            abort(404);
        return view('admin.productDetails', ["product" => $product]);
    }

    public function showNewForm()
    {
        $categories = DB::table('categorynames')
            ->get();
        return view('products.new', ["categories" => $categories]);
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
        $categories = DB::table('categorynames')
            ->get();
        if ($product == null)
            abort(404);

        return view('products.edit', ["product" => $product], ["categories" => $categories]);
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
        $id_aux = auth()->user()->id;
        $user = User::find($id_aux);

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

        $product->save();

        if ($request->input('name') != null) {
            DB::table('category')->where('product_id', '=', $id)->delete();

            $category = new Category();
            $category->product_id = $id;
            $category->name = $request->input('name');
            $category->save();
        }

        if ($user->acctype == 'Admin')
            return redirect('admin/products');
        else
            return redirect('products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $product = Product::find($id);

        $product->delete();

        return redirect('admin/products');
    }
}
