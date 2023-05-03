<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckRole
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
        if (Auth::check()) {
            $user = Auth::user();
            if(!count($user->usersSitesRoles()->where('role_id','=',8)->get())){
                alert()->warning('You have no permission to access this page!', 'Permission Denied')->persistent('OK');
                return redirect()->back();
            }

        }
        return $next($request);
    }
}
