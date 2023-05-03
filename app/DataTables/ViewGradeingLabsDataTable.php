<?php

namespace App\DataTables;

//use App\Lab;
//use App\LabTask;
use Auth;
use App\LabContent;
use App\UserTask;
use Yajra\DataTables\Services\DataTable;
//use Illuminate\Support\Facades\Auth;
//use DB;

class ViewGradeingLabsDataTable extends DataTable
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

//            ->addColumn('taskname', function ($row) {
//                return $row->labTask()->first()->name;
//            })
            ->addColumn('labname', function ($row) {
                return LabContent::find($row->labid)->name;
            })
//            ->addColumn('groupname', function ($row) {
//                return $row->labTask()->first()->lab()->first()->group()->first()->name;
//            })
            ->addColumn('action', 'admin.labs.viewgradingdatatable-action')
            ->rawColumns(['permissions', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Lab $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(UserTask $model)
    {
//        $lab=Lab::find(614);
//        $tasks=$lab->labTask()->get();
//        foreach ($tasks as $task) {
//            $model = $task->userTask()->get();
//            $result = $result->get()->merge($model);
//        }
//        return $result;
        $result = $model->newQuery()->select('users_tasks.id','users_tasks.grade','lab_task.fullpoints', 'users_tasks.user_id', 'users_tasks.task_id','users_tasks.group_id', 'users.email','user_profiles.first_name','user_profiles.last_name','lab_task.name','lab_task.labid')
            ->with('user')->with('labTask')
            ->rightJoin('users', 'user_id', '=', 'users.id')
            ->leftJoin('user_profiles','users.id','=','user_profiles.user_id')
            ->join('lab_task','task_id','=','lab_task.id')
            ->join('labcontent','lab_task.labid','=','labcontent.id')
            ->where('labcontent.id','=',$this->labid)->where('users_tasks.group_id','=',$this->id)->where('users.email','=',Auth::user()->email);

        //if($this->user == 'jachung@hotmail.com' ||$this->user == 'ydeng19@asu.edu') {
        return $result;
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
                'dom' => 'Bfrtip',
                'pageLength'=>'5',
                "scrollY"=>"200px",
                "scrollCollapse"=>true,
                "paging"=>false,
                "buttons"=> ['csv'],
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
            'user_id'=>[ 'visible' => false],
            'task_id'=>[ 'visible' => false],
            'labid'=>[ 'data' => 'labid','name' => 'lab_task.labid','visible' => false],
            'first_name'=>[ 'name' => 'user_profiles.first_name','title' => 'First Name','visible' => false],
            'last_name'=>[ 'name' => 'user_profiles.last_name','title' => 'Last Name','visible' => false],
            'email'=>[ 'name' => 'users.email','visible' => false],
            'labname',
            'taskname'=>['data' => 'name','name' => 'lab_task.name','title'=>'Task Name'],
            'group_id'=>['visible' => false],
            'grade'=>['name'=>'users_tasks.grade','title'=>'Current Grade'],
            'fullpoints'=>['name'=>'lab_task.fullpoints','title'=>'Available Points','visible' => false],

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
