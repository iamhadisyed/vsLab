<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/18/18
 * Time: 1:36 PM
 */

namespace App\Traits;

use Carbon\Carbon;
use App\User;

trait CheckUserStatusTrait
{
    public function getUserStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->activated == 0) return 'Not Activate';

        $login_t = Carbon::parse($user->last_login);
        $logout_t = Carbon::parse($user->last_logout);
        $active_t = Carbon::parse($user->last_activity);

        if (is_null($login_t) or is_null($active_t) or is_null($logout_t))
            return 'None';

        if ($active_t->gte($login_t) and $logout_t->gte($active_t))
            return 'Offline';

        $current = Carbon::now();
        if ($login_t->gte($logout_t) and $active_t->gte($login_t) and $active_t->addHours(2)->gt($current))
            return 'Online';

        $active_t = Carbon::parse($user->last_activity);
        if ($active_t->gte($login_t) and $active_t->gte($logout_t) and $current->gte($active_t->addHours(2)))
            return 'Timeout';

    }

    public function getUserRoles($id)
    {
        $user = User::findOrFail($id);

        return str_replace(array('[',']','"'),'', $user->roles()->pluck('name'));
    }
}

