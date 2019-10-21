<?php

namespace app\admin\controller;

use app\admin\validate\CateValidate;
use app\common\model\Cates;
use think\Controller;
use think\Db;
use think\Model;
use think\Request;
use think\Image;

class Cate extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //显示分类列表
        $list = Db::name('cates')->alias('a')
            ->leftjoin('articles b','a.id=b.cates_id')
            ->field('a.*,count(b.id) as art_num')
            ->group('a.id')->select();
        $data= model('cates')->getCatesList($list,$max_deep=4);
        return view('admin@cate/index',compact('data'));
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $list=Cates::all();
        $cates = model('cates')->getCatesList($list,4);
        return view("admin@cate/create",compact('cates'));
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {

        //获取接收的数据
        $input = $request->post();
        $input['image']=$request->file('image');
        //设置验证器
        $ret = $this->validate($input,CateValidate::class);
        if(true !== $ret){
            return $this->error($ret);
        }
        $file = $input['image'];
        $info = $file->move('./uploads/admin/images/');
        if($info){
            $icon_path = '/uploads/admin/images/'.str_replace('\\','/',$info->getSaveName());
            $input['icon_path']=$icon_path;
        }
        //保存数据到服务器中
        Cates::create($input);
        return redirect(url('/admin/cate'));
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
    public function edit(int $id)
    {
        //获取分类数据
        $list=Cates::all();
        $cates = model('cates')->getCatesList($list,4);
        //查询指定id的数据
        $data = Cates::where('id',$id)->find();

        return view('/admin@cate/edit',compact('data','cates'));
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update_1(Request $request)
    {
        //获取数据
        $input = $request->param();
        $input['image'] = $request->file('image');
        //设置验证器
        $ret = $this->validate($input, CateValidate::class);
        if (true !== $ret) {
            return $ret;
        }
        //获取原有的图片地址
        $pic = model('cates')->where('id', '=', $input['id'])
            ->field('icon_path')->select();
        //删除原有图片
        if (file_exists(path_public() . $pic[0]['icon_path'])) {
            unlink(path_public() . $pic[0]['icon_path']);

        }

        //移动图片到文件夹
        $info = $input['image']->move('./uploads/admin/images/');
        if ($info) {
            $savename = '/uploads/admin/images/' . str_replace('\\', '/', $info->getSaveName());
            $image = Image::open(path_public() . $savename);

            //提取路径整合到$input中
            $input['icon_path'] = $savename;

            //更新数据
            model('cates')->allowField(true)->save($input,['id'=>$input['id']]);
            return redirect(url('/admin/cate'));
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete(int $id)
    {

       Cates::destroy($id);
      return model('cates')->getCatesList();
    }
}
