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
use Illuminate\Support\Facades\Storage;
/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class DownloaderController extends Controller
{

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


    public function getfmdataset()
    {
        $content = fopen(Storage::path('downloadcounts.txt'),'r');
        $line = fgets($content);
        fclose($content);
        $counts=(int)$line;
        Storage::disk('local')->put('downloadcounts.txt', $counts+1);
        return redirect()->away('https://drive.google.com/drive/folders/1RXj0t8NMYt_Jr5lW-BIeAIxw4aX5VshV');
    }

    public function getdownloadcounts()
    {
        $content = fopen(Storage::path('downloadcounts.txt'),'r');
        $line = fgets($content);
        fclose($content);
        $counts=(int)$line;
        return $counts;
    }

}