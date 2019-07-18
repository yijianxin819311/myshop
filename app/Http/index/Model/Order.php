<?php

namespace App\HTTP\index\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
   protected $table = 'order';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
