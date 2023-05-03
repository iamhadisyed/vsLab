<?php

/*
 * Taken from
 * https://github.com/laravel/framework/blob/5.3/src/Illuminate/Auth/Console/stubs/make/controllers/HomeController.stub
 */

namespace App\Http\Controllers;
use App\DeployLab;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Traits\CheckProfileTrait;
use App\Traits\CheckPasswordExpireTrait;
use App\Traits\AvatarTrait;
use Auth;
use App\LabActivity;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    use CheckProfileTrait, AvatarTrait,CheckPasswordExpireTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        $errors = array();

        $avatar = $this->getAvatar();
        //array_push($messages, 'Your lab is available now. Please go to \'Classes\'->\'My Class\' to access it.');

        if (!$this->isProfileComplete()) {
            array_push($errors, 'Your profile is incomplete! Please input required information in your profile settings.');
        }
        if (!$this->isPasswordExpired()) {
            array_push($errors, 'Your password has expired! Please update it your profile setting!');
//            Auth::logout();
            //return view('profile.edit')->withErrors($messages)->with('user', Auth::user())->with('avatar', $avatar);
        }
        $grp = Auth::user()->usersGroupsRoles()->whereIn('role_id',array(5,6,9))->get();
        if (count($grp) == 0){
            $role='student';
        }else{
            $role='instructor';
        }
        $lastlab= '';
        $labname= '';
        $lastaccesstime='';
        $userid=Auth::user()->id;
//        $avatar = $this->getAvatar();
        $deploylabid=LabActivity::where('causer_id', '=', $userid)->where('log_name','=','LabAccess')->orderBy('created_at','desc')->first();
        if ($deploylabid !== null){
            $lastlab=$deploylabid->description;
            $lastaccesstime=$deploylabid->created_at;
            try{
//                DeployLab::where('id', '=', $lastlab)->firstOrFail();
//                $projectname=DeployLab::find($lastlab)->project_name;
//
//                $labnames=explode("-", $projectname);
                try {
                    $labname=DeployLab::find($lastlab)->subgroup->group->name;
                } catch (\Exception $e) {
                    $labname='';
                }

            } catch (ModelNotFoundException $e){
                $lastlab='';
            }

        }
        return view('adminlte::home')->withErrors($errors)->with('avatar', $avatar)->with('lastlab',$lastlab)->with('labname',$labname)->with('lastaccesstime',$lastaccesstime)->with('role',$role);
    }

    public function landing()
    {
        return view('adminlte::layouts.landing');
    }

//    public function index()
//    {
//        return view('home');
//    }

    public function dashboard()
    {
        return view('adminlte::dashboard');
    }

    public function session_timeout()
    {
        return view('errors.session_timeout');
    }

    public function pagenotfound()
    {
        return view('errors.404');
    }

    public function serverunavailable()
    {
        return view('errors.503');
    }
}