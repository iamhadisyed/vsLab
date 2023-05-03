<?php namespace App\Http\Controllers\Auth;
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2/7/20
 * Time: 7:44 PM
 */
use Sentrasoft\Cas\Facades\Cas;
use Auth, Response;
use DB;
use App\User, App\UserGroupRole;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CasController extends Controller
{
    /**
     * Obtain the user information from CAS.
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function callback(Request $request)
    {
        $input = $request->all();
        $username = Cas::user()->id;
        $register_service = app()->make('App\Http\Controllers\Auth\RegisterController');
        $email= $username."@asu.edu";
        try {
            $user = User::where('email', '=', $email)->firstOrFail();
            $userid=$user->id;
        } catch (ModelNotFoundException $e){
            // send registration invitation email
            $randompass = substr(str_shuffle(MD5(microtime())), 0, 10);
            $fakeuser = array('first_name'=>'first_name', 'last_name'=>'last_name', 'email'=>$email, 'institute'=>'ASU',
                'password'=>$randompass, 'password_confirmation'=>$randompass, 'country'=>'USA');
//                    $newuser = App::make('register_service')->register2($fakeuser);
            $newuser = $register_service->registerforsso($fakeuser);

            // mark new user
            $n_user = User::where('email', '=', $email)->get()->first();
            $n_user->fill(['activated' => '1'])->save();
            $userid=$n_user->id;
        }

        // Here you can store the returned information in a local User model on your database (or storage).

        // This is particularly usefull in case of profile construction with roles and other details
        // e.g. Auth::login($local_user);
        Auth::loginUsingId($userid);
        //Auth::loginUsingId(5);
        if(empty($input)){

            return redirect('/userhome');
        }else {
            if($input['group']=='groups'){
                return redirect('/groups');
            }else{
                return redirect('/mylabs/'.$input['group']);
            }
        }
        //return Redirect::intended('/');

    }

    public function enuser(Request $request)
    {
        if(Auth::user()->email=='touti1988@gmail.com'){
            $input = $request->all();

            $register_service = app()->make('App\Http\Controllers\Auth\RegisterController');
            $email= $input['email'];
            $firstname = $input['firstname'];
            $lastname = $input['lastname'];
            try {
                $user = User::where('email', '=', $email)->firstOrFail();
                $n_user = User::where('email', '=', $email)->get()->first();
                DB::table('users')->where('email','=',$email)->update(['activated' => '1']);
                $userid=$user->id;
            } catch (ModelNotFoundException $e){
                // send registration invitation email
                $randompass = substr(str_shuffle(MD5(microtime())), 0, 10);
                $fakeuser = array('first_name'=>$firstname, 'last_name'=>$lastname, 'email'=>$email, 'institute'=>'ASU',
                    'password'=>$randompass, 'password_confirmation'=>$randompass, 'country'=>'USA');
//                    $newuser = App::make('register_service')->register2($fakeuser);
                $newuser = $register_service->registerforsso($fakeuser);

                // mark new user
                $n_user = User::where('email', '=', $email)->get()->first();
                DB::table('users')->where('email','=',$email)->update(['activated' => '1']);
                $userid=$n_user->id;
            }
            return Response::json([
                'userid' => $userid,
                'email' => $email,
                'firstname' => $firstname,
                'lastname' => $lastname
            ]);
        }else{
            return redirect('/pagenotfound');
        }
    }

    public function engroup(Request $request)
    {
        if(Auth::user()->email=='touti1988@gmail.com') {
            $input = $request->all();
            $ugrs = UserGroupRole::where([['group_id', '=', $input['groupid']], ['user_id', '=', $input['userid']], ['role_id', '=', $input['role']]]);
            $ugrs->delete();


            $ugr = new UserGroupRole();
            $ugr->fill(['group_id' => $input['groupid'], 'user_id' => $input['userid'], 'role_id' => $input['role']])->save();

            $result = ['status' => 'Success', 'message' => ' role has been changed '];
            return Response::JSON($result);
        }else{
            return redirect('/pagenotfound');
        }
    }

    public function disgroup(Request $request)
    {
        if(Auth::user()->email=='touti1988@gmail.com') {
            $input = $request->all();
            $ugrs = UserGroupRole::where([['group_id', '=', $input['groupid']], ['user_id', '=', $input['userid']],  ['role_id', '=', 5]]);
            $ugrs->delete();
            $ugrs = UserGroupRole::where([['group_id', '=', $input['groupid']], ['user_id', '=', $input['userid']],  ['role_id', '=', 6]]);
            $ugrs->delete();
            $ugrs = UserGroupRole::where([['group_id', '=', $input['groupid']], ['user_id', '=', $input['userid']],  ['role_id', '=', 7]]);
            $ugrs->delete();
            $result = ['status' => 'Success', 'message' => ' role has been changed '];
            return Response::JSON($result);
        }else{
            return redirect('/pagenotfound');
        }
    }
}