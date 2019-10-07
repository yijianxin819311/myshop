<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Goods_attr extends Model
{
   /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'goods_attr';
    protected $primarykey="ga_id";
    public $timestamps = false;
    protected $guarded=[];
}
