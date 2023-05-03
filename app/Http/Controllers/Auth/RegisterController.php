<?php

namespace App\Http\Controllers\Auth;

use App\Jobs\SendInitialEmail;
use DB;
use App\User;
use App\UserProfile;
use Adldap\Laravel\Facades\Adldap;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Jobs\SendVerificationEmail;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;


/**
 * Class RegisterController
 * @package %%NAMESPACE%%\Http\Controllers\Auth
 */
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('adminlte::auth.register');
    }

    /**
     * Where to redirect users after login / registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name'    =>  'required|min:1|max:35',
            'last_name'     =>  'required|min:3|max:35',
            'institute'     =>  'required|min:3|max:35',
            'email'         => 'required|email|max:255|unique:users',
            'password'      => 'required|confirmed|min:6|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'terms'         => 'required',
        ], [
            //'first_name.required'   =>  'Your first name is required.',
            'first_name.min'        =>  'Your first name must be at least 1 character.',
            'first_name.max'        =>  'Your first name may not be greater than 35 character.',
            //'last_name.required'    =>  'Your last name is required.',
            'last_name.min'         =>  'Your last name must be at least 2 character.',
            'last_name.max'         =>  'Your last name may not be greater than 35 character.',
            //'institute.required'    =>  'Your company/institute name is required.',
            'password.required'     => 'Password is required',
            'password.min'          => 'Password must be more than 6 characters long',
            'password.regex'        => 'Password should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $name = $data['first_name'] . ' ' . $data['last_name'];
        $fields = [
            'name'     => $name,
            'email'    => $data['email'],
            'institute'=> $data['institute'],
            'password' => bcrypt($data['password']),
            'activation_code' => base64_encode($data['email'])
        ];
        if (config('auth.providers.users.field','email') === 'username' && isset($data['username'])) {
            $fields['username'] = $data['username'];
        }

        $ldap_user = Adldap::search()->where('cn', '=', $data['email'])->get()->first();
        if (!$ldap_user) {
            $info = ['givenName' => $data['first_name'], 'ou' => $data['last_name'], 'sn' => $data['email'],
                    'userPassword' => $data['password'], 'o' => $data['institute'], 'mail' => $data['email']];

            $ldap_user = Adldap::make()->user($info);
            $ldap_user->setAttribute('objectClass', ['inetOrgPerson', 'extensibleObject']);
            $ldap_user->setDn('cn=' . $data['email'] . ',' . $ldap_user->getDnBuilder()->get());

            $ldap_user->save();
            $ldap_user = Adldap::search()->where('cn', '=', $data['email'])->get()->first();
            $ldap_enable = Adldap::search()->where('cn', '=', 'enabled')->get()->first();
            $ldap_enable->addMember($ldap_user);
        } else {
            $ldap_user->setAttribute('userPassword', $data['password']);
            $ldap_user->save();
        }

        $user = User::create($fields);

        $profile = [
            'user_id'       => $user->id,
            'first_name'    => $data['first_name'],
            'last_name'     => $data['last_name']
        ];
        $userProfile = UserProfile::create($profile);

        return $user;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));
        dispatch(new SendVerificationEmail($user));

        return view('emails.verification');
    }

//    public function registerforbatch(array $newuser)
//    {
//        event(new Registered($user = $this->create($newuser)));
//        dispatch(new SendVerificationEmail($user));
//
//        return view('emails.verification');
//    }

    public function registerforbatch(array $newuser)
    {
        event(new Registered($user = $this->create($newuser)));
//        dispatch(new SendVerificationEmail($user));
//        $array = array(
//            "email" => $newuser['email']
//        );
        dispatch(new SendInitialEmail($user));
//        $this->broker()->sendInitialResetLink($array);
//        DB::table('users')->where('email','=',$newuser['email'])->update(['activated' => '1']);
        return view('emails.verification');
    }

    public function registerforsso(array $newuser)
    {
        event(new Registered($user = $this->create($newuser)));
        //dispatch(new SendVerificationEmail($user));

        //return view('emails.verification');
    }

    public function registerWithoutValidator(User $user)
    {
        event(new Registered($user));
        dispatch(new SendVerificationEmail($user));

        return view('emails.verification');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param $token
     * @return \Illuminate\Http\Response
     */
    public function verify($token)
    {

        $user = User::where('activation_code', $token)->first();
        if (is_null($user)){
            return redirect('/userhome');
        }

        $user->activated = 1;

        if($user->save()){
            return view('emails.emailconfirm', ['user' => $user]);
        }
    }

}
