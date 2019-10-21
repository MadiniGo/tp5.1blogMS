<?php

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use app\common\model\Cates;

class Cate extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {

        $data=$request->param();
        $where=[];
        //获取查询分类条件
        if(!empty($data['max_deep'])){

            $list = Db::name('cates')->alias('a')
                ->leftjoin('articles b','a.id=b.cates_id')
                ->field('a.*,count(b.id) as art_num')
                ->group('a.id')->select();
            $cates=model('cates')->getCatesList($list,$data['max_deep']);

        }

        if(!empty($data['id'])){
            $where[] = ['id','=',$data['id']];
            $cates=model('cates')->where($where)->select();
        }


       return json(['result'=>$cates,'status'=>1],200);
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
