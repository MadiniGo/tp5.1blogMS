<?php
/**
 * Created by PhpStorm.
 * User: Mr Li
 * Date: 2019/10/9
 * Time: 15:46
 */
use think\facade\Route;
Route::group(['prefix'=>'admin/','name'=>'admin'],function (){

    // 登录
    Route::get('login','login/index')->name('admin/login/index');
    Route::post('login','login/loginHandler')->name('admin/login/index');
    //
    Route::group(['middleware'=>['CheckLogin']],function (){

        //资源路由
        Route::resource('article','article');
        //后台首页
        Route::get('index','index/index')->name('admin/index/index');
        //欢迎页
        Route::get('welcome' ,'index/welcome')->name('admin/index/welcome');
        //分类管理
        Route::resource('cate','cate');
        //搜索路由
        Route::post('article/search','article/search');
        Route::post('article/update_1','article/update_1');
        Route::post('cate/update_1','cate/update_1');
        //返回图片地址路由
        Route::post('article/picUrl','article/picUrl');
    });

    //文件上传
    Route::get('up','up/index')->name('admin/up/index');
    Route::post('upload','up/upload')->name('admin/up/upload');
    Route::delete('delete','up/del')->name('admin/up/del');

});