<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/4/18
 * Time: 2:33 PM
 */

namespace App\Http\Controllers;

use App\Traits\CephDiskTrait;
use App\User;
use App\UserProfile;
use Illuminate\Support\Facades\Auth;
use Adldap\Laravel\Facades\Adldap;
use Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use UxWeb\SweetAlert\SweetAlert;
use Creativeorange\Gravatar\Gravatar;

class UserProfileController extends Controller implements HasMedia
{
    use CephDiskTrait;
    use HasMediaTrait;

    public function __construct()
    {

    }

    /**
     * Get All User's Profile
     *
     */
    public function index()
    {
        //$user = $this->getProfile($this->user);
        return view('admin.profiles.index')->with('user', Auth::user());
    }

    /**
     * Show User's Profile by ID
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        if (Auth::user()->id != $id) {
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        $user = $this->getProfile($id);
        return view('admin.profiles.show')->with('user', $user);
    }

    /**
     * Get User's Profile by ID
     *
     * @param \Illuminate\Http\Request  $request
     * @param $id
     * @return mixed
     */
    public function getProfileByID(Request $request, $id)
    {
        if ($request->ajax()) {
            $user = $this->getProfile($id);
            return Response::json($user);
        } else {
            $user = $this->getProfile($id);
            return view('profile.user')->with('user', $user);
        }
    }

