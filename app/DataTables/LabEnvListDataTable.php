<?php

namespace App\DataTables;

use App\LabEnv;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;
use App\UserProfile;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Auth;

class LabEnvListDataTable extends DataTable
{
    protected $user;
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

        return datatables($query)
//            ->addColumn('permissions', 'admin.roles.datatable-permissions')
//            ->addColumn('permissions', function (Role $role) {
//                $htmlstr = '';
//                foreach ($role->permissions()->pluck('name') as $permission) {
//                    $htmlstr .= '<span class="label label-info label-many" > ' . $permission . '</span> ';
//                }
//                return $htmlstr;
//            })
            ->addColumn('state', function($row) {
                $html_str = "";
                if (in_array($row->status, ['CREATE_COMPLETE', 'DELETE_COMPLETE'])) {
                    $html_str .= '<div><i class="fa fa-check-circle" rel="tooltip-right" title="' . $row->status . '" style="font-size:24px; color:green"></i></div>';
                } else if (in_array($row->status, ['CREATE_FAILED', 'PROJECT_FAILED', 'DELETE_FAILED'])) {
                    $html_str .= '<div><i class="fa fa-times-circle" rel="tooltip-right" title="' . $row->status . '" style="font-size:24px; color:red"></i></div>';
                } else if (in_array($row->status, ['CREATE_IN_PROGRESS', 'DELETE_IN_PROGRESS', 'Deploying', 'Deleting', 'Releasing', 'CREATE_PROJECT'])) {
                    $html_str .= '<div><i class="fa fa-spinner fa-spin" rel="tooltip-right" title="' . $row->status . '" style="font-size:24px;"></i></div>';
                }
//                else if (in_array($row->status, ['PROJECT_COMPLETE', 'NONE'])) {
//                    $html_str .= '<div><i class="fa fa-spinner fa-spin" rel="tooltip-right" title="' . $row->status . '" style="font-size:24px;"></i></div>';
//                }
                return $html_str;
            })
            ->editColumn('deploy_at', function($row) {
                return Carbon::parse($row->deploy_at)->setTimezone($this->timezone)->toDateTimeString();
            })
            ->addColumn('action', 'admin.labs.labenvlist-action')
            ->rawColumns(['state', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Lab $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(LabEnv $model)
    {

        //if($this->user == 'jachung@hotmail.com' ||$this->user == 'ydeng19@asu.edu') {

            return $model->newQuery()->select('id', 'name', 'description','publicflag','owner_id','project_id',
                'project_name','deploy_at','status')->where('owner_id',Auth::user()->id);

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
            ->parameters([
                'lengthMenu' => [ [10, 20, 30, 40, 50, -1], [10, 20, 30, 40, 50, 'All'] ],
                'dom' => 'Blf<"main-toolbar">rtip',
                'buttons' => [ 'reload'],
                $this->getBuilderParameters()
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
            'id' => ['visible' => false, 'searchable' => false ],
            'name',
            'description',
            'publicflag' =>['visible' => false, 'searchable' => false ],
            'owner_id' =>['visible' => false, 'searchable' => false ],
            'project_id' => ['visible' => false, 'searchable' => false],
            'project_name',
            'deploy_at'=>['data' => 'deploy_at', 'name' => 'deploy_at'],
            'status' => ['data' => 'status', 'name' => 'subgroup_lab_project.status', 'visible' => false, 'searchable' => false],
            'state' => ['title' => 'Status']

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Labs_' . date('YmdHis');
    }
}
