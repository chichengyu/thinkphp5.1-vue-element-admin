<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------


// 后台首页
Route::get('/', function(){
	return view('admin/index');
});
Route::group('api/:version',function(){
    /********** 登陆 / 注销 **********/
    Route::post('login','api/:version.Login/login');
    Route::get('logout','api/:version.Login/logout');

    /********** 车辆管理 **********/
    Route::get('car/lst','api/:version.CarDetails/lst');

    /********** 购物车管理 **********/
    Route::get('shopcar/lst','api/:version.StoreCart/lst');
    Route::post('shopcar/add','api/:version.StoreCart/add');
    Route::get('shopcar/del','api/:version.StoreCart/del');

    /********** 订单管理 **********/
    Route::post('order','api/:version.Order/placeOrder');
    Route::get('order/lst','api/:version.Order/lst');
    Route::get('order/del/:id','api/:version.Order/del');


    /********** 员工管理 **********/
    Route::get('personnel/lst','api/:version.StorePersonnel/lst');
    Route::post('personnel/add','api/:version.StorePersonnel/add');
    Route::post('personnel/edit/:id','api/:version.StorePersonnel/edit');
    Route::get('personnel/del/:id','api/:version.StorePersonnel/del');

    /********** 咨询管理 **********/
    Route::get('user_reserve/lst','api/:version.UserReserve/lst');
    Route::post('user_reserve/add','api/:version.UserReserve/add');
    Route::post('user_reserve/edit/:id','api/:version.UserReserve/edit');
    Route::post('user_reserve/relay/:id','api/:version.UserReserve/relay');
    Route::get('user_reserve/del/:id','api/:version.UserReserve/del');
    Route::get('user_reserve/car','api/:version.UserReserve/getByBrandOrTypeCar');

    /********** 咨讯管理 **********/
    Route::get('message/lst','api/:version.Message/lst');
    Route::post('message/add','api/:version.Message/add');
    Route::post('message/edit/:id','api/:version.Message/edit');
    Route::get('message/del/:id','api/:version.Message/del');

    /********** 车主管理 **********/
    Route::post('owner/add','api/:version.StoreOwner/add');
    Route::post('owner/upload','api/:version.StoreOwner/updaloadIdCard');
    Route::post('owner/edit/:id','api/:version.StoreOwner/edit');


    /********** 支付 **********/
    Route::get('pay/:id','api/:version.Pay/getPreOrder');
    // 微信支付回调
    Route::post('notify','api/:version.Pay/receiveNotifyWechat');
    // 轮询
    Route::post('query','api/:version.Pay/queryOrderJump');
    // 支付宝支付回调
    Route::post('notify_ali','api/:version.Pay/receiveNotifyAli');

    // 银联
    Route::post('unionPay','api/:version.AcpPay/unionPay');
    // 提车通知
    Route::post('informSms','api/:version.SmsOwner/informSms');
    // 银联后台回调
    Route::post('UnionBackReceive','api/:version.Pay/receiveNotifyAcp');
    // 银联前台回调
    Route::post('UnionFrontReceive','api/:version.Pay/receiveNotifyAcpTest');


    /********** 用户组管理 **********/
    Route::get('groups/lst','api/:version.StoreAuthGroup/lst');
    Route::post('groups/add','api/:version.StoreAuthGroup/add');
    Route::post('groups/edit/:id','api/:version.StoreAuthGroup/edit');
    Route::get('groups/del/:id','api/:version.StoreAuthGroup/del');
    Route::post('groups/auth/:id','api/:version.StoreAuthGroup/setRules');

    /********** 权限管理 **********/
    Route::get('rule/lst','api/:version.StoreAuthRule/lst');
    Route::post('rule/add','api/:version.StoreAuthRule/add');
    Route::post('rule/edit/:id','api/:version.StoreAuthRule/edit');
    Route::get('rule/del/:id','api/:version.StoreAuthRule/del');


    /********** 获取地址 **********/
    Route::get('address','api/:version.Address/getAddress');

    /********** 个人中心 **********/
    Route::get('my/user','api/:version.StorePersonnel/getUserinfo');
    Route::post('my/edit','api/:version.StorePersonnel/myEdit');

});


Route::post('/upload/del','api/BaseController/delUpload');// 删除上传图片
Route::post('/upload/:image','api/BaseController/upload');// 资讯上传








