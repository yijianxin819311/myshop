<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
   /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'orders';
    protected $primarykey="order_id";
    public $timestamps = false;
    protected $guarded=[];

}
