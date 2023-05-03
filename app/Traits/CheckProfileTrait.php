<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/18/18
 * Time: 1:36 PM
 */

namespace App\Traits;

use Auth;
use App\User;
use App\UserProfile;

trait CheckProfileTrait
{
    public function isProfileComplete()
    {
        $user = Auth::user();
        $badName = ['first', 'last', 'first_name', 'last_name', ''];

        if (is_null($user->profile()->first())) {
            $newProfile = new UserProfile();
            $newProfile->user_id = $user->id;
            $newProfile->save();
        }

        $first = $user->profile()->first()->first_name;
        $last = $user->profile()->first()->last_name;

        if (is_null($user->institute) || is_null($user->alt_email) ||
            is_null($user->profile()->first()->last_name) || is_null($user->profile()->first()->first_name) ||
            in_array($first, $badName) || in_array($last, $badName)) {
            return false;
        }

//        if (is_null($user->name))
//        {
//            $cur_user = User::find($user->id);
//            $cur_user->fill(['name' => $first . ' ' . $last])->save();
//        }
        return true;
    }
}

