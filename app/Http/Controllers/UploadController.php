<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 3/30/15
 * Time: 7:50 PM
 */
namespace App\Http\Controllers;

use View;
use Redirect;

class UploadController extends Controller
{
    protected $user;
    //public $restful=true;

    public function __construct()
    {
        if (\Cas::isAuthenticated()) {
            $this->user = \Cas::getCurrentUser();

            //$this->cloudRes = App::make('CloudResource');
        } else {
            return Redirect::to('/');
        }
    }


    public function fileUpload() {
        //$valid_exts = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
        //$max_size = 2000 * 1024; // max file size (200kb)
        //$path = public_path() . '/img/'; // upload directory
        //$fileName = NULL;

        $file = Input::file('uploaded_file');
        $input = array('uploaded_file' => $file);
        $rules = array('uploaded_file' => 'image');
        $validator = Validator::make($input, $rules);
        if ( $validator->fails() ) {
            $response = array('success' => false, 'errors' => $validator->getMessageBag()->toArray());
            return Response::json($response);
        }
        else {
            $path = public_path() . '/files/'; // upload directory
            $filename = $file->getClientOriginalName();
            $file->move($path, $filename);

            $servername = "10.2.255.50";
            $username = "root";
            $password = "Cloud\$erver";
            $database = "mobicloud";
            $port = 3307;
            $conn = new mysqli($servername, $username, $password, $database, $port);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } else {
                $sql = "UPDATE users SET avatar= '" . $filename . "' WHERE email='" . $this->user . "';";
                $result = $conn->query($sql);
            }

            $response = array('success' => true, 'file' => asset($path.$filename));
            return Response::json($response);
        }

    }
}