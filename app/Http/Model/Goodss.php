<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Goodss extends Model
{
   /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'goodss';
    protected $primarykey="g_id";
    public $timestamps = false;
    protected $guarded=[];
}
