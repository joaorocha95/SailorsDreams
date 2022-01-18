<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryNames;
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

        $categories = DB::table('categorynames')
            ->get();

        return view('pages.categories', ['categories' => $categories]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminIndex()
    {
        $categories = DB::table('categorynames')
            ->get();

        return view('admin.categories', ['categories' => $categories]);
    }

    public function showNewForm()
    {
        return view('admin.categoryNew');
    }

    public function showUpdateForm($id)
    {
        $category = CategoryNames::find($id);

        return view('admin.categoryEdit', ['category' => $category]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $category = new CategoryNames();

        $category->name = $request->input('name');
        $category->save();

        return redirect('admin/categories');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($id == null)
            abort(404);

        $items = DB::table('category')->where('name', '=', $id)
            ->get();

        $products = DB::table('product')->where('id', '=', -1)
            ->get();

        foreach ($items as $item) {
            $products->push(Product::find($item->product_id));
        }

        return view('pages.products', compact('products'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function adminShow($id)
    {
        if ($id == null)
            abort(404);

        $category = CategoryNames::find($id);

        return view('admin.categoryDetails', ['category' => $category]);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $category = CategoryNames::find($id);
        $category->delete();

        return redirect('admin/categories');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = CategoryNames::find($id);

        if ($request->input('name') != null)
            $category->name = $request->input('name');
        $category->save();

        return redirect('admin/categories');
    }
}
