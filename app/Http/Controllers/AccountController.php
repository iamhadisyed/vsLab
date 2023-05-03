<?php
namespace App\Http\Controllers;
use View;
use Illuminate\Support\Facades\Redirect;

class AccountController extends Controller {

	public function getSignIn() {
		return View::make('account.signin');
	}

	public function postSignIn() {
		$validator = Validator::make(Input::all(),
			array(
				'username' => 'required',
				'password' => 'required'
			)
		);

		if ($validator->fails()) {
			return Redirect::route('account-signin')
					->withErrors($validator)
					->withInput();
		} else {

			$remember = (Input::has('remember')) ? true : false;

			$auth = Auth::attempt(array(
				'username' => Input::get('username'),
				//'email' => Input::get('email'),
				'password' => Input::get('password'),
				'active' => 1
			), $remember);

			if ($auth) {
				//Redirect to a intended page
				//Notify::success('Welcome to use Mobicloud system.');
				return Redirect::intended('/');
			} else {
				Notify::error('Username/password wrong, or account not activated.');
				return Redirect::route('account-signin');
						//->with('global', "Username, Email, or password wrong, or account not activated.");
			}
		}
		Notify::error('There was a problem signing you in.');
		return Redirect::route('account-signin');
				//->with('global', "There was a problem signing you in.");
	}

	public function getSignOut() {
		Auth::logout();
		return Redirect::route('home');
	}

	public function getCreate() {
		return View::make('account.register');
	}

	public function postCreate() {
		$validator = Validator::make(Input::all(),
			array(
				'email' 	=> 'required|max:50|email|unique:users',
				'username' 	=> 'required|max:20|min:3|unique:users',
				'firstname' => 'required|min:3|',
				'lastname' 	=> 'required|min:3|',
				'password' 	=> 'required|min:6',
				'password_confirm' => 'required|same:password',
				'captcha_text' => 'required|captcha'
			)
		);

		if ($validator->fails()) {
			return Redirect::route('account-register')
					->withErrors($validator)
					->withInput();
		}
		else {

			$email = Input::get('email');
			$username = Input::get('username');
			$password = Input::get('password');
			$firstname = Input::get('firstname');
			$lastname = Input::get('lastname');

			// Activation code
			$code = str_random(60);

			$user = User::create(array(
				'email' => $email,
				'username' => $username,
				'firstname' => $firstname,
				'lastname' => $lastname,
				'password' => Hash::make($password),
				'code' => $code,
				'active' => 0
			));

			if ($user) {

				Mail::send('emails.auth.activate', array('link' => URL::route('account-activate', $code), 'username' => $username), function($message) use ($user) {
					$message->to($user->email, $user->username)->subject('Active your account');
				});

				Notify::info('Your account has been created! We have sent you an email to activate your account.');
				return Redirect::route('account-signin');
						//->with('global', 'Your account has been created! We have sent you an email to activate your account.');
			}
		}

	}

	public function getActivate($code) {
		$user = User::where('code', '=', $code)->where('active', '=', 0);

		if ($user->count()) {
			$user = $user->first();

			// Update user to active state
			$user->active = 1;
			//$user->code   = '';

			if ($user->save()) {
				Notify::success('Activated! You can now sign in!');
				return Redirect::route('account-signin');
						//->with('global', 'Activated! You can now sign in!');
			}
		}

		$user = User::where('code', '=', $code)->where('active', '=', 1);

		if ($user->count()) {
			Notify::warning('You have activated the account. Please sign in!');
			return Redirect::route('account-signin');
		}

		Notify::warning('We could not activate your account. Try again later.');
		return Redirect::route('account-signin');
			 	//->with('global', 'We could not activate your account. Try again later.');
	}

	public function getChangePassword() {
		return View::make('account.password');
	}

	public function postChangePassword() {
		$validator = Validator::make(Input::all(), 
			array(
				'old_password' 		=> 'required',
				'password' 			=> 'required|min:6',
				'password_again' 	=> 'required|same:password',
			)
		);

		if ($validator->fails()) {
			return Redirect::route('account-change-password')
					->withErrors($validator);
		} else {
			$user 			= User::find(Auth::user()->id);
			$old_password 	= Input::get('old_password');
			$password 		= Input::get('password');

			if (Hash::check($old_password, $user->getAuthPassword())) {
				$user->password = Hash::make($password);

				if ($user->save()) {

					Notify::success('Your password has been changed.');
					return Redirect::route('account-signin');
							//->with('global', 'Your password has been changed.');
				}
			} else {

				Notify::error('Your old password is incorrect.');
				return Redirect::route('account-change-password');
						//->with('global', 'Your old password is incorrect.');				
			}
		}

		Notify::warning('Your password could not be changed.');
		return Redirect::route('account-change-password');
				//->with('global', 'Your password could not be changed.');
	}

	public function getForgotPassword() {
		return View::make('account.forgot');
	}

	public function postForgotPassword() {
		$validator = Validator::make(Input::all(), 
			array(
				'email' => 'required|email'
			)
		);

		if ($validator->fails()) {
			return Redirect::route('account-forgot-password')
					->withErrors($validator)
					->withInput();
		} else {
			// change password
			$user = User::where('email', '=', Input::get('email'));

			if ($user->count()) {
				$user = $user->first();

				// generate a new code and password
				$code 		= str_random(60);
				$password 	= str_random(10);

				$user->code = $code;
				$user->password_temp = Hash::make($password);

				if ($user->save()) {
					Mail::send('emails.auth.forgot', array(
								'link' => URL::route('account-recover', $code), 
								'username' => $user->username, 
								'password' => $password), function($message) use ($user) {
						$message->to($user->email, $user->username)->subject('Your new password');
					});

					notify::success('We have sent a new password by email.');
					return Redirect::route('account-signin');
							//->with('global', 'We have sent a new password by email.');
				}
			}
		}

		Notify::error('Could not request new password for the email.');
		return Redirect::route('account-forgot-password');
				//->with('global', 'Could not request new password.');
	}

	public function getRecover($code) {
		$user = User::where('code', '=', $code)
				->where('password_temp', '!=', '');
		
		if ($user->count()) {
			$user = $user->first();

			$user->password = $user->password_temp;
			$user->password_temp = '';
			$user->code = '';

			if ($user->save()) {

				notify::success('Your account has been recovered and you can signin with your new password.');
				return Redirect::route('account-signin');
						//->with('global', 'Your account has been recovered and you can signin with your new password.');
			}
		}

		Notify::error("Cloud not recover your account!");
		return Redirect::route('account-register');
				//->with('global', 'clould not recover your account.');
	}

}