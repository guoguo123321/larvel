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
use App\Entity\PdtImages;
Route::get('/', function () {
    return view('login');
//    return PdtImages::all();
});
Route::get('/master', function () {
    return view('master');
//    return PdtImages::all();
});
//登录页面
Route::get('/login','View\MemberController@tologin');
//注册页面
Route::get('/register', 'View\MemberController@toregister');
//首页select表单（一级分类显示）后->前
Route::get('/category', 'View\CategoryController@toCategory');
//product  书籍列表  前->后
Route::get('/category/product/category_id/parent_id/{category_id}', 'View\ProductController@product');
//pdt_content  书籍详情
Route::get('/pdtcontent/{category_id}', 'View\ProductController@toPdtContent');

Route::group(['prefix'=>'service'],function(){
    //验证码
    Route::get('validate_code/create', 'Service\ValidateController@test');
    //短信验证
    Route::get('validate_code/send', 'Service\ValidateController@sendSMS');
    //注册逻辑
    Route::post('register', 'Service\MemberController@register');
    //邮箱验证
    Route::get('validate_code/validateEmail', 'Service\ValidateController@validateEmail');
    //登录
    Route::post('login', 'Service\MemberController@login');
    //首页二级分类显示 前->后
    Route::get('category/parent_id/{parent_id}', 'Service\CategoryController@twoCategory');
    //CarConreoller  购物车
    Route::get('category/car/{car_id}', 'Service\CarController@toCar');
});
