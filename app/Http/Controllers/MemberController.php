<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Model\Member;
class MemberController extends Controller
{
    public  static function show()
    {
        $res=Member::get()->toArray();
        //dd($res);
        return json_encode($res);
    }

}
