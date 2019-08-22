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
//后台登录
Route::get('/admin/logins','admin\ExamController@logins');
//后台登录处理
Route::post('/admin/logins_do','admin\ExamController@logins_do');
Route::get('/admin/adds','admin\ExamController@adds');

// Route::get('/admin/sadd','admin\ExamController@sadd');
Route::post('/admin/addsss_do','admin\ExamController@sadd_do');
//调研系统
Route::get('/admin/sadd','admin\ExamController@sadd');
Route::post('/admin/do_sadd','admin\ExamController@do_sadd');
Route::get('/admin/index','admin\ExamController@index');
Route::get('/admin/add_papers','admin\ExamController@add_papers');
Route::post('admin/do_add_papers','admin\ExamController@do_add_papers');
Route::post('admin/insert_papers','admin\ExamController@insert_papers');
Route::get('admin/test_list','admin\ExamController@test_list');
Route::get('admin/test_detail','admin\ExamController@test_detail');

//竞猜
Route::get('/cai/add','Cai@add');
Route::post('/cai/add_do','Cai@add_do');
Route::get('/cai/index','Cai@index');
Route::post('/cai/do_cai','Cai@do_cai');
Route::get('/cai/list','Cai@list');
Route::get('/cai/jieguo','Cai@jieguo');
Route::post('/cai/jieguo_do','Cai@jieguo_do');
Route::get('/cai/list_do','Cai@list_do');
//车库管理系统
Route::get('/cheku/login','Cheku@login');
Route::post('/cheku/login_do','Cheku@login_do');
Route::get('/cheku/add','Cheku@add');
Route::get('/cheku/adds','Cheku@adds');
Route::post('/cheku/adds_do','Cheku@adds_do');
Route::get('/cheku/index','Cheku@index');
Route::get('/cheku/out','Cheku@out');
Route::post('/cheku/out_do','Cheku@out_do');
Route::post('/cheku/chekuPrice','Cheku@chekuPrice');
Route::get('/cheku/del_price','Cheku@del_price');
//地图
Route::get('/map/index','Map@index');
Route::post('/map/add','Map@add');
Route::get('/map/list','Map@list');
//注册
Route::get('/zhuce/registers','Zhuce@registers');
Route::post('/zhuce/sendEmail','Zhuce@sendEmail');

//后台车票提交
Route::get('/admin/add','admin\Train@add');
//后台车票处理
Route::post('/admin/add_do','admin\Train@add_do');
Route::get('/admin/list','admin\Train@list');
//
Route::any('/wechat/event','WechatController@event'); //接收公众号事件
//用户标签相关
Route::get('/wechat/update_tag','WechatController@update_tag'); //修改标签
Route::post('/wechat/do_update_tag','WechatController@do_update_tag'); //执行修改标签
Route::get('/wechat/tag_list','WechatController@tag_list'); //标签列表
Route::get('/wechat/add_tag','WechatController@add_tag'); //添加标签
Route::get('/wechat/do_add_tag','WechatController@do_add_tag'); //执行添加标签
Route::post('/wechat/add_user_tag','WechatController@add_user_tag'); //为用户打标签
Route::get('/wechat/del_tag','WechatController@del_tag'); //删除标签
Route::get('/wechat/tag_user','WechatController@tag_user'); //标签下用户列表
Route::get('/wechat/get_user_tag','WechatController@get_user_tag'); //获取用户标签
Route::get('/wechat/del_user_tag','WechatController@del_user_tag'); //删除用户标签
Route::get('/wechat/push_tag_message','WechatController@push_tag_message'); //根据标签推送消息
Route::post('/wechat/do_push_tag_message','WechatController@do_push_tag_message'); //执行根据标签推送消息
Route::get('/wechat/code','WechatController@code');
Route::get('/wechat/login','WechatController@login');
Route::get('/wechat/template_list','WechatController@template_list');
Route::get('/wechat/del_template','WechatController@del_template');
Route::get('/wechat/push_template','WechatController@push_template');
//上传素材
Route::get('/wechat/upload_source','WechatController@upload_source');
Route::get('/wechat/get_source','WechatController@get_source');
Route::get('/wechat/get_video_source','WechatController@get_video_source');
Route::get('/wechat/get_voice_source','WechatController@get_voice_source');
Route::post('wechat/do_upload','WechatController@do_upload');
//第一周作业
Route::get('/wechat/get_user_info','WechatController@get_user_info');
Route::get('/wechat/get_user_list','WechatController@get_user_list');
Route::get('/wechat/user_list','WechatController@user_list');
//接口
Route::get('/weixin/get_user_info','Weixin@get_user_info');
Route::get('/weixin/wechat_user_info','Weixin@wechat_user_info');
Route::get('/weixin/get_user_infos','Weixin@get_user_infos');
Route::get('/weixin/user_info','Weixin@user_info');
Route::get('/weixin/get_user_list','Weixin@get_user_list');
Route::get('/weixin/user_list','Weixin@user_list');
Route::get('/weixin/lists','Weixin@lists');
Route::get('/weixin/code','Weixin@code');
Route::get('/weixin/login','Weixin@login');
Route::get('/weixin/template_list','Weixin@template_list');
Route::get('/weixin/del_template','Weixin@del_template');
Route::get('/weixin/push_template','Weixin@push_template');
Route::post('/weixin/event','Weixin@event');
//上传素材
Route::get('/weixin/sucai','Weixin@sucai');
Route::post('/weixin/do_upload','Weixin@do_upload');
Route::get('/weixin/upload_source','Weixin@upload_source');
Route::get('/weixin/get_source','Weixin@get_source');
Route::get('/weixin/get_video_source','Weixin@get_video_source');
Route::get('/weixin/get_voice_source','Weixin@get_voice_source');
Route::get('/weixin/sucai_list','Weixin@sucai_list');

