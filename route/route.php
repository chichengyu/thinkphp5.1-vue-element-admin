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

    /********** 账号管理 **********/
    Route::get('administrators/lst','api/:version.Administrators/lst');
    Route::post('administrators/add','api/:version.Administrators/add');
    Route::post('administrators/edit/:id','api/:version.Administrators/edit');
    Route::get('administrators/password/:id','api/:version.Administrators/resetPassword');
    Route::get('administrators/del/:id','api/:version.Administrators/del');

    /********** 个人资料 **********/
    Route::get('administrators/personal','api/:version.Administrators/getPersonal');
    Route::post('administrators/personal/edit','api/:version.Administrators/editPassword');

    /********** 用户组管理 **********/
    Route::get('groups/lst','api/:version.AuthGroup/lst');
    Route::post('groups/add','api/:version.AuthGroup/add');
    Route::post('groups/edit/:id','api/:version.AuthGroup/edit');
    Route::get('groups/del/:id','api/:version.AuthGroup/del');
    Route::post('groups/auth/:id','api/:version.AuthGroup/setRules');

    /********** 权限管理 **********/
    Route::get('rule/lst','api/:version.AuthRule/lst');
    Route::post('rule/add','api/:version.AuthRule/add');
    Route::post('rule/edit/:id','api/:version.AuthRule/edit');
    Route::get('rule/del/:id','api/:version.AuthRule/del');


    /********** 获取地址 **********/
    Route::get('address','api/:version.Address/getAddress');
});


Route::post('/upload/del','api/BaseController/delUpload');// 删除上传图片
Route::post('/upload/:image','api/BaseController/upload');// 图片上传








