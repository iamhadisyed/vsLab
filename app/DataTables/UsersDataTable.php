<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 4/3/18
 * Time: 10:46 AM
 */

namespace App\DataTables;

use App\Traits\CheckUserStatusTrait;
use App\User;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    use CheckUserStatusTrait;
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
            ->addColumn('roles', function($row) { return str_replace(',', '<br>', $this->getUserRoles($row->getAttribute('id'))); })
            ->addColumn('status', function($row) { return $this->getUserStatus($row->getAttribute('id')); })
            ->addColumn('action', 'admin.users.datatable-action')
            ->rawColumns(['roles', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        return $model->newQuery()->select('id', 'email', 'name', 'last_activity', 'created_at', 'updated_at');
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
            'email',
            'name',
            'status',
            'roles',
            'last_activity',
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
        return 'Users_' . date('YmdHis');
    }
}