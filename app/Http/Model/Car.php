<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $table = 'car';
    protected $primaryKey="id";
    public $timestamps = false;
    // protected $connection = 'mysql_shops';
}
