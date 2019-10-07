<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Order_address extends Model
{
   /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'order_address';
    protected $primarykey="oa_id";
    public $timestamps = false;
    protected $guarded=[];

}
