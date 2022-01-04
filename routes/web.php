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
// Home
Route::get('/', function () {
    return view('home');
});
Route::get('/home', function () {
    return view('home');
});


// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

//Users - M01
Route::get('/user/{id}', 'UsersController@show')->name('user.id');
Route::patch('/user/edit', 'UsersController@update');

//Produtos e Categorias - M02
Route::get('products', 'ProductController@index')->name('products');
Route::get('/products/{id}', 'ProductController@show')->name('products.id');
Route::post('/products/add', 'ProductController@create');
Route::delete('/products/{id}/delete', 'ProductController@delete');
Route::patch('/products/{id}/edit', 'ProductController@update');
Route::get('/categories/{category}', 'CategoryController@show');
Route::get('/categories', 'CategoryController@index');


//Reviews e Wishlist - M03
Route::get('/wishlist', 'WishlistController@index');
Route::post('/wishlist/add', 'WishlistController@addProduct');
Route::delete('/wishlist/delete', 'WishlistController@removeProduct');
Route::get('/reviews/user/{id}', 'ReviewController@index');
Route::post('/reviews/user/{id}/add', 'ReviewController@create');
Route::patch('/reviews/user/{id}/{review_id}/edit', 'ReviewController@update');
Route::delete('/reviews/user/{id}/{review_id}/delete', 'ReviewController@delete');


//Pedidos - M04
Route::get('/orders', 'OrderController@index')->name('orders');
Route::get('/orders/{id}', 'OrderController@show')->name('orders.id');
Route::post('/order/new', 'OrderController@create')->name('order.create');
Route::patch('/orders/{id}/cancel', 'OrderController@update');
Route::patch('/orders/{id}/edit', 'OrderController@update');

//Area de Mensagens - M05
Route::get('/messages', 'MessageController@index');
Route::get('/messages/{id}', 'MessageController@show');
Route::post('/messages/send', 'MessageController@sendMessage');
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
Route::get('/admin');
Route::get('/admin/categories', 'CategoryController@index');
Route::get('/admin/categories/{category}', 'CategoryController@show');
Route::post('/admin/categories/add', 'CategoryController@create');
Route::delete('/admin/categories/{category}/delete', 'CategoryController@delete');
Route::patch('/admin/categories/{category}/edit', 'CategoryController@update');
Route::get('/admin/accounts', 'UserController@index');
Route::get('/admin/accounts/{id}', 'UserController@show');
Route::patch('/admin/accounts/{id}/ban', 'UserController@update');
Route::patch('/admin/accounts/{id}/unban', 'UserController@update');
Route::get('/applications');
Route::post('/applications/submit');
Route::get('/applications/{id}');
Route::get('/applications/{id}/accept');
Route::get('/applications/{id}/reject');
