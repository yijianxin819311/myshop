<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Types extends Model
{
   /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'Type';
    protected $primarykey="t_id";
    public $timestamps = false;
    protected $guarded=[];
}
