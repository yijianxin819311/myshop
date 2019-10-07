<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Wechat extends Model
{
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'wechat';
    protected $primarykey="id";
    public $timestamps = false;
    protected $guarded=[];
}
