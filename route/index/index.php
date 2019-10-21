<?php
use think\facade\Route;
//首页路由

Route::group(['prefix'=>'index/','name'=>'index'],function (){
    Route::resource('index','index');
    Route::resource('cate','cate');
    Route::get('search','index/search');
});