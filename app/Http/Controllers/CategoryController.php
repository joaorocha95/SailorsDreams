<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryNames;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Policies\CategoryPolicy;


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
        $pol = new CategoryPolicy();

        if ($pol->adminCheck()) {
            $categories = DB::table('categorynames')->get();

            return view('admin.categories', ['categories' => $categories]);
        }

        abort(404);
    }

    public function showNewForm()
    {
        $pol = new CategoryPolicy();

        if ($pol->adminCheck()) {
            return view('admin.categoryNew');
        }

        abort(404);
    }

    public function showUpdateForm($id)
    {
        $pol = new CategoryPolicy();

        if ($pol->adminCheck()) {
            $category = CategoryNames::find($id);

            return view('admin.categoryEdit', ['category' => $category]);
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
        $pol = new CategoryPolicy();

        if ($pol->adminCheck()) {

            $category = new CategoryNames();

            $category->name = $request->input('name');
            $category->save();

            return redirect('admin/categories');
        }

        abort(404);
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

        return view('pages.products', compact('products'))->with('flag', '0');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function adminShow($id)
    {

        $pol = new CategoryPolicy();

        if ($pol->adminCheck()) {

            $category = CategoryNames::find($id);

            return view('admin.categoryDetails', ['category' => $category]);
        }

        abort(404);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $pol = new CategoryPolicy();

        if ($pol->adminCheck()) {
            $category = CategoryNames::find($id);
            $category->delete();

            return redirect('admin/categories');
        }

        abort(404);
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
        $pol = new CategoryPolicy();

        if ($pol->adminCheck()) {
            $category = CategoryNames::find($id);

            if ($request->input('name') != null)
                $category->name = $request->input('name');
            $category->save();

            return redirect('admin/categories');
        }

        abort(404);
    }
}
