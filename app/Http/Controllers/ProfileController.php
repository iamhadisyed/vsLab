<?php
namespace App\Http\Controllers;
use View;
class ProfileController extends Controller {
	public function user($username) {
		$user = \App\User::where('username', '=', $username);

		if ($user->count()) {
			$user = $user->first();
			return View::make('profile.user')
					->with('user', $user);
		}

		return \App::abort(404);
	}
}
