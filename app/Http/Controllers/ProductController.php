<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Policies\ProductPolicy;
use Illuminate\Support\Facades\Storage;
use App\Models\File;
use App\Models\Review;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = DB::table('product')->where('productname', 'iLIKE', '%' . $request->term . '%')->simplepaginate(6);

        return view('pages.products', compact('products'))->with('flag', '1');
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminIndex(Request $request)
    {
        $pol = new ProductPolicy();

        if ($pol->adminCheck()) {
            $products = DB::table('product')->where('productname', 'iLIKE', '%' . $request->term . '%')
                ->get();

            return view('admin.products', compact('products'));
        }

        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $pol = new ProductPolicy();

        if ($pol->sellerCheck()) {
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


            if ($request->file('img')) {
                $file = $request->file('img');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('uploads/productImages', $filename);
                $product->img = $filename;
            }

            $product->price = $request->input('price');
            $product->priceperday = $request->input('priceperday');
            $product->save();

            $category = new Category();
            $category->product_id = $product->id;
            $category->name = $request->input('name');
            $category->save();

            $seller = User::find($product->seller);

            $reviews = DB::table('review')->where('to_user', '=', $seller->id)
                ->limit(3)
                ->get();

            return redirect()->route('products.id', ["product" => $product, "seller" => $seller, "reviews" => $reviews]);
        }

        abort(404);
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

        $seller = User::find($product->seller);
        $reviews = DB::table('review')->where('to_user', '=', $seller->id)
            ->limit(3)
            ->get();
        return view('products.product', ["product" => $product, "seller" => $seller, "reviews" => $reviews]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function adminShow($id)
    {
        $pol = new ProductPolicy();

        if ($pol->adminCheck()) {
            $product = Product::find($id);
            if ($product == null)
                abort(404);

            $seller = User::find($product->seller);
            $reviews = DB::table('review')->where('to_user', '=', $seller->id)
                ->limit(3)
                ->get();
            return view('admin.productDetails', ["product" => $product, "seller" => $seller, "reviews" => $reviews]);
        }

        abort(404);
    }

    public function showNewForm()
    {
        $pol = new ProductPolicy();

        if ($pol->sellerCheck()) {
            $categories = DB::table('categorynames')
                ->get();
            return view('products.new', ["categories" => $categories]);
        }

        abort(404);
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
        $pol = new ProductPolicy();

        if ($pol->sellerCheck()) {
            $id = auth()->user()->id;
            $product = DB::table('product')->where('seller', '=', $id)
                ->get();

            return view('pages.productManager', ['productManager' => $product]);
        }

        abort(404);
    }

    public function showUpdateForm($id)
    {
        $pol = new ProductPolicy();
        $product = Product::find($id);

        if ($pol->updateCheck($product)) {

            $categories = DB::table('categorynames')
                ->get();
            if ($product == null)
                abort(404);

            return view('products.edit', ["product" => $product], ["categories" => $categories]);
        }

        abort(404);
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
        $pol = new ProductPolicy();
        $product = Product::find($id);
        if ($product == null)
            abort(404);

        if ($pol->updateCheck($product)) {

            if ($request->input('productname') != null)
                $product->productname = $request->input('productname');

            if ($request->input('description') != null)
                $product->description = $request->input('description');

            if (isset($_POST['active']))
                $product->active = true;
            else
                $product->active = false;

            if ($request->file('pic') != null) {
                $file = $request->file('pic');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('uploads/productImages', $filename);
                $product->img = $filename;
            }

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

            if (auth()->user()->acctype == 'Admin')
                return redirect('admin/products');
            else
                return redirect('products');
        }

        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $pol = new ProductPolicy();

        if ($pol->adminCheck()) {
            $product = Product::find($id);

            $product->delete();

            return redirect('admin/products');
        }

        abort(404);
    }
}
