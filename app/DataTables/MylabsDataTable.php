<?php

namespace App\DataTables;

use Auth;
use App\DeployLab, App\Subgroup,App\UserGroupRole,App\Group;
use App\UserProfile;
use Carbon\Carbon;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MylabsDataTable extends DataTable
{
    protected $user;
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
            ->addColumn('user_id', function ($row) {
                $subgroup= SubGroup::find($row->subgroup_id);
                $members=$subgroup->users()->get();
                $groupid=$subgroup->group()->first()->id;
                $userid='';
                foreach ($members as $member) {
                    if($member->usersGroupsRoles()->where('group_id','=',$groupid)->whereHas('Role', function($query) {
                        $query->whereIn('name', ['student']);
                    })->count()>0){

                        $userid=$member->id;
                    }
                }
                return $userid;
            })
            ->editColumn('description', function($row) {
                if (strlen($row->description)>120){
                    return substr($row->description,0,120).'<br>'.substr($row->description,120,1000);
                }else{
                    return $row->description;
                }

            })
            ->editColumn('start_at', function($row) {
                return Carbon::parse($row->start_at)->setTimezone($this->timezone)->toDateTimeString();
            })
            ->editColumn('due_at', function($row) {
                return Carbon::parse($row->due_at)->setTimezone($this->timezone)->toDateTimeString();
            })
            ->editColumn('inactivefor', function($row) {
                if(is_null($row->inactivefor)&&$row->user_list!=""){
                    return "Never login";
                }elseif($row->inactivefor!=0){
                    return "Inactive for ".number_format($row->inactivefor/3600,2,'.','').' hours';
                }elseif($row->inactivefor==0){
                    return "Active";
                }

            })
            ->addColumn('action', ' ')
            ->editColumn('action', function($row) {
                $teacher = 'teacher';
                if ($this->role == 1)
                    return view('admin.mylabs.datatable-action');
                else if ($this->role ==0)
                    return view('admin.mylabs.datatable-action', compact('teacher', 'row'));
            })
//            ->addColumn('action', 'admin.mylabs.datatable-action')
            ->rawColumns(['user_list', 'description','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\DeployLab $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(DeployLab $model)
    {
        return $model->newQuery()->select('labenv.id as lab_id', 'labenv.name as name', 'labenv.description as description', 'deploylabs.subgroup_id',
            'deploylabs.description as notes',  'deploylabs.project_id','deploylabs.id','deploylabs.user_list', 'deploylabs.inactivefor')
            ->join('labenv', 'labenv.id', '=', 'deploylabs.lab_id')
            ->join('subgroups', 'deploylabs.subgroup_id', '=', 'subgroups.id')
            ->join('users_subgroups', 'users_subgroups.subgroup_id', '=', 'subgroups.id')

            ->where('subgroups.group_id', '=' , $this->id)
            ->where('users_subgroups.user_id', '=', Auth::user()->id);
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
            'id'=>['visible' => false, 'searchable' => false],
            'user_id'=>['visible' => false, 'searchable' => false],
            'lab_id' => ['visible' => false, 'searchable' => false],
            'name' => ['data' => 'name', 'name' => 'labenv.name', 'title' => 'Lab Environment',],
//            'description' => ['data' => 'description', 'name' => 'labenv.description'],
            'user_list'=>['data' => 'user_list', 'name' => 'deploylabs.user_list'],
           'description',
            'inactivefor'=>['data' => 'inactivefor', 'name' => 'deploylabs.inactivefor','title'=>'Inactive time'],
//            'notes' => ['data' => 'notes', 'name' => 'deploylabs.description'],

//            'start_at' => ['data' => 'start_at', 'name' => 'deploylabs.start_time'],
//            'due_at' => ['data' => 'due_at', 'name' => 'deploylabs.due_at'],
            'project_id' => ['visible' => false, 'searchable' => false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Mylabs_' . date('YmdHis');
    }
}
