<?php

namespace App\DataTables;

use App\Permission;
use Yajra\DataTables\Services\DataTable;

class PermissionsDataTable extends DataTable
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
//            ->addColumn('permissions', function (Role $role) {
//                $htmlstr = '';
//                foreach ($role->permissions()->pluck('name') as $permission) {
//                    $htmlstr .= '<span class="label label-info label-many" > ' . $permission . '</span> ';
//                }
//                return $htmlstr;
//            })
            ->addColumn('action', 'admin.permissions.datatable-action');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Permission $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Permission $model)
    {
        return $model->newQuery()->select('id', 'name', 'description', 'type', 'guard_name', 'created_at', 'updated_at');
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
                    ->parameters($this->getBuilderParameters());
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id',
            'name',
            'description',
            'type',
            'guard_name',
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
        return 'Permissions_' . date('YmdHis');
    }
}
