<?php

namespace MillionsSaving\Http\Middleware;

use Closure;
use MillionsSaving\User;
class CheckUserStatus
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
    
        return $next($request);
    }
}
