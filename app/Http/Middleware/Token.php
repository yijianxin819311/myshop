<?php

namespace App\Http\Middleware;
use App\Http\Model\User;
use Closure;

class Token
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token=isset($_SERVER['HTTP_TOKEN']) ? $_SERVER['HTTP_TOKEN'] : "";
        //$token=$request->input('token');
        //dd($token);
        if(empty($token)){
            echo json_encode(['msg'=>'请先登录','code'=>201],JSON_UNESCAPED_UNICODE);die;
        }
        $userdata=User::where(['token'=>$token])->first();
        //dd($userdata);
        if(!$userdata){
            echo json_encode(['msg'=>'参数有误','code'=>201],JSON_UNESCAPED_UNICODE);die;
        }
        //判断是否过期时间
        if(time()>$userdata['reg_time']){
            echo json_encode(['msg'=>'已过期','code'=>201],JSON_UNESCAPED_UNICODE);die;
        }
        //根据业务需求是否更新token过期时间
        $user=User::where(['user_id'=>$userdata['user_id']])->update([
            'reg_time'=>time()+7200
        ]);
        $mid_params = ['user_id'=>'this is mid_params'];
        $request->attributes->add(['user_id'=>$userdata['user_id']]);//添加参数
        return $next($request);
    }
    //直接购买接口
    public function goods_buy()
    {

    }


}
