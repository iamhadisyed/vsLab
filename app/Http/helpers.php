<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/18/18
 * Time: 10:44 PM
 */

if (!function_exists('avatar')) {

    function avatar()
    {
        $user = Auth::user();

        $media = $user->getMedia('avatars')->first();
        $avatar = ($media) ? $media->getUrl() : Gravatar::get($user->email);

        return $avatar;
    }
}