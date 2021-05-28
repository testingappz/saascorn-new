<?php

namespace App\Http\Middleware;

use Closure;

class CheckApproved
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
      if (!auth()->user()->email_verified_at) {
           return redirect('/login')->with('verifyalert', 'Please verify your account');
       }
        return $next($request);
    }
}
