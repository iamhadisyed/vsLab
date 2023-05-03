<?php
namespace App\Http\Controllers;
use View;
use Gitlab;

class ProjectController  extends Controller {

    //protected $client;

    public function getResource()
    {
        $client = new Gitlab\Client('http://mobisphere.asu.edu/api/v3/'); // change here
        $client->authenticate('JfjH8H61DJY7fATYa71p', \Gitlab\Client::AUTH_URL_TOKEN); // change here

        $project = $client->api('projects')->show(53);
        return View::make('projects', array('project_list' => $project ));

    }

} 