//用户标签相关
Route::get('/weixin/taglist','Weixin@taglist'); //标签列表
Route::get('/weixin/add_tag','Weixin@add_tag'); //添加标签
Route::get('/weixin/do_add_tag','Weixin@do_add_tag'); //执行添加标签
Route::get('/weixin/add_user_tag','Weixin@add_user_tag'); //为用户打标签
Route::get('/weixin/del_tag','Weixin@del_tag'); //删除标签
Route::get('/weixin/tag_user','Weixin@tag_user'); //标签下用户列表
Route::get('/weixin/get_user_tag','Weixin@get_user_tag'); //获取用户标签
Route::get('/weixin/del_user_tag','Weixin@del_user_tag'); //删除用户标签
Route::get('/weixin/fensilist','Weixin@fensilist');//标签下用户列表展示
Route::get('/weixin/tag_do','Weixin@tag_do');//为标签下用户添加标签
Route::get('/weixin/tuisong','Weixin@tuisong');//推送消息
Route::post('/weixin/tuisong_do','Weixin@tuisong_do');//推送消息执行

//生成二维码
Route::get('/agent/userlist','Agent@userlist');//
Route::get('/agent/creat_qrcode','Agent@creat_qrcode');//
Route::get('/agent/list','Agent@list');//

//菜单
Route::get('/caidan/add','Caidan@add');//
Route::post('/caidan/add_do','Caidan@add_do');
Route::get('/caidan/del','Caidan@del');//
//表白
Route::get('/biaobai/code','Biaobai@code');//授权码
Route::get('/biaobai/login','Biaobai@login');//授权登录
Route::get('/biaobai/add','Biaobai@add');
Route::get('/biaobai/list','Biaobai@list');//通过openid获得我要表白的用户页面
Route::get('/biaobai/lists','Biaobai@lists');//表白列表
Route::get('/biaobai/biaobai_add','Biaobai@biaobai_add');
Route::post('/biaobai/biaobai_add_do','Biaobai@biaobai_add_do');
Route::post('/biaobai/send','Biaobai@send');

Route::get('/liuyand/login','LiuyanController@login');//授权码

//xin留言
Route::get('/liuyans/logins','Liuyans@logins');//授权码
Route::get('/liuyans/code','Liuyans@code');//授权码
Route::get('/liuyans/login','Liuyans@login');//
Route::get('/liuyans/add','Liuyans@add');//
Route::post('/liuyans/add_do','Liuyans@add_do');//
Route::get('/liuyans/liuyans_list','Liuyans@liuyans_list');//
Route::get('/liuyans/get_user_list','Liuyans@get_user_list');//
Route::get('/liuyans/lists','Liuyans@lists');//

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

//留言管理

//登录
Route::get('/liuyan/liuyan_login','Liuyan@liuyan_login');
Route::post('/liuyan/do_login','Liuyan@do_login');
Route::get('/liuyan/liuyan','Liuyan@liuyan');
Route::post('liuyan_info','Liuyan@info');



//调用中间件
// Route::group(['middleware' => ['liuyan_login']], function () {
//     //留言
// 	Route::get('/liuyan/liuyan','Liuyan@liuyan');

// });
Route::post('/liuyan/do_liuyan','Liuyan@do_liuyan');
Route::get('/liuyan/list','Liuyan@list');
Route::get('/liuyan/delete','Liuyan@delete');