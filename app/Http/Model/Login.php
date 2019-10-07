<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
   /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'login';
    protected $primarykey="id";
    public $timestamps = false;
    protected $guarded=[];
}
