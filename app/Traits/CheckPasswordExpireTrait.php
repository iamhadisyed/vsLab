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

trait CheckPasswordExpireTrait
{
    public function isPasswordExpired()
    {
        $user = Auth::user();
        $badName = ['first', 'last', 'first_name', 'last_name', ''];

        if (is_null($user->profile()->first())) {
            $newProfile = new UserProfile();
            $newProfile->user_id = $user->id;
            $newProfile->save();
        }


        if (abs(time()-strtotime($user->profile()->first()->password_updated_at))/60/60/24>180) {
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

