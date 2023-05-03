<?php

namespace App\DataTables;

use Auth, App\Site, App\UserProfile;
use Carbon\Carbon;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SitesDataTable extends DataTable
{
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

        $datatable =  datatables($query)
            ->addColumn('admins', function ($row) {
//                return implode('<br>', $row->admins()->pluck('email')->toArray());
                $admins = $row->usersSitesRoles()->whereHas('Role', function($query) {
                    $query->whereIn('name', ['site_admin']);
                })->select('users.email')->join('users', 'users.id', '=', 'users_sites_roles.user_id')->get();
                return str_replace(",", "<br>", str_replace(array('[',']','"'),'', $admins->pluck('email')));
            })
            ->addColumn('resource_util', function ($row) {
                $rss = json_decode($row->resources);
                $rss_used = json_decode($row->resource_usage);
                $labs = ($rss->labs == 0) ? 0 : number_format($rss_used->labs / $rss->labs * 100, 0);
                $vms = ($rss->vms == 0) ? 0 : number_format($rss_used->vms / $rss->vms * 100, 0);
                $vcpus = ($rss->vcpus == 0) ? 0 : number_format($rss_used->vcpus / $rss->vcpus * 100, 0);
                $ram = ($rss->ram == 0) ? 0 : number_format($rss_used->ram / $rss->ram * 100, 0);
                $storage = ($rss->storage == 0) ? 0 : number_format($rss_used->storage / $rss->storage * 100, 0);
                $html_str = '<div>' .
                            'Labs: (' . $rss_used->labs . ' used)<span style="float: right">' . $rss->labs . '</span>' .
                            '<div class="progress"><div class="progress-bar progress-bar-success" role="progressbar" ' .
                                'aria-valuenow="' . $labs . '" aria-valuemin="0" aria-valuemax="100" style="min-width:2em; width:' . $labs . '%">' .
                                $labs . '%' .
                            '</div></div>' .
                            'VMs: (' . $rss_used->vms . ' used)<span style="float: right">' . $rss->vms . '</span>' .
                            '<div class="progress"><div class="progress-bar progress-bar-info" role="progressbar" ' .
                                'aria-valuenow="' . $vms . '" aria-valuemin="0" aria-valuemax="100" style="min-width:2em; width:' . $vms . '%">' .
                                $vms . '%' .
                            '</div></div>' .
                            'vCPUs: (' . $rss_used->vcpus . ' used)<span style="float: right">' . $rss->vcpus . '</span>' .
                            '<div class="progress"><div class="progress-bar progress-bar-success" role="progressbar" ' .
                                'aria-valuenow="' . $vcpus . '" aria-valuemin="0" aria-valuemax="100" style="min-width:2em; width:' . $vcpus . '%">' .
                                $vcpus . '%' .
                            '</div></div>' .
                            'Memory: (' . $rss_used->ram . 'MB used)<span style="float: right">' . $rss->ram . 'MB</span>' .
                            '<div class="progress"><div class="progress-bar progress-bar-info" role="progressbar" ' .
                                'aria-valuenow="' . $ram . '" aria-valuemin="0" aria-valuemax="100" style="min-width:2em; width:' . $ram . '%">' .
                                $ram . '%' .
                            '</div></div>' .
                            'Storage: (' . $rss_used->storage . 'GB used)<span style="float: right">' . $rss->storage . 'GB</span>' .
                            '<div class="progress"><div class="progress-bar progress-bar-success" role="progressbar" ' .
                                'aria-valuenow="' . $storage . '" aria-valuemin="0" aria-valuemax="100" style="min-width:2em; width:' . $storage . '%">' .
                                $storage . '%' .
                            '</div></div>' .
                            '</span>';
                return $html_str;
            })
            ->editColumn('created_at', function($row) {
                return Carbon::parse($row->created_at)->setTimezone($this->timezone)->toDateTimeString();
            })
            ->editColumn('updated_at', function($row) {
                return Carbon::parse($row->updated_at)->setTimezone($this->timezone)->toDateTimeString();
            });

        if ($this->mysite) {
            $datatable = $datatable->addColumn('group_default_resource_display', function ($row) {
                if (is_null($row->group_default_resource)) {
                    return '<span style="color:red">No group default resource setting</span>';
                }
                $default_rss = json_decode($row->group_default_resource);
                return 'Labs: ' . $default_rss->labs . '<br>' .
                        'VMs: ' . $default_rss->vms . '<br>' .
                        'vCPUs: ' . $default_rss->vcpus . '<br>' .
                        'Memory: ' . $default_rss->ram . ' MB<br>' .
                        'Storage: ' . $default_rss->storage . ' GB';
            })
            ->addColumn('action', 'admin.mysites.datatable-action')
            ->rawColumns(['sites', 'admins', 'group_default_resource_display', 'resource_util', 'action']);
        } else {
            $datatable = $datatable->addColumn('action', 'admin.sites.datatable-action')
            ->rawColumns(['sites', 'admins', 'resource_util', 'action']);
        }
        return $datatable;
    }

    protected function progressbar_color($value)
    {
        if ($value > 80) return 'progress-bar-info';
        else if ($value > 30 and $value < 50) return 'progress-bar-warning';
        else return 'progress-bar-danger';
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Site $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Site $model)
    {
        if ($this->mysite) {
            $sites = Auth::user()->usersSitesRoles()->whereHas('Role', function($query) {
                $query->whereIn('name', ['site_admin']);
            })->get()->pluck('site_id');
            return $model->newQuery()->select('id', 'name', 'description', 'resources', 'resource_usage', 'group_default_resource', 'created_at', 'updated_at')
                    ->whereIn('id', $sites);
        }
        return $model->newQuery()->select('id', 'name', 'description', 'resources', 'resource_usage', 'created_at', 'updated_at');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $mytoolbar = ($this->mysite) ? 'Blfrtip' : 'Blf<"main-toolbar">rtip';

        return $this->builder()
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->addAction(['width' => '80px'])
                    //->parameters($this->getBuilderParameters());
                    ->parameters([
                        'lengthMenu' => [ [10, 20, 30, 40, 50, -1], [10, 20, 30, 40, 50, 'All'] ],
                        'dom' => $mytoolbar,
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
        return ($this->mysite) ?
            [
                'id' => ['visible' => false, 'orderable' => false, 'searchable' => false],
                'name',
                'description',
                'admins' => ['title' => 'Administrators'],
                'group_default_resource' => ['visible' => false],
                'group_default_resource_display' => ['title' => 'Group Default Resource'],
                'resources' => ['visible' => false],
                'resource_usage' => ['visible' => false],
                'resource_util' => ['title' => 'Resource Utilization'],
                'created_at',
                'updated_at',
            ]
            :
            [
                'id' => ['visible' => false, 'orderable' => false, 'searchable' => false],
                'name',
                'description',
                'admins' => ['title' => 'Administrators'],
                'resources' => ['visible' => false],
                'resource_usage' => ['visible' => false],
                'resource_util' => ['title' => 'Resource Utilization'],
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
        return 'Sites_' . date('YmdHis');
    }
}
