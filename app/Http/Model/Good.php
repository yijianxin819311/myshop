<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
   /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'good';
    protected $primarykey="goods_id";
    public $timestamps = false;
    protected $guarded=[];

}