    /**
     * /profiles/email/edit
     *
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        //$user = $this->getProfile($id);

        if (Auth::user()->id == $id) {
            $user = Auth::user();
//        } else if (Auth::user()->hasRole('system_admin')) {
//            $user = User::findOrFail($id);
        } else {
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }

        $media = $user->getMedia('avatars')->first();
        $avatar = ($media) ? $media->getUrl() : null;

        return view('profile.edit')->with('user', $user)->with('avatar', $avatar);
    }

    public function editCurrent()
    {
        //$user = $this->getProfile($id);
        $user = Auth::user();

        return view('profile.edit')->with('user', $user);
    }

    /**
     * Update a user's profile
     *
     * @param \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function store(Request $request)
    {

        $rules = [
            'first_name'            =>  'required|min:1|max:35',
            'last_name'             =>  'required|min:3|max:35',
            'institute'             =>  'required|min:3|max:35',
            'phone'                 =>  'numeric',
        //  'email'                 =>  'required|email|unique:users',
        //  'username'              =>  'required|unique:users'
        ];

        $messages = [
            'first_name.required'   =>  'Your first name is required.',
            'first_name.min'        =>  'Your first name must be at least 1 character.',
            'first_name.max'        =>  'Your first name may not be greater than 35 character.',
            'last_name.required'    =>  'Your last name is required.',
            'last_name.min'         =>  'Your last name must be at least 3 character.',
            'last_name.max'         =>  'Your last name may not be greater than 35 character.',
            'institute.required'    =>  'Your company/institute name is required.',
            'institute.min'         =>  'Your company/institute name must be at least 3 character.',
            'institute.max'         =>  'Your company/institute name may not be greater than 35 character.',
            'phone.numeric'         =>  'The phone field only accept number.'
        //  'email.required'        =>  'Your emails address is required.',
        //  'email.unique'          =>  'That email address is already in use.',
        //  'username.required'     =>  'Your username is required.',
        //  'username.unique'       =>  'That username is already in use.'
        ];

        if ($request->get('change-password')) {
            $rules = array_merge($rules, array('old_password' => 'required', 'new_password' => 'required', 'confirm_password' => 'required'));
            $messages = array_merge($messages, array('old_password.required' => 'The original password is required',
                'new_password.required' => 'The new password is required', 'confirm_password.required' => 'The confirm password is required'));
        }

        //$input = $request->get('profile');

        $this->validate($request,$rules, $messages);

        alert()->success('Your profile has been updated successfully.', 'Profile Update')->persistent('OK');
        return view('profile.edit')->with('user', Auth::user());
    }

    /**
     * Update a user's profile
     *
     * @param \Illuminate\Http\Request  $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {

        if (Auth::user()->id == $id) {
            $user = Auth::user();
//        } else if (Auth::user()->hasRole('system_admin')) {
//            $user = User::findOrFail($id);
        } else {
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        $input = $request->all();
        $avatar = null;

        $rules = [
            'first_name'            =>  'required|min:1|max:35',
            'last_name'             =>  'required|min:2|max:35',
            'institute'             =>  'required|min:3|max:35',
            'phone'                 =>  'numeric',
        ];

        $messages = [
            'first_name.required'   =>  'Your first name is required.',
            'first_name.min'        =>  'Your first name must be at least 1 character.',
            'first_name.max'        =>  'Your first name may not be greater than 35 character.',
            'last_name.required'    =>  'Your last name is required.',
            'last_name.min'         =>  'Your last name must be at least 2 character.',
            'last_name.max'         =>  'Your last name may not be greater than 35 character.',
            'institute.required'    =>  'Your company/institute name is required.',
            'institute.min'         =>  'Your company/institute name must be at least 3 character.',
            'institute.max'         =>  'Your company/institute name may not be greater than 35 character.',
            'phone.numeric'         =>  'The phone field only accept number.'
        ];

        if ($request->get('change-password')) {
            $rules = array_merge($rules, array('old_password' => 'required',
                'password' => 'required|confirmed|min:6|different:old_password|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/'));
            $messages = array_merge($messages, array('old_password.required' => 'The original password is required',
                'password.required' => 'The new password is required',
                'password.min' => 'Password must be more than 6 characters long',
                'password.regex' => 'Password should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character'));
        }

        $this->validate($request, $rules, $messages);

        if ($request->get('change-password')) {
            if (Hash::check($request->get('old_password'), $user->password)) {
                $ldap_user = Adldap::search()->where('cn', '=', $user->email)->first();
                $user->fill(['password' => Hash::make($request->get('password'))])->save();
                $user->profile->fill(['password_updated_at'=> date('Y-m-d H:i:s')])->save();
                $ldap_user->update(['userPassword' => $request->get('password')]);
            } else {
                alert()->error('Password does not match!', 'Profile Update')->persistent('OK');
                return view('profile.edit')->with('user', $user)->with('avatar', $avatar);
            }
        }
        $data = array('name' => $input['first_name'] . ' ' . $input['last_name'],
                        'institute' => $input['institute'], 'alt_email' => $input['alt_email']);
        $user->fill($data)->save();
        $user->profile->fill($input)->save();

        if (isset($input['avatar'])&&($input['avatar']->getMimeType()=='image/jpeg'||$input['avatar']->getMimeType()=='image/gif'||$input['avatar']->getMimeType()=='image/png')) {

//            $media = $user->getMedia('avatars')->first();
//            if ($media) {
//                $media->delete();
//            }
            $user->clearMediaCollection('avatars');
            $user->addMediaFromRequest('avatar')->addCustomHeaders(['ACL' => 'public-read'])
                ->toMediaCollection('avatars', 'ceph-avatar');

            $avatar = $user->getMedia('avatars')->first()->getUrl();
        }elseif(isset($input['avatar'])&&$input['avatar']->getMimeType()!='image/jpeg'&&$input['avatar']->getMimeType()!='image/gif'&&$input['avatar']->getMimeType()!='image/png'){
            alert()->error('Please upload gif, jpeg or png image as your avatar.', 'Not a image!')->persistent('OK');
            return view('profile.edit')->with('user', $user)->with('avatar', $avatar);
        }

        $passwd_msg = ($request->get('change-password')) ? '<br>Your password changed! Please re-login!' : '';
        alert()->success('Your profile updated!' . $passwd_msg , 'Profile Update')->persistent('OK');
        return view('profile.edit')->with('user', $user)->with('avatar', $avatar);
    }

    /**
     * Fetch user
     * (You can extract this to repository method)
     *
     * @param $id
     * @return mixed
     */
    public function getProfile($id)
    {
        if (Auth::user()->id == $id) {
            $user = Auth::user();
//        } else if (Auth::user()->hasRole('system_admin')) {
//            $user = User::findOrFail($id);
        } else {
            alert()->warning('You don\'t have permission to do that!');
            return redirect()->back();
        }
        return User::with('profile')->where('id', '=', $id)->firstOrFail();
    }
}