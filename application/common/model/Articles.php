<?php

namespace app\common\model;

use think\Model;
use think\Request;

class Articles extends Model
{
    public function cates()
    {
        return $this->belongsTo(Cates::class,'cates_id')->field('cates_id','cate_name');

    }


}
