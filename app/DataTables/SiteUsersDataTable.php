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

class SiteUsersDataTable extends DataTable
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
            ->addColumn('roles', function($row) {
                //return str_replace(',', '<br>', $this->getUserRoles($row->getAttribute('id')));
                $roles = $row->usersSitesRoles()->select('roles.name')
                    ->join('roles', 'roles.id', '=', 'users_sites_roles.role_id')
                    ->where('site_id', '=', $this->id)
                    ->get();
                return str_replace(',', '<br>', str_replace(array('[',']','"'),'', $roles->pluck('name')));
            })
            ->addColumn('groups', function($row) {
                $groups = $row->usersGroupsRoles()->select('groups.name')
                        ->join('groups', 'groups.id', '=', 'users_groups_roles.group_id')
                        ->where('groups.site_id', '=', $this->id)
                        ->get();
                return str_replace(',', '<br>', str_replace(array('[',']','"'),'', $groups->pluck('name')));
            })
            ->addColumn('status', function($row) { return $this->getUserStatus($row->getAttribute('id')); })
            ->addColumn('action', 'admin.mysites.users-action')
            ->rawColumns(['roles', 'groups', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        return $model->newQuery()->select('id', 'email', 'name', 'last_activity', 'users.created_at', 'users.updated_at')
            ->join('users_sites_roles', 'user_id', '=', 'id')
            ->where('site_id', '=', $this->id)
            ->groupBy('user_id');
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
            //->parameters($this->getBuilderParameters());
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
            'id' => ['visible' => false],
            'email',
            'name',
            'status',
            'roles',
            'groups',
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