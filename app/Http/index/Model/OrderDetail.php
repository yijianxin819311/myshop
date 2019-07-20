<?php

namespace App\http\Index\Model;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{ 
    protected $table = 'order_detail';
    protected $primaryKey = 'id';
    public $timestamps = false;
  

}
