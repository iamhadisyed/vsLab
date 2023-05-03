<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/2/18
 * Time: 3:22 PM
 */

namespace App\DataTables;

use App\UserProfile, App\Subgroup;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SubgroupsDataTable extends DataTable
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
            ->addColumn('check', function($row){
                return '<input type="checkbox" name="ids[]" class="group-checkable" value="' . $row->id . '" data-name="' . $row->name . '" style="margin-left:8px">';
            })
            ->addColumn('members', function($row){
                $users = Subgroup::find($row->id)->users()->get();
                return str_replace(",", "<br>", str_replace(array('[',']','"'),'', $users->pluck('email')));
            })
            ->addColumn('members_ids', function($row){
                $users = Subgroup::find($row->id)->users()->get();
                return str_replace(",", ",", str_replace(array('[',']','"'),'', $users->pluck('id')));
            })
            ->addColumn('labs', function($row){
                $labs = Subgroup::find($row->id)->deploylabs()->select('labenv.name')
                                ->join('labenv', 'labenv.id', '=', 'deploylabs.lab_id')->get();
                return (count($labs) > 0) ? str_replace(",", "<br>", str_replace(array('[',']','"'),'', $labs->pluck('name'))) : '';
            })
            ->editColumn('created_at', function($row) {
                return Carbon::parse($row->created_at)->setTimezone($this->timezone)->toDateTimeString();
            })
            ->editColumn('updated_at', function($row) {
                return Carbon::parse($row->updated_at)->setTimezone($this->timezone)->toDateTimeString();
            })
            ->addColumn('action', 'admin.subgroups.datatable-action')
            ->rawColumns(['check', 'members', 'labs', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Subgroup $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Subgroup $model)
    {
        return $model->newQuery()->select('id', 'name', 'description', 'created_at', 'updated_at')
            ->where('group_id', '=', $this->id);
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
                'lengthMenu' => [ [5, 10, 20, 30, 40, 50, -1], [5, 10, 20, 30, 40, 50, 'All'] ],
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
            'id' => ['visible' => false],
            'check' => ['name' => 'check', 'data' => 'check', 'style' => 'width:20px', 'orderable' => false,
                 'searchable' => false, 'printable' => false, 'exportable' => false, 'class' => 'checkall' ],
            'name',
            'description',
            'members',
            'members_ids' => ['visible' => false],
            'labs' => ['title' => 'Lab Environments'],
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
        return 'Subgroups_' . date('YmdHis');
    }
}