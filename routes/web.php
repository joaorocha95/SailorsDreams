<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Events\MessageSent;
use App\Http\Controllers\Application;
use Illuminate\Support\Facades\DB;
use App\Models\Message;
use App\Models\Order;

// Home
Route::get('/', function () {
    $products = DB::table('product')->limit(5)->get();
    return view('home', ["product" => $products]);
})->name('home')->middleware('banned');
Route::get('/home', function () {
    $products = DB::table('product')->limit(5)->get();
    return view('home', ["product" => $products]);
})->middleware('banned');


// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->middleware('banned');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

//Users - M01
Route::get('/user/{id}', 'UsersController@show')->name('user.id');
Route::get('/user/{id}/edit', 'UsersController@update')->name('editProfile');
Route::patch('/user/{id}/edit', 'UsersController@updateProfile');
Route::delete('/user/delete/{id}', 'UsersController@delete')->name('deleteUser');

Route::get('/applications', 'ApplicationController@index')->name('showApplications');
Route::get('/applications/submit', 'ApplicationController@create')->name('newApplication');
Route::post('/applications/submit', 'ApplicationController@submitApplication');
Route::get('/applications/{id}', 'ApplicationController@show')->name('showApplication.id');
Route::patch('/applications/{id}/accept', 'ApplicationController@acceptApplication')->name('acceptApplication');
Route::patch('/applications/{id}/reject', 'ApplicationController@rejectApplication')->name('rejectApplication');



//Produtos e Categorias - M02
Route::get('products', 'ProductController@index')->name('products');
Route::get('/products/{id}', 'ProductController@show')->name('products.id');
Route::get('/product/new', 'ProductController@showNewForm')->name('newProduct');
Route::post('/product/new', 'ProductController@create');
Route::delete('/products/{id}/delete', 'ProductController@delete');
Route::get('/product/edit', 'ProductController@myProducts')->name('productManager');
Route::get('/product/edit/{id}', 'ProductController@showUpdateForm')->name('updatepage');
Route::patch('/product/edit/{id}', 'ProductController@updatepage');
Route::get('/categories/{name}', 'CategoryController@show')->name('category.name');
Route::get('/categories', 'CategoryController@index');


//Reviews e Wishlist - M03
Route::get('/wishlist', 'WishlistController@index');
Route::post('/wishlist/add', 'WishlistController@addProduct');
Route::delete('/wishlist/delete', 'WishlistController@removeProduct');
Route::get('/reviews/orders/{id}', 'ReviewController@index')->name('moreReviews');
Route::get('/orders/{id}/reviews/add', 'ReviewController@newReviewForm')->name('newReview.id');
Route::post('/orders/{id}/reviews/add', 'ReviewController@newReview');
Route::patch('/orders/{id}/reviews/{review_id}/edit', 'ReviewController@update');
Route::delete('/orders/{id}/reviews/{review_id}/delete', 'ReviewController@delete');


//Pedidos - M04
Route::get('/orders', 'OrderController@index')->name('orders');
Route::get('/orders/{id}', 'OrderController@show')->name('orders.id');
Route::post('/order/new', 'OrderController@create')->name('order.create');
Route::patch('/orders/{id}/cancel', 'OrderController@update');
Route::patch('/orders/{id}/edit', 'OrderController@update');
Route::patch('/orders/{id}/end', 'OrderController@endOrder')->name('endOrder');
Route::patch('/orders/{id}/cancel', 'OrderController@cancelOrder')->name('cancelOrder');

//Area de Mensagens - M05
Route::get('/message', 'MessageController@index')->name('myMessages');
Route::get('/message/{id}', 'MessageController@messagePage')->name('messagePage.id');
Route::post('/message/{id}', 'MessageController@sendMessage');
//Route::get('/messages/{id}', 'MessageController@show')->name('getMessage');
//Route::post('/message/{id}', 'MessageController@sendMessage');
Route::get('/tickets', 'TicketController@index');
Route::get('/tickets/{id}', 'TicketController@show');
Route::post('/tickets/new', 'TicketController@createTicket');


//Administração de utilizador e paginas estáticas - M06

Route::get('/help', function () {
    return view('help');
})->name('help');

Route::get('help/faq', function () {
    return view('faq');
})->name('faq');


Route::get('/about', function () {
    return view('about');
});



//Área de Gestão - M07
Route::get('/admin/products', 'ProductController@adminIndex')->name('admin.products');
Route::get('/admin/products/{id}', 'ProductController@adminShow')->name('admin.products.id');
Route::delete('/admin/products/{id}/delete', 'ProductController@delete')->name('admin.products.delete');
Route::patch('/admin/products/{id}/edit', 'ProductController@showUpdateForm')->name('admin.products.edit');
Route::get('/admin/categories', 'CategoryController@adminIndex')->name('showCategories');
Route::get('/admin/categories/{category}', 'CategoryController@adminShow')->name('showCategory');
Route::get('/admin/category/add', 'CategoryController@showNewForm')->name('addCategory');
Route::post('/admin/category/add', 'CategoryController@create');
Route::delete('/admin/categories/{category}/delete', 'CategoryController@delete')->name('deleteCategory');
Route::get('/admin/category/{category}/edit', 'CategoryController@showUpdateForm')->name('updateCategory');
Route::patch('/admin/category/{category}/edit', 'CategoryController@update');
Route::get('/admin/accounts', 'UsersController@index')->name('accounts');
Route::get('/admin/accounts/{id}', 'UsersController@adminShow')->name('accounts.id');
Route::patch('/admin/accounts/{id}/ban', 'UsersController@ban')->name('accounts.ban');
Route::patch('/admin/accounts/{id}/unban', 'UsersController@unban')->name('accounts.unban');
