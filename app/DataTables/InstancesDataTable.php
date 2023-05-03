<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/7/20
 * Time: 3:14 PM
 */

namespace App\DataTables;
use App\DeployLab;
use App\Traits\CheckUserStatusTrait;
use Auth;
use App\Instance;
use App\Instanceinactive;
use Carbon\Carbon;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use DB;

class InstancesDataTable extends DataTable
{
    use CheckUserStatusTrait;
    //protected $timezone;
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        //$this->timezone = UserProfile::where('user_id','=',Auth::user()->id)->first()->timezone;

        return datatables($query)
            ->addColumn('check', function($row){
                return '<input type="checkbox" name="labs[]" class="group-checkable" value="' .
                    $row->uuid . '" style="margin-left:8px">';
            })
//            ->addColumn('users_status', function($row){
//                if ($row->project_name) {
//                    $subgroup = DeployLab::where('project_name', '=', $row->project_name)->first()->subgroup()->first();
//                    $active = 0;
//                    $user_count = 0;
//                    if ($subgroup) {
//                        $users = $subgroup->users()->get();
//                        foreach ($users as $user) {
//                            if ($this->getUserStatus($user->id) == "Online")
//                                $active += 1;
//                        }
//                        $user_count =  count($users);
//                    }
//                    $response = "Users: " . $user_count;
//
//                    $response .= "<br>Active Users: " . $active;
//
//                    return $response;
//                }
//                return 'Project does not exist!';
//            })
//            ->addColumn('user_list', function($row){
//                if ($row->project_name) {
//                    $subgroup = DeployLab::where('project_name', '=', $row->project_name)->first()->subgroup()->first();
//
//                    $members_str="";
//
//                    if ($subgroup) {
//                        $users = $subgroup->users()->get();
//                        $groupid=$subgroup->group()->first()->id;
//                        foreach ($users as $user) {
//                            if(($user->usersGroupsRoles()->where('group_id','=',$groupid)->whereHas('Role', function($query) {
//                                    $query->whereIn('name', ['student']);
//                                })->count()>0)){
//
//
//                                $members_str.=$user->email."<br>";
//                            }
//                        }
//
//                    }
//                    $response = $members_str;
//
//                    return $response;
//                }
//                return 'Project does not exist!';
//            })
//            ->addColumn('active_user_list', function($row){
//                if ($row->project_name) {
//                    $subgroup = DeployLab::where('project_name', '=', $row->project_name)->first()->subgroup()->first();
//
//                    $members_str="";
//
//                    if ($subgroup) {
//                        $users = $subgroup->users()->get();
//                        $groupid=$subgroup->group()->first()->id;
//                        foreach ($users as $user) {
//                            $userstatus=$this->getUserStatus($user->id);
//                            if(($user->usersGroupsRoles()->where('group_id','=',$groupid)->whereHas('Role', function($query) {
//                                        $query->whereIn('name', ['student']);
//                                    })->count()>0)&&($userstatus == "Online")){
//
//                                $members_str.=$user->email." is active<br>";
//                            }
//                            if(($user->usersGroupsRoles()->where('group_id','=',$groupid)->whereHas('Role', function($query) {
//                                        $query->whereIn('name', ['student']);
//                                    })->count()>0)&&($userstatus == "Online")){
//                                try {
//                                    DB::table('instances_status')->insert([
//                                        ['uuid' => $row->uuid, 'inactivefor'=>0,'instanceid'=>$row->id]
//                                    ]);
//                                } catch (QueryException $e) {
//                                    DB::table('instances_status')->where('instanceid',$row->id)->
//                                    update(
//                                        array(
//                                            'inactivefor'=>0
//                                        )
//                                    );
//                                }
//                            }
//                        }
//
//                    }
//                    $response = $members_str;
//
//                    return $response;
//                }
//                return 'Project does not exist!';
//            })
//            ->addColumn('inactive_user_list', function($row){
//                if ($row->project_name) {
//                    $subgroup = DeployLab::where('project_name', '=', $row->project_name)->first()->subgroup()->first();
//
//                    $members_str="";
//
//                    if ($subgroup) {
//                        $users = $subgroup->users()->get();
//                        $groupid=$subgroup->group()->first()->id;
//                        foreach ($users as $user) {
//                            $userstatus=$this->getUserStatus($user->id);
//                            if(($user->usersGroupsRoles()->where('group_id','=',$groupid)->whereHas('Role', function($query) {
//                                        $query->whereIn('name', ['student']);
//                                    })->count()>0)&&!($userstatus== "Online")){
//                                $logout_t = Carbon::parse($user->last_logout);
//                                $active_t = Carbon::parse($user->last_activity);
//                                $current = Carbon::now();
//
//
//                                if($userstatus== "Offline"){
//                                    $totalDuration = $current->diffInSeconds($logout_t);
//                                    //$time=gmdate('H', $totalDuration);
//                                    //$members_str.=$user->email." has logged out for ".$time." hours.<br>";
//                                    try {
//                                        DB::table('instances_status')->insert([
//                                            ['uuid' => $row->uuid, 'inactivefor'=>$totalDuration,'instanceid'=>$row->id]
//                                        ]);
//                                    } catch (QueryException $e) {
//                                        DB::table('instances_status')->where('instanceid',$row->id)->
//                                        update(
//                                            array(
//                                                'inactivefor'=>$totalDuration
//                                            )
//                                        );
//                                    }
//                                }elseif($userstatus== "Timeout"){
//                                    $totalDuration = $current->diffInSeconds($active_t);
//                                    //$time=gmdate('H', $totalDuration);
//                                    //$members_str.=$user->email." has time out for ".$time." hours.<br>";
//                                    try {
//                                        DB::table('instances_status')->insert([
//                                            ['uuid' => $row->uuid, 'inactivefor'=>$totalDuration,'instanceid'=>$row->id]
//                                        ]);
//                                    } catch (QueryException $e) {
//                                        DB::table('instances_status')->where('instanceid',$row->id)->
//                                        update(
//                                            array(
//                                                'inactivefor'=>$totalDuration
//                                            )
//                                        );
//                                    }
//
//                                }
//                            }
//                            if(($user->usersGroupsRoles()->where('group_id','=',$groupid)->whereHas('Role', function($query) {
//                                        $query->whereIn('name', ['student']);
//                                    })->count()>0)&&!($userstatus== "Online")){
//                                $logout_t = Carbon::parse($user->last_logout);
//                                $active_t = Carbon::parse($user->last_activity);
//                                $current = Carbon::now();
//                                if($userstatus== "Offline"){
//                                    $totalDuration = $current->diffInSeconds($logout_t);
//                                    $time=gmdate('H', $totalDuration);
//                                    $members_str.=$user->email." has logged out for ".$time." hours.<br>";
//                                }elseif($userstatus== "Timeout"){
//                                    $totalDuration = $current->diffInSeconds($active_t);
//                                    $time=gmdate('H', $totalDuration);
//                                    $members_str.=$user->email." has time out for ".$time." hours.<br>";
//                                }elseif($userstatus== "None"){
//                                    $members_str.=$user->email." is inactive<br>";
//                                }
//                            }
//                        }
//
//                    }
//                    $response = $members_str;
//
//                    return $response;
//                }
//                return 'Project does not exist!';
//            })
            ->addColumn('action', ' ')
            ->editColumn('action', function($row) {
                $suspend = 'vm_suspend';
                $start = 'vm_start';
                $resume = 'vm_resume';
                if ($row->vm_state == 'active')
                    return view('admin.sysadmin.instances-actions', compact('suspend', 'row'));
                else if ($row->vm_state == 'suspended')
                    return view('admin.sysadmin.instances-actions', compact('resume', 'row'));
                else if ($row->vm_state == 'stopped')
                    return view('admin.sysadmin.instances-actions', compact('start', 'row'));
            })
            ->editColumn('inactivefor', function($row) {

                return number_format($row->inactivefor/3600,2,'.','').' hours';
            })
            ->rawColumns(['check', 'user_list', 'active_user_list','inactive_user_list', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Instance $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Instance $model)
    {
        if($this->id==0){
            return $model->newQuery()->select('instances_federated.id','instances_federated.uuid', 'instances_federated.project_id', 'power_state', 'vm_state',
                'hostname', 'display_name', 'display_description', 'memory_mb', 'vcpus', 'project_name','instances_status.inactivefor', 'instances_status.inactive_user_list','instances_status.active_user_list','instances_status.user_list')
                ->leftJoin('deploylabs', 'instances_federated.project_id', '=', 'deploylabs.project_id')
                ->join('instances_status', 'instances_status.instanceid', '=', 'instances_federated.id')
                ->whereNotIn('vm_state', ['error', 'deleted','building']);
        }else{
            return $model->newQuery()->select('instances_federated.id','instances_federated.uuid', 'instances_federated.project_id', 'power_state', 'vm_state',
                'hostname', 'display_name', 'display_description', 'memory_mb', 'vcpus', 'project_name','instances_status.inactivefor','instances_status.inactive_user_list','instances_status.active_user_list','instances_status.user_list' )
                ->join('deploylabs', 'instances_federated.project_id', '=', 'deploylabs.project_id')
                ->join('subgroups', 'deploylabs.subgroup_id', '=', 'subgroups.id')
                ->join('instances_status', 'instances_status.instanceid', '=', 'instances_federated.id')
                ->whereNotIn('vm_state', ['error', 'deleted','building'])->where('subgroups.group_id', '=', $this->id);
        }

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
            'uuid' => ['visible' => false, 'searchable' => false ],
            'id' => ['visible' => false, 'searchable' => false ],
            'project_id' => ['visible' => false, 'searchable' => false ],
            'project_name' =>['data' => 'project_name', 'name' => 'deploylabs.project_name'],
//            'users_status',
            'user_list'=>['data' => 'user_list', 'name' => 'instances_status.user_list'],
            'active_user_list'=>['data' => 'active_user_list', 'name' => 'instances_status.active_user_list'],
            'inactive_user_list'=>['data' => 'inactive_user_list', 'name' => 'instances_status.inactive_user_list'],
            'inactivefor'=>['data' => 'inactivefor', 'name' => 'instances_status.inactivefor','title'=>'Inactive time'],
            'power_state' =>['visible' => false, 'searchable' => false ],
            'vm_state',
            //'hostname',
            'display_name',
            //'display_description',
            'memory_mb',
            'vcpus'
//            'team_name' => ['data' => 'team_name', 'name' => 'subgroups.name'],
//            'lab_id' => ['visible' => false, 'searchable' => false],
//            'lab_name' => ['title' => 'Lab Environment', 'data' => 'lab_name', 'name' => 'labenv.name', ],
//            'lab_contents',
//            'notes' => ['data' => 'notes', 'name' => 'deploylabs.description'],
//            'project_id' => ['visible' => false, 'searchable' => false],
//            'project_name' => ['data' => 'project_name', 'name' => 'deploylabs.project_name'],
//            'assign_at' => ['data' => 'assign_at', 'name' => 'deploylabs.assign_at'],
//            'deploy_at' => ['data' => 'deploy_at', 'name' => 'deploylabs.deploy_at'],
//            'start_at' => ['data' => 'start_at', 'name' => 'deploylabs.start_time'],
//            'due_at' => ['data' => 'due_at', 'name' => 'deploylabs.due_at'],
//            'status' => ['data' => 'status', 'name' => 'deploylabs.status', 'visible' => false, 'searchable' => false],
//            'state' => ['title' => 'Status']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Instances_' . date('YmdHis');
    }
}