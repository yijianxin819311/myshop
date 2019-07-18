<?php

namespace App\Http\Middleware;

use Closure;

class Goods_upd
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
        //业务逻辑 9点到17点才可以进行修改
        $start=strtotime('9:00:00');
        $end=strtotime('17:00:00');
        $now=time();
        if($now >=$start &&$now <=$end){
            //可以通过
            
        }else{
            //不可以通过
            dd('当前时间不可访问');
        }
        return $next($request);
    }
}
