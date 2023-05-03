<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/24/18
 * Time: 10:59 AM
 */

namespace App\DataTables;

use App\UserProfile;
use App\Group;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GroupsDataTable extends DataTable
{
    protected $timezone;
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        try {
            $this->timezone = UserProfile::findOrFail(Auth::user()->id)->timezone;
        } catch (ModelNotFoundException $e) {
            $this->timezone = 'America/Phoenix';
        }
//        $this->timezone = UserProfile::where('user_id', '=', Auth::user()->id)->first()->timezone;

        return datatables($query)
//            ->addColumn('details_url', function ($row) {
//                return url('/groups/getMembersTable/' . $row->id);
//            })
            ->editColumn('details_url', function ($row) {
                return url('groups/members-table/' . $row->id);
                //                return '<a href="#" onclick="group_details_table($(this))" data-id="'. $row->id . '"><i class="fa fa-toggle-right "></i></a>';
            })
            ->addColumn('roles', function($row) {
                $roles = Auth::user()->usersGroupsRoles()->select('roles.name')
                        ->join('roles', 'roles.id', '=', 'users_groups_roles.role_id')
                        ->where('group_id', '=', $row->id)->get();
                return str_replace(",", "<br>", str_replace(array('[',']','"'),'', $roles->pluck('name')));
            })
            ->editColumn('private', function($row) { return ($row->getAttribute('private')) ? 'Private' : 'Public'; })
            ->addColumn('status', function($row) {
                if ($row->enabled and $row->approved) {
                    return 'Active';
                } elseif ($row->enabled and !$row->approved) {
                    return 'Pending';
                } elseif (!$row->enabled and $row->approved) {
                    return 'Disabled';
                } else {
                    return 'Denied';
                }
            })
            ->editColumn('created_at', function($row) {
                return Carbon::parse($row->created_at)->setTimezone($this->timezone)->toDateTimeString();
            })
            ->editColumn('updated_at', function($row) {
                return Carbon::parse($row->updated_at)->setTimezone($this->timezone)->toDateTimeString();
            })
            ->addColumn('action', 'admin.groups.datatable-action')
            ->rawColumns(['details_url', 'roles', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Group $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Group $model)
    {
        //$userGroups = Auth::user()->usersGroupsRoles()->where('role_id', '=', '9');

        return $model->newQuery()->select('groups.id as id', 'site_id', 'groups.name', 'groups.description',
                    'private', 'enabled', 'approved', 'expiration', 'groups.created_at', 'groups.updated_at')
            ->with('site')->with('usersGroupsRoles')
            ->join('users_groups_roles', 'groups.id', '=', 'users_groups_roles.group_id')
            ->join('roles', 'roles.id', '=', 'users_groups_roles.role_id')
            ->where('user_id', '=', Auth::user()->id)
            ->whereIn('roles.name', ['group_owner', 'instructor', 'TA'])
            ->groupBy('group_id');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '80px'])
//            ->parameters($this->getBuilderParameters());
            ->parameters([
                'lengthMenu' => [ [10, 20, 30, 40, 50, -1], [10, 20, 30, 40, 50, 'All'] ],
                'dom' => 'Blf<"main-toolbar">rtip',
                'buttons' => ['csv', 'print', 'reload']
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'details_url' => ['title' => '', 'data' => null, 'orderable' => false, 'searchable' => false,
                'class' => 'details-control', 'defaultContent' => '' ],
            'id' => ['title' => '', 'data' => 'id', 'name' => 'groups.id', 'visible' => false ],
            'site_id' => ['data' => 'site.name', 'name' => 'site.name', 'title' => 'Site'],
            'name' => ['data' => 'name', 'name' => 'groups.name'],
            'description' => ['data' => 'description', 'name' => 'groups.description'],
            'roles' => ['title' => 'Your Roles'],
            'private' => ['title' => 'Type', 'data' => 'private', 'name' => 'groups.private'],
            'status',
            'expiration',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Groups_' . date('YmdHis');
    }
}