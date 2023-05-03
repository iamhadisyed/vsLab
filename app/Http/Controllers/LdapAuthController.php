<?php
namespace App\Http\Controllers;

use View;
use Redirect;

class LdapAuthController extends Controller {
 
	public function __construct(LDAP $ldap)
	{
		$this->ldap = $ldap;
	}
 
	public function login()
	{
		if ( $this->ldap->authenticate( Input::get('email'), Input::get('password') ) )	{
			$user = User::where('email', Input::get('email'))->first();
 
			Auth::login( $user );
 
			return Redirect::to('home');
		}
 
		return Redirect::refresh()->with('error', 'User and/or password are incorrect.');
	}
 
	public function logout()
	{
 
		if ( ! Auth::guest())
		{
			Auth::logout();
 
			return Redirect::to('message')
					->with('message', 'You just logged out.');
		}
 
		return Redirect::to('login');
 
	}
	 
}