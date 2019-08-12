<?php

namespace App\Http\Middleware;

use Closure;

class liuyan_login
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
        if(empty(session('name'))){
            return redirect('liuyan/liuyan_login');
        }
        return $next($request);
    }
}
