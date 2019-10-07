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
//油价
Route::get('/price/price','PriceController@price');//
//表白
Route::get('/biaobai/code','Biaobai@code');//授权码
Route::get('/biaobai/login','Biaobai@login');//授权登录
Route::get('/biaobai/add','Biaobai@add');
Route::get('/biaobai/list','Biaobai@list');//通过openid获得我要表白的用户页面
Route::get('/biaobai/lists','Biaobai@lists');//表白列表
Route::get('/biaobai/biaobai_add','Biaobai@biaobai_add');
Route::post('/biaobai/biaobai_add_do','Biaobai@biaobai_add_do');
Route::post('/biaobai/send','Biaobai@send');
Route::get('/biaobai/sends','Biaobai@sends');

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

//课程管理
Route::get('/kecheng/add','Kecheng@add');
Route::get('/kecheng/adds','Kecheng@adds');
Route::get('/kecheng/addss','Kecheng@addss');
Route::post('/kecheng/add_do','Kecheng@add_do');
Route::get('/kecheng/code','Kecheng@code');//授权码
Route::get('/kecheng/login','Kecheng@login');//
Route::get('/kecheng/login','Kecheng@login');//
Route::post('/kecheng/event','Kecheng@event');
Route::get('/kecheng/kecheng_add','Kecheng@kecheng_add');
Route::post('/kecheng/kecheng_add_do','Kecheng@kecheng_add_do');
Route::get('/kecheng/list','Kecheng@list');
Route::get('/kecheng/update','Kecheng@update');
Route::post('/kecheng/update_do','Kecheng@update_do');
Route::get('/kecheng/moban','Kecheng@moban');
Route::get('/kecheng/class_caidan', 'Kecheng@class_caidan');


//接口
Route::get('/member/add', function () {
    return view('api.add');
});
Route::get('/member/show', function () {
    return view('api.show');
});
Route::get('/member/shows', function () {
    return view('api.show1');
});
Route::get('/member/save', function () {
    return view('api.save');
});
Route::get('/goods/adds', function () {
    return view('api.adds');
});
Route::get('/goods/list', function () {
    return view('api.list');
});
Route::get('/goods/update', function () {
    return view('api.update');
});
Route::get('/goods/list', function () {
    return view('api.list');
});
Route::get('/jiekou/jiekou','Jiekou@jiekou');
Route::get('/weixin/do_get','Weixin@do_get');
Route::get('/weixin/test','Weixin@test');
Route::get('/weixin/test1','Weixin@test1');
Route::get('/api/member/show','api\MemberController@show');//展示
Route::get('/api/member/index','api\MemberController@index');//展示
Route::get('/api/test/add','api\TestController@add');//加密
Route::get('/api/test/jiekou','api\TestController@jiekou');//加密接口
Route::get('/api/goods/aes','api\GoodsController@aes');//aes加密解密
Route::get('/api/goods/rsa','api\GoodsController@rsa');//rsa加密解密
Route::get('/api/goods/zuoye','api\GoodsController@zuoye');//rsa加密解密
Route::get('/api/goods/jiekou_add','api\GoodsController@jiekou_add');//写接口加密
Route::get('/api/goods/jiekouadd','api\GoodsController@jiekouadd');//写接口加密
Route::get('/api/goods/jiekou_do','api\GoodsController@jiekou_do');//写接口加密
Route::get('/api/member/shows','api\Member1Controller@shows');//展示
//Route::any('/api/member/add','api\MemberController@add');//添加
//Route::any('/api/goods/store','api\CategoryController@store');//添加
Route::any('/api/goods/index','api\GoodsController@index');//商品展示
Route::any('/api/goods/destroy','api\GoodsController@destroy');//商品删除
//Route::any('/api/member/find','api\MemberController@find');//修改默认值
//Route::any('/api/member/save','api\MemberController@save');//修改执行
//Route::any('/api/member/delete','api\MemberController@delete');//删除
//Route::any('/api/member/loginPages','api\MemberController@loginPages');//删除
Route::any('/api/member/loginPages','api\Member1Controller@loginPages');//删除
//result路由
Route::resource('api/goods', 'api\GoodsController');
Route::resource('api/member', 'api\MemberController');


//调用中间件
// Route::group(['middleware' => ['liuyan_login']], function () {
//     //留言
// 	Route::get('/liuyan/liuyan','Liuyan@liuyan');

