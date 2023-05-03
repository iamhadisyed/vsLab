<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 12/11/17
 * Time: 3:21 PM
 */

namespace App\Http\Controllers;

use Auth;
use App\User;
use Response;
use Illuminate\Http\Request;

class SessionController extends Controller {

    protected $user;
    protected $redirectUrl;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                redirect('/');
            }
            $this->user = Auth::user();
            $this->redirectUrl = 'userlogin';
            return $next($request);
        });
    }

    public function timeout(Request $request)
    {
        if (Auth::user()) {
            activity("Session")->log("User's Client Timeout.");
            Auth::logout();
        }
        return redirect($this->getRedirectUrl())->withErrors( ['timeout' => 'Your session was timeout, Please login again!']);
    }

    public function clientClosed(Request $request)
    {
        if (Auth::user()) {
            activity("Session")->log("User's Client Closed.");
            Auth::logout();
        }
    }

    protected function getRedirectUrl()
    {
        return  (env('SESSION_TIMEOUT_REDIRECTURL')) ?: $this->redirectUrl;
    }

}