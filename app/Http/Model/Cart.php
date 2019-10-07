<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
   /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'cart';
    protected $primarykey="cart_id";
    public $timestamps = false;
    protected $guarded=[];

}
