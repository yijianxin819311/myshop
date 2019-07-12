<?php

namespace App\Http\Middleware;

use Closure;

class Login
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
        //前置
        $result=$request->session()->has('username');
         //dump($result);
        if($result){
            echo "登录成功";
        }
        $response=$next($request);
        echo 22;
        return $response;
    }
}
