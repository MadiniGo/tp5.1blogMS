<?php

namespace app\admin\controller;

use think\Controller;
use think\Image;
use think\Request;

class Up extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {

        return view('admin@up/index');
    }

    public function upload(Request $request)
    {


       $file =  $request->file('pic');
       $info =  $file->move( './uploads');
       //移动到upload目录
       if($info){

           $savename = '/uploads/'.str_replace('\\','/',$info->getSaveName());
           $image = Image::open(path_public().$savename);
           $image->thumb(150,150)->save(path_public().$savename);
           return json(['status'=>1,'msg'=>$savename]);
       }else{
           return json(['status'=>0,'msg'=>$file->getError()]);

       }

    }
    //删除文件
    public function del(Request $request)
    {
        $img = path_public().$request->delete('img');
        $data = ['status'=>0,'msg'=>'删除失败'];
        if (unlink($img)){
            $data = ['status'=>1,'msg'=>'删除成功'];
        }
        return $data;
    }


    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
