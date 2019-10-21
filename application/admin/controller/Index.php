<?php
/**
 * 后台首页
 */
namespace app\admin\controller;


use think\Controller;
use think\Request;

class Index extends Controller
{
    public function index()
    {

        return view('admin@index/index');
    }
    public function welcome(){
        return view('admin@index/welcome');
    }
}
