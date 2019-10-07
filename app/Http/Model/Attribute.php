<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
   /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'attribute';
    protected $primarykey="a_id";
    public $timestamps = false;
    protected $guarded=[];
}
