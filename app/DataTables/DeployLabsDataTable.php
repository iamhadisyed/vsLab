<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/2/18
 * Time: 3:22 PM
 */

namespace App\DataTables;

use Auth;
use App\DeployLab;
use App\UserProfile;
use Carbon\Carbon;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeployLabsDataTable extends DataTable
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
//            $this->timezone = UserProfile::where('user_id', '=', Auth::user()->id)->first()->timezone;
        return datatables($query)
            ->addColumn('check', function($row){
                return '<input type="checkbox" name="labs[]" class="group-checkable" value="' .
                    $row->project_name . '" data-labid="' . $row->lab_id . '" data-id="' . $row->id . '" style="margin-left:8px">';
            })
            ->addColumn('lab_contents', function($row){
//                $contents = DeployLab::find($row->id)->labcontents()->get();
//                return (count($contents) > 0) ? str_replace(",", "<hr>", str_replace(array('[',']','"'),'', $contents->pluck('name'))) : '';
                $contents = DeployLab::find($row->id)->labcontents()->get();
                $labcontents = '<div id="contents_container_' . $row->id . '">';
                foreach ($contents as $content) {
                    $content_tag = $row->id . '-' . $content->id;
                    $labcontents .= '<div id="content_' . $content_tag . '">' .
                        '<i class="fa fa-book" style="color: blue" title="Lab Content"></i>&nbsp;<span id="name_' . $content_tag . '">' . $content->name . '</span><br>' .
                        '&nbsp;&nbsp;&nbsp;<span style="white-space:nowrap"><i class="fa fa-clock-o" style="color: green" title="Lab Start At"></i>' .
                        '<span id=start_at_' . $content_tag . '>' . ((is_null($content->pivot->start_at) or $content->pivot->start_at == "") ? '' :
                            Carbon::parse($content->pivot->start_at)->setTimezone($this->timezone)->toDateTimeString()) . '</span></span><br>' .
                        '&nbsp;&nbsp;&nbsp;<span style="white-space:nowrap"><i class="fa fa-clock-o" style="color: red" title="Lab Due At"></i>' .
                        '<span id=due_at_' . $content_tag . '>' . ((is_null($content->pivot->due_at) or $content->pivot->due_at == "") ? '' :
                            Carbon::parse($content->pivot->due_at)->setTimezone($this->timezone)->toDateTimeString()) . '</span></span>' .
                        '</div><hr style="margin: 0; color: blue">';
                }
                $labcontents .= '</div>';
                return $labcontents;
            })
            ->addColumn('state', function($row) {
                $html_str = "";
                if (in_array($row->status, ['CREATE_COMPLETE'])) { //}, 'DELETE_COMPLETE'])) {
                    $html_str .= '<div><i class="fa fa-check-circle" rel="tooltip-right" title="' . $row->status . '" style="font-size:24px; color:green"></i></div>';
                } else if (in_array($row->status, ['CREATE_FAILED', 'PROJECT_FAILED', 'DELETE_FAILED'])) {
                    $html_str .= '<div><i class="fa fa-times-circle" rel="tooltip-right" title="' . $row->status . '" style="font-size:24px; color:red"></i></div>';
                } else if (in_array($row->status, ['CREATE_IN_PROGRESS', 'DELETE_IN_PROGRESS', 'Deploying', 'Deleting', 'Releasing', 'CREATE_PROJECT'])) {
                    $html_str .= '<div><i class="fa fa-spinner fa-spin" rel="tooltip-right" title="' . $row->status . '" style="font-size:24px;"></i></div>';
                }
                else if (in_array($row->status, ['DELETE_COMPLETE', 'PROJECT_COMPLETE', 'NONE'])) {
                    $html_str .= '<div>Ready to Deploy</div>';
                }
                return $html_str;
            })
            ->editColumn('assign_at', function($row) {
                return Carbon::parse($row->assign_at)->setTimezone($this->timezone)->toDateTimeString();
            })
            ->editColumn('deploy_at', function($row) {
                return Carbon::parse($row->deploy_at)->setTimezone($this->timezone)->toDateTimeString();
            })
//            ->editColumn('start_at', function($row) {
//                return Carbon::parse($row->start_at)->setTimezone($this->timezone)->toDateTimeString();
//            })
//            ->editColumn('due_at', function($row) {
//                return Carbon::parse($row->due_at)->setTimezone($this->timezone)->toDateTimeString();
//            })
//            ->addColumn('action', 'admin.deploylabs.datatable-action')
            ->addColumn('action', ' ')
            ->addColumn('action', function($row) {
                $deploy = 'lab_deploy';
                $release = 'lab_release';
                $delete = 'lab_delete';
                $labview = 'lab_view';
                if (in_array($row->status, ['CREATE_COMPLETE']))
                    return view('admin.deploylabs.datatable-action', compact('release', 'labview', 'row'));
                else if (in_array($row->status, ['PROJECT_COMPLETE', 'NONE']))
                    return view('admin.deploylabs.datatable-action', compact('deploy', 'delete', 'row'));
                return view('admin.deploylabs.datatable-action', compact('delete', 'row'));
            })
            ->rawColumns(['check', 'state', 'lab_contents', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\DeployLab $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(DeployLab $model)
    {
        return $model->newQuery()->select('deploylabs.id',
            'deploylabs.subgroup_id', 'subgroups.name as team_name', 'deploylabs.lab_id',
            'labenv.name as lab_name', 'deploylabs.description as notes', 'deploylabs.project_id',
            'deploylabs.project_name', 'deploylabs.assign_at', 'deploylabs.deploy_at',
            'deploylabs.start_time as start_at', 'deploylabs.due_at', 'deploylabs.status')
            ->join('subgroups', 'deploylabs.subgroup_id', '=', 'subgroups.id')
            ->join('labenv', 'labenv.id', '=', 'deploylabs.lab_id')
            ->where('subgroups.group_id', '=', $this->id);
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
                'dom' => 'Bflrtip',
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
            'check' => ['data' => 'check', 'name' => 'check', 'style' => 'width:20px', 'orderable' => false,
                 'searchable' => false, 'printable' => false, 'exportable' => false, 'class' => 'checkall' ],
            'id' => ['visible' => false, 'searchable' => false ],
            'subgroup_id' => ['visible' => false, 'searchable' => false ],
            'team_name' => ['data' => 'team_name', 'name' => 'subgroups.name'],
            'lab_id' => ['visible' => false, 'searchable' => false],
            'lab_name' => ['title' => 'Lab Environment', 'data' => 'lab_name', 'name' => 'labenv.name', ],
            'lab_contents',
            'notes' => ['data' => 'notes', 'name' => 'deploylabs.description'],
            'project_id' => ['visible' => false, 'searchable' => false],
            'project_name' => ['data' => 'project_name', 'name' => 'deploylabs.project_name'],
            'assign_at' => ['data' => 'assign_at', 'name' => 'deploylabs.assign_at'],
            'deploy_at' => ['data' => 'deploy_at', 'name' => 'deploylabs.deploy_at'],
//            'start_at' => ['data' => 'start_at', 'name' => 'deploylabs.start_time'],
//            'due_at' => ['data' => 'due_at', 'name' => 'deploylabs.due_at'],
            'status' => ['data' => 'status', 'name' => 'deploylabs.status', 'visible' => false, 'searchable' => false],
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
        return 'LabDeployment_' . date('YmdHis');
    }
}