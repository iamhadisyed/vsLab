<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2/7/19
 * Time: 11:11 AM
 */

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Support\Facades\Validator;

class Activation
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
        $user = User::where('email', '=', $request->email)->first();
        if (!is_null($user) and (!$user->activation_code == "" or !$user->activated)) {
            $v = Validator::make([], []);
            $v->getMessageBag()->add('email', 'Your account is not activated yet!');
            return redirect()->back()->withErrors($v)->withInput();

        }
        return $next($request);
    }
}