// });
Route::post('/liuyan/do_liuyan','Liuyan@do_liuyan');
Route::get('/liuyan/list','Liuyan@list');
Route::get('/liuyan/delete','Liuyan@delete');

//商城
Route::get('/admins/index', function () {
    return view('admins.index');
});
//Route::get('/admins/cate', function () {
//    return view('admins.category');
//});
//Route::any('/admins/goods/list','admins\CategoryController@list');//修改默认值
Route::get('/admins/cate','admins\CategoryController@cate');
Route::post('/admins/add_do','admins\CategoryController@add_do');
Route::any('/admins/add_dodo','admins\CategoryController@add_dodo');
Route::get('/admins/lists','admins\CategoryController@lists');
Route::get('/admins/goods_type','admins\CategoryController@goods_type');
Route::post('/admins/type_do','admins\CategoryController@type_do');
Route::get('/admins/type_list','admins\CategoryController@type_list');
Route::get('/admins/goods_attr','admins\CategoryController@goods_attr');
Route::post('/admins/attr_do','admins\CategoryController@attr_do');
Route::get('/admins/attr_list','admins\CategoryController@attr_list');
Route::get('/admins/attr_lists','admins\CategoryController@attr_lists');
Route::get('/admins/del','admins\CategoryController@del');//批删
//Route::get('/admins/goods','admins\CategoryController@goods');
Route::get('/admins/attr_add','admins\GoodsController@attr_add');
Route::any('/admins/goods_add_do','admins\GoodsController@goods_add_do');
Route::any('/admins/goods_sku/{goods_id}','admins\GoodsController@goods_sku');
Route::any('/admins/goods_sku_do','admins\GoodsController@goods_sku_do');
Route::any('/admins/goods_list','admins\GoodsController@goods_list');
Route::any('/admins/change','admins\GoodsController@change');//即点即改
Route::any('/admins/login','admins\GoodsController@login');//登录
Route::any('/admins/login_do','admins\GoodsController@login_do');//登录执行
Route::any('/admins/register','admins\GoodsController@register');//注册
//调用中间件
 Route::group(['middleware' => ['login']], function () {
    Route::get('/admins/goods','admins\CategoryController@goods');

});
//调用中间件
Route::group(['middleware' => ['apiheader']], function () {
    Route::any('/api/index/cate','api\IndexController@cate');//前台分类接口
    Route::any('/api/index/cates','api\IndexController@cates');//前台分类接口
    Route::any('/api/index/newgoods','api\IndexController@newgoods');//前台新商品接口
    Route::any('/api/index/allgoods','api\IndexController@allgoods');//前台新商品接口
    Route::any('/api/index/goodsdetail','api\IndexController@goodsdetail');//前台新商品接口
    Route::any('/api/index/login','api\IndexController@login');//token登录
    Route::any('/api/index/addresslist','api\IndexController@addresslist');//收货地址展示
    Route::group(['middleware' => ['token']], function () {
        Route::any('/api/index/addcart','api\IndexController@addcart');//添加购物车
        Route::any('/api/index/info','api\IndexController@info');//用户信息
        Route::any('/api/index/cartlist','api\IndexController@cartlist');//购物车展示
        Route::any('/api/index/goods_buy','api\IndexController@goods_buy');//添加收货地址
    });
});
//购物车展示测试接口
//Route::any('/api/index/goods_buy','api\IndexController@goods_buy');
//用户信息
//Route::any('/api/index/cartlist','api\IndexController@cartlist');
//天气接口
Route::any('/api/weather/login','api\WeatherController@login');//登录接口
Route::any('/api/weather/login_out','api\WeatherController@login_out');//退出接口
Route::any('/api/weather/register','api\WeatherController@register');//登录接口
Route::any('/api/weather/weather','api\WeatherController@weather');//查询天气接口
Route::any('/api/weather/weather_do','api\WeatherController@weather_do');//查询天气接口
Route::any('/api/weather/web','api\WeatherController@web');//登录接口
//app接口
Route::any('/api/app/regster','api\AppController@regster');//注册接口
Route::any('/api/app/login_do','api\AppController@login_do');//注册接口
Route::any('/api/app/orderlist','api\AppController@orderlist');//订单展示接口
Route::any('/api/app/app','api\AppController@app');//app测试
Route::any('/api/app/apps','api\AppController@apps');//添加参数接口
