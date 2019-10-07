<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
   /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'user';
    protected $primarykey="user_id";
    public $timestamps = false;
    protected $guarded=[];
}
