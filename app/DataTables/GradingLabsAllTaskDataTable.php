<?php

namespace App\DataTables;

//use App\Lab;
use App\LabTask;
use App\LabContent;
use App\UserTask;
use Yajra\DataTables\Services\DataTable;
//use Illuminate\Support\Facades\Auth;
use DB;

class GradingLabsAllTaskDataTable extends DataTable
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
            ->editColumn('graded', function ($row) {

                $graded = 0;

                $counts = 0;
                $grades = DB::table('users_tasks')->select( 'graded')->join('lab_task', 'lab_task.id', '=', 'task_id')->where('user_id', '=', $row->user_id)
                    ->where('lab_task.labid', '=', $row->labid)->get();
                foreach ($grades as $ingrade) {

                    $graded = $graded + $ingrade->graded;

                    $counts = $counts + 1;
                }

                    if($graded==0){
                        return 'Not graded yet';
                    }else{
                        return 'Graded';
                    }

            })
            ->editColumn('finished', function ($row) {

                $graded = 0;
                $finished = 0;
                $counts = 0;
                $grades = DB::table('users_tasks')->select('grade', 'graded', 'finished')->join('lab_task', 'lab_task.id', '=', 'task_id')->where('user_id', '=', $row->user_id)
                    ->where('lab_task.labid', '=', $row->labid)->get();
                foreach ($grades as $ingrade) {

                    $graded = $graded + $ingrade->graded;
                    $finished = $finished + $ingrade->finished;
                    $counts = $counts + 1;
                }
                if($row->labid==695){
                    if($finished!=$counts||$counts!=4){
                        return 'Not fully submitted yet';
                    }else{
                        return 'Fully submitted';
                    }
                }else{
                    if($finished!=$counts){
                        return 'Not submitted yet';
                    }else{
                        return 'Submitted';
                    }

                }
            })
            ->editColumn('update_time', function ($row) {

                $update_time = 0;


                $grades = DB::table('users_tasks')->select('update_time')->join('lab_task', 'lab_task.id', '=', 'task_id')->where('user_id', '=', $row->user_id)
                    ->where('lab_task.labid', '=', $row->labid)->get();
                foreach ($grades as $ingrade) {

                    if($ingrade->update_time>$update_time)
                        $update_time = $ingrade->update_time;

                }

                        return $update_time;

            })
            ->addColumn('labname', function ($row) {
               return LabContent::find($row->labid)->name;
            })
            ->addColumn('totalpoints', function ($row) {
                $totalpoints = 0;
                $graded = 0;

                $counts = 0;
                $grades = DB::table('users_tasks')->select('grade', 'graded', 'finished')->join('lab_task', 'lab_task.id', '=', 'task_id')->where('user_id', '=', $row->user_id)
                    ->where('lab_task.labid', '=', $row->labid)->get();
                foreach ($grades as $ingrade) {
                    $totalpoints = $totalpoints + $ingrade->grade;
                    $graded = $graded + $ingrade->graded;

                    $counts = $counts + 1;
                }

                    if($graded==0){
                        return 'No grade';
                    }else{
                        return $totalpoints;
                    }


            })
//            ->addColumn('groupname', function ($row) {
//                return $row->labTask()->first()->lab()->first()->group()->first()->name;
//            })
            ->addColumn('action', 'admin.labs.gradingalltaskdatatable-action')
            ->rawColumns(['permissions', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Lab $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(LabTask $model)
    {
//        $lab=Lab::find(614);
//        $tasks=$lab->labTask()->get();
//        foreach ($tasks as $task) {
//            $model = $task->userTask()->get();
//            $result = $result->get()->merge($model);
//        }
//        return $result;
        $result = $model->newQuery()->select('users_tasks.id','users_tasks.grade','lab_task.fullpoints', 'users_tasks.update_time','users_tasks.user_id', 'users_tasks.task_id','users_tasks.group_id', 'users.email','user_profiles.first_name','user_profiles.last_name','lab_task.name','lab_task.labid','users_tasks.graded','users_tasks.finished')
            ->LeftJoin('users_tasks','users_tasks.task_id','=','lab_task.id')
            ->join('users', 'users_tasks.user_id', '=', 'users.id')
            ->join('user_profiles','users.id','=','user_profiles.user_id')
            ->join('labcontent','lab_task.labid','=','labcontent.id')
            ->groupBy('lab_task.labid','users.id','users_tasks.group_id')
            ->where('labcontent.id','=',$this->labid)->where('users_tasks.group_id','=',$this->id);
//         $result = $model->newQuery()->select('users_tasks.id','users_tasks.grade','lab_task.fullpoints', 'users_tasks.user_id', 'users_tasks.task_id','users_tasks.group_id', 'users.email','user_profiles.first_name','user_profiles.last_name','lab_task.name','lab_task.labid','users_tasks.graded','users_tasks.finished')
//             ->with('user')->with('labTask')
//             ->rightJoin('users', 'user_id', '=', 'users.id')
//             ->leftJoin('user_profiles','users.id','=','user_profiles.user_id')
//             ->join('lab_task','task_id','=','lab_task.id')
//             ->join('labcontent','lab_task.labid','=','labcontent.id')
//         ->where('labcontent.id','=',$this->labid)->where('users_tasks.group_id','=',$this->id);

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
//                'pageLength'=>'10',
                "scrollY"=>"400px",
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
            //'user_id'=>[ 'visible' => false],
            //'task_id'=>[ 'visible' => false],
            'labid'=>[ 'data' => 'labid','name' => 'lab_task.labid','visible' => false],
            'first_name'=>[ 'name' => 'user_profiles.first_name','title' => 'First Name'],
            'last_name'=>[ 'name' => 'user_profiles.last_name','title' => 'Last Name'],
            'email'=>[ 'name' => 'users.email'],
            'labname',
            //'taskname'=>['data' => 'name','name' => 'lab_task.name','title'=>'Task Name'],
            //'group_id'=>['visible' => false],
            //'grade'=>['name'=>'users_tasks.grade','title'=>'Current Grade'],
            'fullpoints'=>['name'=>'lab_task.fullpoints','title'=>'Available Points','visible' => false],
            'totalpoints'=>['title'=>'Current Grades'],
            'graded'=>['name'=>'users_tasks.graded','title'=>'Grading Status'],
            'finished'=>['name'=>'users_tasks.finished','title'=>'Submission Status'],
            'update_time'=>['name'=>'users_tasks.update_time','title'=>'Last Activity'],

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
