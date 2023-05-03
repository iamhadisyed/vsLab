<?php

/*
 * Taken from
 * https://github.com/laravel/framework/blob/5.3/src/Illuminate/Auth/Console/stubs/make/controllers/HomeController.stub
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Traits\CheckProfileTrait;
use App\Traits\AvatarTrait;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class ErrorController extends Controller
{
    use CheckProfileTrait, AvatarTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */


    public function pagenotfound()
    {
        return view('errors.404');
    }

    public function serverunavailable()
    {
        return view('errors.503');
    }
}