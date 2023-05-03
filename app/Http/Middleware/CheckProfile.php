<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckProfile
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
            $badName = ['first', 'last', 'first_name', 'last_name', ''];
            
            $first = $user->profile()->first()->first_name;
            $last = $user->profile()->first()->last_name;
            if (is_null($user->institute) || is_null($user->alt_email) ||
                is_null($user->profile()->first()->last_name) || is_null($user->profile()->first()->first_name) ||
                in_array($first, $badName) || in_array($last, $badName) ) {

                alert()->warning('Please complete your profile settings first!')->persistent('OK');
                return redirect('/profiles/' . $user->id . '/edit');
            }
        }
        return $next($request);
    }
}
