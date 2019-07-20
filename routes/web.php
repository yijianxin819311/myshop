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

Route::get('/', function () {
    return view('welcome');
});
//后台
Route::get('/admin','admin\IndexController@index');
//后台登录
Route::get('/admin/login','admin\IndexController@login');
//后台登录处理
Route::post('/admin/login_do','admin\IndexController@login_do');



Route::get('/index','index\IndexController@index');
//前台登录
Route::get('/index/login','index\IndexController@login');
//前台登录执行
Route::post('/index/login_do','index\IndexController@login_do');
//项目注册
Route::get('/index/register','index\IndexController@register');
//项目注册执行
Route::post('/index/register_do','index\IndexController@register_do');
//商品上传
Route::get('/admin/goods_add','admin\GoodsController@goods_add');
//商品上传执行
Route::post('/admin/goods_add_do','admin\GoodsController@goods_add_do');
//商品列表展示
Route::get('/admin/goods_list','admin\GoodsController@goods_list');
//商品删除
Route::get('/admin/goods_delete','admin\GoodsController@goods_delete');
//商品修改
Route::get('/admin/goods_update','admin\GoodsController@goods_update');
//商品修改执行
Route::post('/admin/goods_update_do','admin\GoodsController@goods_update_do');
//商品详情
Route::get('/index/goodsdetail','index\IndexController@goodsdetail');
//购物车
Route::get('/index/cart','index\IndexController@cart');

//购物车列表
Route::get('/index/cartlist','index\IndexController@cartlist');
//购物车删除
Route::get('/index/cartdelete', 'index\IndexController@cartdelete');
//订单详情
//Route::get('/index/order', 'index\IndexController@order');

//支付宝
Route::get('/do_pay', 'PayController@do_pay');
//订单详情
Route::get('confirm_pay', 'PayController@confirm_pay');
//支付订单详情
Route::get('index/order_list', 'index\IndexController@order_list');
Route::get('pay_order', 'PayController@pay_order');

//同步
Route::get('return_url', 'PayController@aliReturn');
//异步
Route::post('notify_url', 'PayController@aliNotify');
//学生信息展示
Route::get('/student/index','StudentController@index');

//学生信息添加提交页面
Route::post('/student/do_add','StudentController@do_add');
//删除
Route::get('/student/delete','StudentController@delete');
//修改
Route::get('/student/update','StudentController@update');
//修改处理
Route::post('/student/do_update','StudentController@do_update');
//登录视图
Route::get('/student/login','StudentController@login');
//登录
Route::post('/student/do_login','StudentController@do_login');

//调用中间件


Route::group(['middleware' => ['login']], function () {
    //学生信息添加页面
	Route::get('/student/add','StudentController@add');
});

// //商品添加
// Route::get('/admin/goods_adds','admin\GoodsController@goods_adds');
// //商品添加执行
// Route::post('/admin/goods_doadd','admin\GoodsController@goods_doadd');
// //商品删除
// Route::get('/admin/goods_del','admin\GoodsController@goods_del');
// //商品列表展示
// Route::get('/admin/goods_lists','admin\GoodsController@goods_lists');
// //商品修改
// Route::get('/admin/goods_upd','admin\GoodsController@goods_upd');
// //商品修改处理
// Route::post('/admin/goods_doupdate','admin\GoodsController@goods_doupdate');
// //调用中间件


// Route::group(['middleware' => ['goods_upd']], function () {
//     //修改
// 	Route::get('/admin/goods_upd','admin\GoodsController@goods_upd');


// });
