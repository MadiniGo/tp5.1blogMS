<?php

namespace app\index\controller;

use app\admin\controller\Article;
use app\common\model\Articles;
use think\Controller;
use think\Db;
use think\Request;

class Index extends Controller
{
    /**
     * 显示资源列表*
     *
     * @return \think\Response
     * 文章列表请求方式GET
     * 请求参数：page:当前页数; cateId:表示分类id; offset:表示每次返回的条数
     * 服务器地址+'/index/index'
     * 返回数据id:文章id；
     * title：文章题目，
     * update_time:更新时间；
     * body：文章主体；
     * desn：文章描述
     * status=1表示还有下一页，0表示没有下一页或者没数据
     */
    public function index(Request $request)
    {
        $page=1;
        $where=[];
        $data = $request->param();
        $page=$data['page'];
        //判断是否时分类列表查询
        if(!empty($data['cateId'])){
            $where[]=['cates_id','=',$data['cateId']];
            //获取当前列表的总页数
            $count = Db::name('articles')->where($where)->count();
        }else{
            //获取总记录数
            $count = Db::name('articles')->count();
        }
        isset($data['offset'])? $offset=$data['offset'] : $offset=3;
        $pages = ceil($count/$offset);
        $startRow=($page-1)*$offset;
        $articles = model('articles')->with('cates')->limit($startRow,$offset)->where($where)->order("update_time",'desc')->select();
        if ($page>$pages){
            return json(["status"=>0,'result'=>$articles]);
        }else if ($pages ==1) {
            return json(["status"=>0,'result'=>$articles]);
        }else{
        //显示首页数据
        return json(['result'=>$articles,'status'=>1,'pages'=>$pages],200);
        }
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
    public function read(int $id)
    {
        //获取指定id的文章
        if (!empty($id)){
        $data =  Articles::get($id);
        return json(['result'=>$data,'status'=>1]);
        }
        return json(['result'=>'','status'=>0]);
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
    public function update(Request $request, int $id)
    {
        //获取数据
        $input = $request->put();
        //更新数据
        $res = model('articles')->allowField(true)->save($input,['id'=>$id]);

        return json($res);


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

    public function search(Request $request)
    {
        //默认sql条件
        $where = [];
        //获取传递的参数
        $input = $request->param();
        //获取当前页码
        $page=$input['page'];
        //判断是否有keyword传入
        if(!empty($input['keyword'])){
            $where[] = ['title','like','%'.$input['keyword'].'%'];
        }
        //获取总记录数
        $count = Db::name('articles')->where($where)->count();
        //判断每页行数是否有传递
        isset($input['offset'])? $offset=$input['offset'] : $offset=3;
        //总页数计算
        $pages = ceil($count/$offset);
        //每页起始行号
        $startRow=($page-1)*$offset;
        //获取数据
        $result = Articles::limit($startRow,$offset)
            ->where($where)
            ->order('update_time','desc')
            ->select();
        //当前页大于总页数时，返回status=0,表示没有数据了
        if($page>$pages){
            return json(['result'=>$result,'status'=>0]);
        }else if($pages==1){
            //当总页数为一时，返回status=0，表示没有数据了
            return json(['result'=>$result,'status'=>0]);
        }else{
            return json(['result'=>$result,'status'=>1]);
        }
    }
}
