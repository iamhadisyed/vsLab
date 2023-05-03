<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/18/18
 * Time: 1:36 PM
 */

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Adldap\Laravel\Facades\Adldap;

trait CustomResetsPasswords
{
    use ResetsPasswords;

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
        ];
    }

    /**
     * Get the password reset validation error messages.
     *
     * @return array
     */
    protected function validationErrorMessages()
    {
        return [
            'password.required'     => 'Password is required',
            'password.min'          => 'Password must be more than 6 characters long',
            'password.regex'        => 'Password should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character'
        ];
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->forceFill([
            'password' => bcrypt($password),
            'remember_token' => Str::random(60),
        ])->save();

        $ldap_user = Adldap::search()->where('cn', '=', $user->getEmailForPasswordReset())->get()->first();
        if ($ldap_user) {
            $ldap_user->setAttribute('userPassword', $password);
            $ldap_user->save();
        }

        $this->guard()->login($user);
    }
}

