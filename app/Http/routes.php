<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('test', 'CheckoutController@test');


Route::controllers([
    'auth'=>'Auth\AuthController',
    'password'=>'Auth\PasswordController'
]);




//Rota index
Route::get('store/index',['as'=>'home','uses'=> 'StoreController@index']);
Route::get('/', 'StoreController@index');

//Mostrar produtos View
Route::get('category/{id}',['as'=>'store.category','uses'=> 'StoreController@category']);
Route::get('product/{id}',['as'=>'store.product','uses'=> 'StoreController@product']);


//carrinho de compra e chekout
Route::get('cart',['as'=>'cart','uses'=> 'CartController@index']);
Route::get('cart/add/{id}',['as'=>'cart.add','uses'=> 'CartController@add']);
Route::get('cart/destroy/{id}',['as'=>'cart.destroy','uses'=> 'CartController@destroy']);




//Rota AUTENTICADOS
Route::group(['middleware'=>'auth'],function()
{
    //Rota account
    Route::get('account/index',['as'=>'account.index','uses'=> 'AccountController@index']);
    Route::get('account/orders',['as'=>'account.orders','uses'=> 'AccountController@orders']);

//Rota checkout
    Route::get('checkout/placeOrder',['as'=>'checkout.place','uses'=> 'CheckoutController@place']);

});





//ADMINISTRACAO

Route::group(['prefix'=>'admin','middleware'=>'auth','where'=>['id'=>'[0-9]+']],function()
{

    Route::group(['prefix'=>'categories'],function()
    {
        Route::get('/',['as'=>'categories.index','uses'=> 'CategoriesController@index']);
        Route::get('create',['as'=>'categories.create','uses'=> 'CategoriesController@create']);
        Route::get('{id}/destroy',['as'=>'categories.destroy','uses'=> 'CategoriesController@destroy']);
        Route::get('{id}/edit',['as'=>'categories.edit','uses'=> 'CategoriesController@edit']);
        Route::post('{id}/update',['as'=>'categories.update','uses'=> 'CategoriesController@update']);
        Route::post('store',['as'=>'categories.store','uses'=> 'CategoriesController@store']);

    });


    Route::group(['prefix'=>'products'],function()
    {
        Route::get('/',['as'=>'products.index','uses'=> 'ProductsController@index']);
        Route::get('create',['as'=>'products.create','uses'=> 'ProductsController@create']);
        Route::get('{id}/destroy',['as'=>'products.destroy','uses'=> 'ProductsController@destroy']);
        Route::get('{id}/edit',['as'=>'products.edit','uses'=> 'ProductsController@edit']);
        Route::post('{id}/update',['as'=>'products.update','uses'=> 'ProductsController@update']);
        Route::post('store',['as'=>'products.store','uses'=> 'ProductsController@store']);

        Route::group(['prefix'=>'images'],function()
        {
            Route::get('{id}/product',['as'=>'products.images','uses'=> 'ProductsController@images']);
            Route::get('create/{id}/product',['as'=>'products.images.create','uses'=> 'ProductsController@createImage']);
            Route::post('store/{id}/product',['as'=>'products.images.store','uses'=> 'ProductsController@storeImage']);
            Route::get('destroy/{id}/product',['as'=>'products.images.destroy','uses'=> 'ProductsController@destroyImage']);

        });
    });

});
