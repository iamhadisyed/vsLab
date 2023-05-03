<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/7/18
 * Time: 12:49 AM
 */

namespace App\Http\Controllers;

use App\Lab;
use Auth;
use App\LabContent;
use App\User;
use App\Group;
use App\Role;
use App\DataTables\MylabsDataTable;
use Sentry;
use Response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class EditorController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                redirect('/');
            }

            $this->user = Auth::user()->email;
            $this->userid = Auth::user()->id;
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     * @param \App\DataTables\MylabsDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function index(MylabsDataTable $dataTable) {
        return $dataTable->render('admin.labs.contenteditor');
//    public function index() {
//        $roles = Role::all();//Get all roles
//
//        return view('admin.roles.index')->with('roles', $roles);
    }

    public function show(MylabsDataTable $dataTable) {
        return $dataTable->render('admin.labs.contenteditor');
//    public function index() {
//        $roles = Role::all();//Get all roles
//
//        return view('admin.roles.index')->with('roles', $roles);
    }






}