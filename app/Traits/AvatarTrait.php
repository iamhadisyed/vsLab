<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/18/18
 * Time: 3:04 PM
 */

namespace App\Traits;

use Auth;

trait AvatarTrait
{
    public function getAvatar()
    {
        $user = Auth::user();

        $media = $user->getMedia('avatars')->first();
        $avatar = ($media) ? $media->getUrl() : null;
        $avatar = null;
        return $avatar;
    }
}