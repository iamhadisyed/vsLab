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

class SiteGroupsDataTable extends DataTable
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
//            ->editColumn('details_url', function ($row) {
//                return url('groups/members-table/' . $row->id);
//            })
            ->addColumn('admins', function($row) {
                $admins = $row->usersGroupsRoles()->whereHas('Role', function($query) {
                    $query->whereIn('name', ['group_owner', 'instructor', 'TA']);
                })->select('users.email', 'roles.name')
                    ->join('users', 'users.id', '=', 'users_groups_roles.user_id')
                    ->join('roles', 'roles.id', '=', 'users_groups_roles.role_id')
                    ->get();
                return str_replace(",", "<br>",
                        str_replace(array('{', '}', '"'), '',
                            str_replace(':', '<br>&nbsp&nbsp', str_replace(array('[',']','"'),'', $admins->pluck('email', 'name')))));
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
            ->addColumn('resource_util', function ($row) {
                if (is_null($row->getAttribute('resource_allocated'))) {
                    if (is_null($row->getAttribute('resource_usage'))) {
                        return '<span style="color:red">No Resource Limitation</span>';
                    } else {
                        return 'Resource Usage:<br>' . $row->getAttribute('resource_usage');
                    }
                }
                $rss = json_decode($row->getAttribute('resource_allocated'));
                if (!isset($rss->labs) or !isset($rss->vms) or !isset($rss->vcpus) or !isset($rss->ram) or !isset($rss->storage)) {
                    return '<span style="color:darkred">Resource format incorrect!</span>';
                }
                $rss_used = (is_null($row->getAttribute('resource_used')))
                    ? (object) ["labs" => 0, "vms" => 0, "vcpus" => 0, "ram" => 0, "storage" => 0]
                    : json_decode($row->getAttribute('resource_used'));
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
            ->editColumn('expiration', function($row) {
                if ($row->expiration == '0000-00-00 00:00:00') return '';
                return Carbon::parse($row->expiration)->setTimezone($this->timezone)->toDateTimeString();
            })
            ->editColumn('created_at', function($row) {
                return Carbon::parse($row->created_at)->setTimezone($this->timezone)->toDateTimeString();
            })
            ->editColumn('updated_at', function($row) {
                return Carbon::parse($row->updated_at)->setTimezone($this->timezone)->toDateTimeString();
            })
            ->addColumn('action', 'admin.mysites.groups-action')
            ->rawColumns(['admins', 'resource_util', 'action']);
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

        return $model->newQuery()->select('id', 'name', 'description', 'private', 'enabled', 'approved',
               'resource_allocated', 'resource_usage', 'expiration', 'created_at', 'updated_at')
               ->where('site_id', '=', $this->id);
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
//            ->parameters([
//                'lengthMenu' => [ [10, 20, 30, 40, 50, -1], [10, 20, 30, 40, 50, 'All'] ],
//                'dom' => 'Blf<"main-toolbar">rtip',
//                'buttons' => ['csv', 'print', 'reload']
//            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
//            'details_url' => ['title' => '', 'data' => null, 'orderable' => false, 'searchable' => false,
//                'class' => 'details-control', 'defaultContent' => '' ],
            'id' => ['title' => '', 'data' => 'id', 'name' => 'groups.id', 'visible' => false ],
            'name' => ['data' => 'name', 'name' => 'groups.name'],
            'description' => ['data' => 'description', 'name' => 'groups.description'],
            'private' => ['title' => 'Type', 'data' => 'private', 'name' => 'groups.private'],
            'status',
            'admins' => ['title' => 'Administrators'],
            'resource_allocated' => ['visible' => false],
            'resource_usage' => ['visible' => false],
            'resource_util' => ['title' => 'Resource Utilization'],
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