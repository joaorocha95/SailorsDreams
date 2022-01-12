<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $categories = DB::table('category')
            ->select('name')
            ->distinct()
            ->get();

        //error_log(print_r($categories));
        return view('pages.categories', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $category = new Category();

        $this->authorize('create', $category);

        $category->name = $request->input('name');
        $category->save();

        return $category;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        if ($name == null)
            abort(404);

        $items = DB::table('category')->where('name', '=', $name)
            ->get();

        $products = DB::table('product')->where('id', '=', -1)
            ->get();

        foreach ($items as $item) {
            $products->push(Product::find($item->product_id));
        }

        return view('pages.products', compact('products'));
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function delete($name)
    {
        $category = Category::find($name);

        $this->authorize('delete', $category);
        $category->delete();

        return $category;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update($name)
    {
        $category = Category::find($name);

        $this->authorize('update', $category);

        $category->name = $request->input('name');
        $category->save();
    }
}
