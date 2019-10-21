<?php

namespace app\common\model;

use think\Model;

class Cates extends Model
{
    public function articles(){
      return Cates::hasMany(Articles::class,'cates_id');
    }
    //获取分类数据
    public function getCatesList($list,$max_deep)
    {

        return $this->tree($list,0,0,$max_deep);
    }
    //获取分类树方法
    private function tree(&$list,$pid = 0,$deep=0,$max_deep=4)
    {
         static $result = array();
        foreach ($list as $row){
            if($row['pid']==$pid){
                //判断是否达到最大深度
                if($deep<$max_deep){
                    $row['deep']=$deep;
                    $result[]=$row;
                    $this->tree($list,$row['id'],$deep+1,$max_deep);
                }
            }
        }
        return $result;
    }
}
