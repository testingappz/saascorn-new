<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class CheckProfileStatus
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
        $user = Auth::user();

        if($user->user_type=="owner" && $user->profile_updated==0)
        {
            return redirect('owner/create_owner_profile');
          
        }
        else if($user->user_type=="investor" && $user->profile_updated==0)
        {
            return redirect('investor/create_investor_profile');
        }

        return $next($request);
    }
}
