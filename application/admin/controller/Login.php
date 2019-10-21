<?php

namespace app\admin\controller;

use app\admin\validate\UserValidate;
use think\Controller;
use think\Request;

class Login extends Controller
{

    public function index()
    {
        return view('admin@login/index');
    }

    public function loginHandler(Request $request) {
        $input = $request->post();

        // 验证
        $ret = $this->validate($input,UserValidate::class);

        // 判断验证是否通过
        if (true !== $ret){
            return $this->error('请重新登录');
        }

        // 查询数据库
        $ret = model('users')->checkUser($input);

        if ($ret){
            return $this->success('登陆成功','admin/index/index');
        }else{
            return $this->error('用户名或密码不正确');
        }

    }
}
