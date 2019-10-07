<?php

namespace App\Http\Controllers\api;

use DemeterChain\C;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Model\Curl;
class TestController extends Controller
{
    public function add()
    {
        $url = "http://wym.yingge.fun/api/test/addUser";
        $re = md5('1901' . '衣建新' . '31');
        $data = file_get_contents($url . '?' . 'name=衣建新&age=31&sign=' . $re);
        dd($data);

    }

    public function jiekou()
    {

        $url="http://api.avatardata.cn/ActNews/Query?key=89e95999b676443e95c11436728ddd00&keyword=NBA";
        $data=Curl::post($url);
        $data=json_decode($data,1);
        //dd($data);
        if(empty($data['result'])){
            return json_encode(['msg'=>'数据为空','code'=>201],JSON_UNESCAPED_UNICODE);
        }
        foreach ($data['result'] as $k=>$v){
            $newsdata=\DB::table('news')->insert([
                'title'=>$v['title'],
                'content'=>$v['content']
            ]);
        }
        //dd($newsdata);
    }
}
