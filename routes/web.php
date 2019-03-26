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

//主页
route::any('/',"IndexController@index");
//路由组index
Route::prefix('/')->group(function(){
    route::any('userpage','IndexController@userpage')->middleware('logs');
    route::any('shopcart','IndexController@shopcart')->middleware('logs');
    route::any('allshops','IndexController@allshops');
    route::any('shopcontent/{id?}','IndexController@shopcontent');
    route::any('set','IndexController@set');
    route::any('edituser','IndexController@edituser');
    route::any('address','IndexController@address');
    route::any('writeaddr','IndexController@writeaddr');
    route::any('getarea/{id}','IndexController@getarea');
    route::any('writeaddrdo','IndexController@writeaddrdo');
    //邀请有奖
    route::any('invite','IndexController@invite');
    //我的钱包
    route::any('mywallet','IndexController@mywallet');
    //晒单
    route::any('sharedetail','IndexController@sharedetail');
    //潮购记录
    route::any('recorddetail','IndexController@recorddetail');
});
//路由组login
Route::prefix('/')->group(function(){
    route::any('login','User\UserController@login');
    route::any('register','User\UserController@register');
    route::any('register/do','User\UserController@regdo');
    route::any('regauth','User\UserController@regauth');
    route::post('logindo','User\UserController@logindo');
    route::post('code','User\UserController@code');
});
//路由组goods
Route::prefix('/')->group(function(){
    route::post('cateshop','Goods\GoodsController@cateshop');
    route::get('cateshops/{id?}','Goods\GoodsController@cateshops');
    route::post('sortshop','Goods\GoodsController@sortshop');
    //结算
    route::any('payment','Goods\GoodsController@payment');

});
    route::any('verify/create','CaptchaController@create');

Route::prefix('/')->group(function(){
    route::post('cartadd','CartController@cartadd')->middleware('logs');
    route::post('cartdel','CartController@cartdel');
});