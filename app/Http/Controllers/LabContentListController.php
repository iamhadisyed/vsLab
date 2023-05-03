<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/7/18
 * Time: 12:49 AM
 */

namespace App\Http\Controllers;

use App\Lab;
use App\User;
use App\Group;
use App\Role;
use App\DataTables\LabContentListDataTable;
use Sentry;
use Response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class LabContentListController extends Controller
{
    protected $user;

    public function __construct() {
//        $this->middleware(['auth', 'isAdmin']);//isAdmin middleware lets only users with a //specific permission permission to access these resources

        $this->user = Sentry::getUser();
    }

    /**
     * Display a listing of the resource.
     * @param \App\DataTables\MylabsDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function index(LabContentListDataTable $dataTable) {
        return $dataTable->render('admin.labs.labcontentlist');
//    public function index() {
//        $roles = Role::all();//Get all roles
//
//        return view('admin.roles.index')->with('roles', $roles);
    }

    public function getLabAll(Request $request) {
        if ($request->ajax()) {

            $result = array();
            $labs = Lab::all(); //Get all sites
            foreach($labs as $lab) {
                array_push($result,
                    array('id' => $lab->id, 'name' => $lab->name, 'description' => $lab->description, 'subgroup' => $lab->subgroup,
                         'start' => date($lab->starttime), 'due' => date($lab->due))
                );
            }
            return Response::json($result);
        }
    }

    public function getLabList(Request $request) {
        if ($request->ajax()) {
            $result = array();
            $id = Sentry::getUser()->getId();
            $user = User::findOrFail($id);

            $sites = $user->sites()->get();
            foreach($sites as $site) {
                array_push($result, array('id' => $site['id'], 'name' => $site['name']));
            }

            return Response::json($result);
        }
    }


}