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
Route::get('/admin','admin\IndexController@index');
Route::get('/index','index\IndexController@index');
Route::get('/index/login','index\IndexController@login');
Route::post('/index/login_do','index\IndexController@login_do');
//项目注册
Route::get('/index/register','index\IndexController@register');
//项目注册执行
Route::post('/index/register_do','index\IndexController@register_do');
//商品上传
Route::get('/admin/goods_add','admin\GoodsController@goods_add');
//商品上传
Route::post('/admin/goods_add_do','admin\GoodsController@goods_add_do');
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