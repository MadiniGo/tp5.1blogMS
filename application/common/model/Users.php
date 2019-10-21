<?php

namespace app\common\model;

use think\Model;

class Users extends Model
{
    /**
     * 检查用户是否登录
     * @param array $data
     * @return bool
     */
    public function checkUser( $data) {
        $ret = $this->where('username',$data['username'])->find();
        if (!$ret){
            return false;
        }
        // 判断密码是否正确
        $pwd = $ret['password'];
        $inppass = md5(md5($data['password'].'jk'));
        if ($pwd != $inppass){
            return false;
        }
        // 登录成功保存到session中
        session('admin.user',$ret);
        return true;
    }
}