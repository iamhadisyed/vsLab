<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/25/17
 * Time: 3:15 PM
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\Store;
use Auth;

class SessionTimeout
{
    protected $session;
    protected $timeout = 1200;

    public function __construct(Store $session)
    {
        $this->session = $session;
        $this->redirectUrl    = 'userlogin';
        $this->sessionLabel   = 'warning';
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $isLoggedIn = $request->path() == 'userlogin';
        //if ($isLoggedIn) return $next($request);

        if(!$this->session->has('lastActivityTime')) {
            $this->session->put('lastActivityTime', time());
        }
        elseif(time() - $this->session->get('lastActivityTime') > $this->getTimeout()) {
            $this->session->forget('lastActivityTime');
            //$cookie = cookie('intend', $isLoggedIn ? url()->current() : 'dashboard');
            if (Auth::user()) {
                activity('Session')->log("User's server session timeout.");
                Auth::logout();
            }
            //alert()->warning('You had not activity in ' . $this->timeout/60 . ' minutes.')->persistent('OK');
            return redirect($this->getRedirectUrl())->withErrors( ['timeout' => 'Your session was timeout, Please login again!']);
        }
        $this->session->put('lastActivityTime', time());
        //$isLoggedIn ? $this->session->put('lastActivityTime', time()) : $this->session->forget('lastActivityTime');
        return $next($request);
    }

    /**
     * Get timeout from laravel default's session lifetime, if it's not set/empty, set timeout to 15 minutes
     * @return int
     */
    public function getTimeout()
    {
        return (env('SESSION_TIMEOUT')) ?: $this->timeout;
        //return  ($this->lifetime) ?: $this->timeout;
    }

    /**
     * Get redirect url from env file
     * @return string
     */
    private function getRedirectUrl()
    {
        return  (env('SESSION_TIMEOUT_REDIRECTURL')) ?: $this->redirectUrl;
    }

    /**
     * Get Session label from env file
     * @return string
     */
    private function getSessionLabel()
    {
        return  (env('SESSION_LABEL')) ?: $this->sessionLabel;
    }
}