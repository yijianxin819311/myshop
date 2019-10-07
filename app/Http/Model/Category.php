<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
   /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'category';
    protected $primarykey="cate_id";
    public $timestamps = false;
    protected $guarded=[];
}
