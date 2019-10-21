<?php

namespace app\admin\validate;

use think\Validate;

class ArticleValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'title'=>'require|token',
        'desn'=>'require',
        'body'=>'require',
        'image'=>['require', 'fileExt' => 'jpg,gif,png', 'fileSize' => 650000]
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'title.require'=>'标题不能为空',
        'desn.require'=>'描述不能为空',
        'body.require'=>'文字内容不能为空',
        'image.fileExt' => '图片的格式不符合要求',
        'image.fileSize' => '内容不能大于6500字节',
    ];
}
