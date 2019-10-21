<?php

namespace app\admin\validate;

use think\Validate;

class CateValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'cate_name'=>'require|token',
        'image'=>['require', 'fileExt' => 'jpg,gif,png', 'fileSize' => 650000]
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'cate_name.require'=>'分类名不能为空',
        'image.fileExt' => '图片的格式不符合要求',
        'image.fileSize' => '内容不能大于6500字节',
    ];
}
