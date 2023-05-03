<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/24/18
 * Time: 10:59 AM
 */

namespace App\DataTables;

use App\LabContent;
use Auth;


use Yajra\DataTables\Services\DataTable;

class LabContentDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {

        return datatables($query)
//            ->addColumn('details_url', function ($row) {
//                return url('/groups/getMembersTable/' . $row->id);
//            })
            ->editColumn('details_url', function ($row) {
                return url('labcontents/tasks-table/' . $row->id);
                //                return '<a href="#" onclick="group_details_table($(this))" data-id="'. $row->id . '"><i class="fa fa-toggle-right "></i></a>';
            })

            ->addColumn('action', 'admin.labs.labcontentdatatable-action')
            ->rawColumns(['details_url', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\LabContent $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(LabContent $model)
    {
        //$userGroups = Auth::user()->usersGroupsRoles()->where('role_id', '=', '9');

        return $model->newQuery()->select('id', 'name', 'description', 'lab_cat_id', 'created_at', 'updated_at')
            ->where('owner_id', '=', Auth::user()->id);
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
                'dom' => 'Bf<"main-toolbar">rtip',
                'pageLength'=>'5',
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
            'id' => ['title' => '', 'data' => 'id', 'name' => 'id', 'visible' => false ],
            'lab_cat_id',
            'name' => ['data' => 'name', 'name' => 'name'],
            'description' => ['data' => 'description', 'name' => 'description','searchable' => false,'visible' => false,],
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
        return 'LabContents_' . date('YmdHis');
    }
}