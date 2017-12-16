<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Illuminate\Support\Facades\Auth;
use Agent;
use Config;
use Route;
use Request;

class VisitorTracking
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null){
        if($request->ajax() || $request->wantsJson()) {
            return $next($request);
        }
        $_current_path = Route::current()->getPath();
        if('login' != $_current_path
            && !str_contains($_current_path,'oauth')
            && !str_contains($_current_path,'login')
            && !str_contains($_current_path,'logout')
            && !str_contains($_current_path,'register')
            && !str_contains($_current_path,'redirector')
            && !str_contains($_current_path,'security')
            && !str_contains($_current_path,'captcha')) {
            $_fallback_url = Request::fullUrl();
            $request->session()->set('login_back_fallback', $_fallback_url);
        }
        return $next($request);
    }
}