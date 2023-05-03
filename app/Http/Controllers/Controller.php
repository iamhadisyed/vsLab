<?php
//App\Http\Controllers;
namespace App\Http\Controllers;
//use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController {

	//use DispatchesCommands, ValidatesRequests,DispatchesJobs,AuthorizesRequests;
    use ValidatesRequests,DispatchesJobs,AuthorizesRequests;

}
