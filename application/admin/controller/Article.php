<?php
/**
 * 文章管理
 */
namespace app\admin\controller;


use app\admin\validate\ArticleValidate;
use app\common\model\Cates;
use think\Controller;
use think\Db;
use think\Image;
use think\Request;
use app\common\model\Articles;


class Article extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        $where=array();
        //获取数据
        $data = $request->get();
        //对数据进行判断
        if(!empty($data['title'])){

            $where[]=['title','like','%'.$data['title'].'%'];
        }

        if(!empty($data['id'])){
            $where[]=['cates_id','=',$data['id']];
        }

        $articles = model('articles')->with('cates')->where($where)->paginate(10,false,['query'=>$request->param()]);
        $cates = Cates::all();

        return view('admin@article/index',compact('articles','cates'));
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {

        $cates =  db('cates')->select();
       return view('admin@article/create',compact('cates'));
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $input = $request->param();
        $input['image']=$request->file('image');

        //设置验证器
        $ret = $this->validate($input, ArticleValidate::class);
        if (true !== $ret) {
           return $ret;
        }
        $file = $request->file('image');
        $info = $file->move('./uploads/admin/images/');
        //移动到upload/admin/images目录
        if ($info) {
            $savename = '/uploads/admin/images/' . str_replace('\\', DS, $info->getSaveName());
            $image = Image::open(path_public() . $savename);
            //获取缩略图文件名
            $arrName = explode(DS,$info->getSaveName());
            $image->thumb(120,120)->save(path_public()."/uploads/admin/thumb/".$arrName[1]);
            //提取路径整合到$input中
            $input['art_icon_path']=$savename;
            $input['thumb']="/uploads/admin/thumb/".$arrName[1];
            //获取表单提交数据
            $username = session('admin.user')['id'];
            $input['users_id'] = $username;
            //写入数据库
            Articles::create($input);
            return redirect('/admin/article');
       }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {

    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit(int $id)
    {
        //查询对应id的数据
        $data = Articles::where('id',$id)->find();
        $cates = Cates::all();
        return view("admin@article/edit",compact('data','cates'));

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
        $info=null;
        //获取数据
        $input = $request->param();
        //判断是否有图标上传
        if($request->file()){
            //将file信息保存到input中
            $input['image']=$request->file('image');
            //获取原有的图片地址
            $pic = model('articles')->where('id','=',$input['id'])
                ->field('art_icon_path,thumb')->select();
            //删除原有图片
            if(file_exists(path_public().$pic[0]['art_icon_path'])){
                unlink(path_public().$pic[0]['art_icon_path']);
                unlink(path_public().$pic[0]['thumb']);
            }
            //上传图片移动图片到文件夹
            $info = $input['image']->move('./uploads/admin/images/');
        }
        if ($info) {
            //构建上传文件名
            $savename = str_replace('\\',DS,'/uploads/admin/images/') . str_replace('\\', DS, $info->getSaveName());
            $image = Image::open(path_public() . $savename);
            //获取缩略图文件名
            $arrName = explode(DS, $info->getSaveName());
            $image->thumb(120, 120)->save(path_public() . "/uploads/admin/thumb/" . $arrName[1]);
            //提取路径整合到$input中
            $input['art_icon_path'] = $savename;
            $input['thumb'] = "/uploads/admin/thumb/" . $arrName[1];
           //更新数据
           model('articles')->allowField(true)->save($input,['id'=>$input['id']]);
           return redirect(url('/admin/article'));
        }else{
            model('articles')->allowField(true)->save($input,['id'=>$input['id']]);
            return redirect(url('/admin/article'));
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
        $ret = Articles::destroy($id);
        if (!$ret){
            #return json(['status'=>0,'mes'=>'删除失败']);
        }
        return json(model('articles')->with('cates')->paginate(10)->toArray());
        #return json(['status'=>1,'mes'=>'删除成功']);
    }
    public function picUrl(Request $request)
    {
        //获取图片数据
        $file = $request->file('editormd-image-file');

        $info = $file->move('./uploads/admin/images/');
        if($info){
            $saveName = '/uploads/admin/images/'.str_replace('\\', '/', $info->getSaveName());
            return json(['success'=>1,'message'=>'上传成功','url'=>$saveName]);
        }else{
            return json(['success'=>0,'message'=>'上传失败','url'=>'']);
        }
    }
}