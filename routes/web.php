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

// Home
Route::get('/', 'Auth\LoginController@home');

// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'UsersController@create');

//Users - M01
Route::get('/users/{id}', 'UserController@show');
Route::patch('/users/edit', 'UserController@update');

//Produtos e Categorias - M02
Route::get('/products', 'ProductController@index');
Route::get('/products/{id}', 'ProductController@show');
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
Route::get('/orders', 'OrderController@index');
Route::get('/orders/{id}', 'OrderController@show');
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
Route::get('/faqs', function(){
    return view('pages.faqs');
});

Route::get('/about', function(){
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