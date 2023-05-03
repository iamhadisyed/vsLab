<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\RedirectsCAS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth, App\User, App\UserProfile;
use Cas;


class LoginController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        attemptLogin as attemptLoginAtAuthenticatesUsers;
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('adminlte::auth.login');
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/userhome';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['guest'], ['except' => 'logout']);
//        $this->middleware(['guest', 'activation'], ['except' => 'logout']);
    }

    /**
     * Returns field name to use at login.
     *
     * @return string
     */
    public function username()
    {
        return config('auth.providers.users.field','email');
    }


    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {

        if ($this->username() === 'email') {
                $user = User::where('email', '=', $request->email)->first();
                if (!is_null($user) and ( !$user->activated)) {
                    return false;
                }

                $result = $this->attemptLoginAtAuthenticatesUsers($request);
                if ($result) {
                    $user = Auth::user()->id;
                    $profile = UserProfile::where('user_id', '=', Auth::user()->id)->first();
                    $profile->fill(['timezone' => $request->get('timezone')])->save();
                    return true;
                }
        }
        if ( ! $this->attemptLoginAtAuthenticatesUsers($request)) {
            return $this->attempLoginUsingUsernameAsAnEmail($request);
        }
        return false;
    }

    /**
     * Attempt to log the user into application using username as an email.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function attempLoginUsingUsernameAsAnEmail(Request $request)
    {
        return $this->guard()->attempt(
            ['email' => $request->input('username'), 'password' => $request->input('password')],
            $request->has('remember'));
    }

    public function attemptLogout(Request $request)
    {
        info("User '{Auth::user()->email}' has been successfully logged out.");
        return $this->logout($request);
    }

    public function logoutByTimeout(Request $request)
    {
        info("User '{$this->username()}' session has been timeout and logged out.");
        return $this->logout($request);
    }

}
