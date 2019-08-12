<?php

namespace App\Http\admin\Model;

use Illuminate\Database\Eloquent\Model;

class Train extends Model
{
    protected $table = 'train';
    protected $primaryKey="id";
    public $timestamps = false;
}
