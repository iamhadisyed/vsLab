<?php

namespace App\DataTables;

use App\LabContent;
use Yajra\DataTables\Services\DataTable;
use Auth;

class LabContentListDataTable extends DataTable
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
        return datatables($query)
//            ->addColumn('permissions', 'admin.roles.datatable-permissions')
//            ->addColumn('permissions', function (Role $role) {
//                $htmlstr = '';
//                foreach ($role->permissions()->pluck('name') as $permission) {
//                    $htmlstr .= '<span class="label label-info label-many" > ' . $permission . '</span> ';
//                }
//                return $htmlstr;
//            })

            ->addColumn('action', 'admin.labs.labcontent-action')
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Lab $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(LabContent $model)
    {

        //if($this->user == 'jachung@hotmail.com' ||$this->user == 'ydeng19@asu.edu') {

            return $model->newQuery()->select('id','lab_cat_id', 'name', 'description')->where('owner_id',Auth::user()->id);

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
            'id'=>[ 'visible' => false],
            'lab_cat_id',
            'name',
            'description',

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
