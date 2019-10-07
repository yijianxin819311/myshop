<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Sku extends Model
{
   /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'sku';
    protected $primarykey="s_id";
    public $timestamps = false;
    protected $guarded=[];
}
