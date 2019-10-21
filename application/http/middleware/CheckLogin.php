<?php

namespace app\http\middleware;

class CheckLogin
{
    public function handle($request, \Closure $next)
    {
        //判断用户是否登录
        if(!session('?admin.user')){
            return redirect(url('admin/login/index'))->with('error','非法登录请登录');
        }
        return $next($request);
    }
}
