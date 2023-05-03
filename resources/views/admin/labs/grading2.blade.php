@extends('adminlte::layouts.app')

@section('htmlheader_title')
    @if ($role === 0)
        Grading Lab Reports
    @elseif($role === 1)
        View Grading
    @endif

    {{--{{ trans('message.role') }}--}}
@endsection

@section('contentheader_title')
    @if ($role === 0)
        Grading Lab Reports
    @elseif($role === 1)
        View Grading
    @endif
@endsection

@section('contentheader_description')

    @if ($role === 0)
        Avaiable Labs for grading
    @elseif($role === 1)
        Please selece a Lab
    @endif
@endsection

@section('main-content')
{{--<table style="width: 100%; border: 1px solid lightblue;">--}}
    {{--<tr style="width: 100%; border: 1px solid lightblue;">--}}
        {{--<td >--}}
            {{--Row 1; Col 1;--}}
        {{--</td>--}}
        {{--<td>--}}
            {{--Row 1; Col 2;--}}
        {{--</td>--}}
    {{--</tr>--}}
    {{--<tr>--}}
        {{--<td>--}}
            {{--Row 2; Col 1;--}}
        {{--</td>--}}
        {{--<td>--}}
            {{--Row 2; Col 2;--}}
        {{--</td>--}}
    {{--</tr>--}}
{{--</table>--}}
<div id="upperpage">
    <section class="col-md-12 container-fluid">
        <div class="box-header ui-sortable-handle" >






        </div>
        <!-- /.box-header -->

        <div class="box-body">

            <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
            <div class="panel panel-default">
                <div class="panel-body table-responsive">
                    Class:
                    <select id="classselector" name="classselector">
                        <option value="0">... Select a group ...</option>
                        @foreach($groups->all() as $group)
                            <option value={{ $group->id }} >
                                {{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                    {{--<select id="classselector" name="classselector" >--}}
                        {{--<option>Please select...</option>--}}
                        {{--<option>Fall2018CSE468</option>--}}


                    {{--</select>--}}
                    Lab:

                    <select id="labselector" name="labselector" >
                        <option value="0">... Select a lab ...</option>
                        @foreach($labs->all() as $lab)
                            <option value={{ $lab->id }} >
                                {{ $lab->name }}
                            </option>
                        @endforeach
                    </select>
                    {{--<input type="checkbox" id="enabletask" name="enabletask" value="enabletask" > Enable task selection--}}
                    Task:
                    <select id="taskselector" name="taskselector" >
                    <option value="0">... Select a task ...</option>
                    @foreach($tasks->all() as $task)
                        <option value={{ $task->id }} >
                            {{ $task->name }}
                        </option>
                    @endforeach
                    </select>
                    <br/>
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </section>
</div>
<div id="lowerpage">
    <section class="col-lg-9 connectedSortable ui-sortable">
        <div class="box box-primary" style="display:none; position: relative; left: 0px; top: 0px;" id="submissionbox0">
            <div class="box-header ui-sortable-handle" >
            </div>
            <div class="box-body" id="tobegrading0">
            </div>
        </div>
    </section>
    <section class="col-lg-3 connectedSortable ui-sortable">
        <div  id="gradingbox0" style="display:none;" class="box box-primary" style="position: relative; left: 0px; top: 0px;">
            <div class="box-header ui-sortable-handle" >
                <h3 class="box-title" id="tasktitle0">Submission Grading for Task</h3>

                <h4><a id="taskname0"></a></h4>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if ($role === 0)
                    <h4>Username: <a id="gradingusername0"></a></h4>
                @endif
                <a id="gradingtaskid0" style="display: none"></a>
                <a id="gradinguserid0" style="display: none"></a>
                <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                <h4>Task Grade:</h4><input style="width: 100px" type="text" id="givenpoints0" maxlength="3"/> out of <a id="totalpointoftask0"></a>
                <h4>Feedback:</h4>
                <textarea spellcheck="true" style="max-width: 100%;width : 100%" id="taskgradingfeedback0"  rows="2"></textarea>

            </div>
            @if ($role === 0)
                <div class="box-footer ">
                    <button class="btn btn-xs btn-primary pull-right" onclick="submitgrade(0)">Save</button>
                </div>
            @endif
        </div>
    </section>


    <section class="col-lg-9 connectedSortable ui-sortable">
        <div class="box box-primary" style="display:none; position: relative; left: 0px; top: 0px;" id="submissionbox1">
            <div class="box-header ui-sortable-handle" >
            </div>
            <div class="box-body" id="tobegrading1">
            </div>
        </div>
    </section>
    <section class="col-lg-3 connectedSortable ui-sortable">
        <div  id="gradingbox1" style="display:none;" class="box box-primary" style="position: relative; left: 0px; top: 0px;">
            <div class="box-header ui-sortable-handle" >
                <h3 class="box-title" id="tasktitle1">Submission Grading for Task</h3>

                <h4><a id="taskname1"></a></h4>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if ($role === 0)
                    <h4>Username: <a id="gradingusername1"></a></h4>
                @endif
                <a id="gradingtaskid1" style="display: none"></a>
                <a id="gradinguserid1" style="display: none"></a>
                <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                <h4>Task Grade:</h4><input style="width: 100px" type="text" id="givenpoints1" maxlength="3"/> out of <a id="totalpointoftask1"></a>
                <h4>Feedback:</h4>
                <textarea spellcheck="true" style="max-width: 100%;width : 100%" id="taskgradingfeedback1"  rows="2"></textarea>

            </div>
            @if ($role === 0)
                <div class="box-footer ">
                    <button class="btn btn-xs btn-primary pull-right" onclick="submitgrade(1)">Save</button>
                </div>
            @endif
        </div>
    </section>

    <section class="col-lg-9 connectedSortable ui-sortable">
        <div class="box box-primary" style="display:none; position: relative; left: 0px; top: 0px;" id="submissionbox2">
            <div class="box-header ui-sortable-handle" >
            </div>
            <div class="box-body" id="tobegrading2">
            </div>
        </div>
    </section>
    <section class="col-lg-3 connectedSortable ui-sortable">
        <div  id="gradingbox2" style="display:none;" class="box box-primary" style="position: relative; left: 0px; top: 0px;">
            <div class="box-header ui-sortable-handle" >
                <h3 class="box-title" id="tasktitle2">Submission Grading for Task</h3>
                <h4><a id="taskname2"></a></h4>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if ($role === 0)
                    <h4>Username: <a id="gradingusername2"></a></h4>
                @endif
                <a id="gradingtaskid2" style="display: none"></a>
                <a id="gradinguserid2" style="display: none"></a>
                <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                <h4>Task Grade:</h4><input style="width: 100px" type="text" id="givenpoints2" maxlength="3"/> out of <a id="totalpointoftask2"></a>
                <h4>Feedback:</h4>
                <textarea spellcheck="true" style="max-width: 100%;width : 100%" id="taskgradingfeedback2"  rows="2"></textarea>

            </div>
            @if ($role === 0)
                <div class="box-footer ">
                    <button class="btn btn-xs btn-primary pull-right" onclick="submitgrade(2)">Save</button>
                </div>
            @endif
        </div>
    </section>

    <section class="col-lg-9 connectedSortable ui-sortable">
        <div class="box box-primary" style="display:none; position: relative; left: 0px; top: 0px;" id="submissionbox3">
            <div class="box-header ui-sortable-handle" >
            </div>
            <div class="box-body" id="tobegrading3">
            </div>
        </div>
    </section>
    <section class="col-lg-3 connectedSortable ui-sortable">
        <div  id="gradingbox3" style="display:none;" class="box box-primary" style="position: relative; left: 0px; top: 0px;">
            <div class="box-header ui-sortable-handle" >
                <h3 class="box-title" id="tasktitle3">Submission Grading for Task</h3>
                <h4><a id="taskname3"></a></h4>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if ($role === 0)
                    <h4>Username: <a id="gradingusername3"></a></h4>
                @endif
                <a id="gradingtaskid3" style="display: none"></a>
                <a id="gradinguserid3" style="display: none"></a>
                <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                <h4>Task Grade:</h4><input style="width: 100px" type="text" id="givenpoints3" maxlength="3"/> out of <a id="totalpointoftask3"></a>
                <h4>Feedback:</h4>
                <textarea spellcheck="true" style="max-width: 100%;width : 100%" id="taskgradingfeedback3"  rows="2"></textarea>

            </div>
            @if ($role === 0)
                <div class="box-footer ">
                    <button class="btn btn-xs btn-primary pull-right" onclick="submitgrade(3)">Save</button>
                </div>
            @endif
        </div>
    </section>

    <section class="col-lg-9 connectedSortable ui-sortable">
        <div class="box box-primary" style="display:none; position: relative; left: 0px; top: 0px;" id="submissionbox4">
            <div class="box-header ui-sortable-handle" >
            </div>
            <div class="box-body" id="tobegrading4">
            </div>
        </div>
    </section>
    <section class="col-lg-3 connectedSortable ui-sortable">
        <div  id="gradingbox4" style="display:none;" class="box box-primary" style="position: relative; left: 0px; top: 0px;">
            <div class="box-header ui-sortable-handle" >
                <h3 class="box-title" id="tasktitle4">Submission Grading for Task</h3>
                <h4><a id="taskname4"></a></h4>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if ($role === 0)
                    <h4>Username: <a id="gradingusername3"></a></h4>
                @endif
                <a id="gradingtaskid4" style="display: none"></a>
                <a id="gradinguserid4" style="display: none"></a>
                <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                <h4>Task Grade:</h4><input style="width: 100px" type="text" id="givenpoints4" maxlength="3"/> out of <a id="totalpointoftask4"></a>
                <h4>Feedback:</h4>
                <textarea spellcheck="true" style="max-width: 100%;width : 100%" id="taskgradingfeedback4"  rows="2"></textarea>

            </div>
            @if ($role === 0)
                <div class="box-footer ">
                    <button class="btn btn-xs btn-primary pull-right" onclick="submitgrade(4)">Save</button>
                </div>
            @endif
        </div>
    </section>

    <section class="col-lg-9 connectedSortable ui-sortable">
        <div class="box box-primary" style="display:none; position: relative; left: 0px; top: 0px;" id="submissionbox5">
            <div class="box-header ui-sortable-handle" >
            </div>
            <div class="box-body" id="tobegrading5">
            </div>
        </div>
    </section>
    <section class="col-lg-3 connectedSortable ui-sortable">
        <div  id="gradingbox5" style="display:none;" class="box box-primary" style="position: relative; left: 0px; top: 0px;">
            <div class="box-header ui-sortable-handle" >
                <h3 class="box-title" id="tasktitle5">Submission Grading for Task</h3>
                <h4><a id="taskname5"></a></h4>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if ($role === 0)
                    <h4>Username: <a id="gradingusername5"></a></h4>
                @endif
                <a id="gradingtaskid5" style="display: none"></a>
                <a id="gradinguserid5" style="display: none"></a>
                <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                <h4>Task Grade:</h4><input style="width: 100px" type="text" id="givenpoints5" maxlength="3"/> out of <a id="totalpointoftask5"></a>
                <h4>Feedback:</h4>
                <textarea spellcheck="true" style="max-width: 100%;width : 100%" id="taskgradingfeedback5"  rows="2"></textarea>

            </div>
            @if ($role === 0)
                <div class="box-footer ">
                    <button class="btn btn-xs btn-primary pull-right" onclick="submitgrade(5)">Save</button>
                </div>
            @endif
        </div>
    </section>

    <section class="col-lg-9 connectedSortable ui-sortable">
        <div class="box box-primary" style="display:none; position: relative; left: 0px; top: 0px;" id="submissionbox6">
            <div class="box-header ui-sortable-handle" >
            </div>
            <div class="box-body" id="tobegrading6">
            </div>
        </div>
    </section>
    <section class="col-lg-3 connectedSortable ui-sortable">
        <div  id="gradingbox6" style="display:none;" class="box box-primary" style="position: relative; left: 0px; top: 0px;">
            <div class="box-header ui-sortable-handle" >
                <h3 class="box-title" id="tasktitle6">Submission Grading for Task</h3>
                <h4><a id="taskname6"></a></h4>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if ($role === 0)
                    <h4>Username: <a id="gradingusername6"></a></h4>
                @endif
                <a id="gradingtaskid6" style="display: none"></a>
                <a id="gradinguserid6" style="display: none"></a>
                <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                <h4>Task Grade:</h4><input style="width: 100px" type="text" id="givenpoints6" maxlength="3"/> out of <a id="totalpointoftask6"></a>
                <h4>Feedback:</h4>
                <textarea spellcheck="true" style="max-width: 100%;width : 100%" id="taskgradingfeedback6"  rows="2"></textarea>

            </div>
            @if ($role === 0)
                <div class="box-footer ">
                    <button class="btn btn-xs btn-primary pull-right" onclick="submitgrade(6)">Save</button>
                </div>
            @endif
        </div>
    </section>

    <section class="col-lg-9 connectedSortable ui-sortable">
        <div class="box box-primary" style="display:none; position: relative; left: 0px; top: 0px;" id="submissionbox7">
            <div class="box-header ui-sortable-handle" >
            </div>
            <div class="box-body" id="tobegrading7">
            </div>
        </div>
    </section>
    <section class="col-lg-3 connectedSortable ui-sortable">
        <div  id="gradingbox7" style="display:none;" class="box box-primary" style="position: relative; left: 0px; top: 0px;">
            <div class="box-header ui-sortable-handle" >
                <h3 class="box-title" id="tasktitle7">Submission Grading for Task</h3>
                <h4><a id="taskname7"></a></h4>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if ($role === 0)
                    <h4>Username: <a id="gradingusername7"></a></h4>
                @endif
                <a id="gradingtaskid7" style="display: none"></a>
                <a id="gradinguserid7" style="display: none"></a>
                <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                <h4>Task Grade:</h4><input style="width: 100px" type="text" id="givenpoints7" maxlength="3"/> out of <a id="totalpointoftask7"></a>
                <h4>Feedback:</h4>
                <textarea spellcheck="true" style="max-width: 100%;width : 100%" id="taskgradingfeedback7"  rows="2"></textarea>

            </div>
            @if ($role === 0)
                <div class="box-footer ">
                    <button class="btn btn-xs btn-primary pull-right" onclick="submitgrade(7)">Save</button>
                </div>
            @endif
        </div>
    </section>

    <section class="col-lg-9 connectedSortable ui-sortable">
        <div class="box box-primary" style="display:none; position: relative; left: 0px; top: 0px;" id="submissionbox8">
            <div class="box-header ui-sortable-handle" >
            </div>
            <div class="box-body" id="tobegrading8">
            </div>
        </div>
    </section>
    <section class="col-lg-3 connectedSortable ui-sortable">
        <div  id="gradingbox8" style="display:none;" class="box box-primary" style="position: relative; left: 0px; top: 0px;">
            <div class="box-header ui-sortable-handle" >
                <h3 class="box-title" id="tasktitle8">Submission Grading for Task</h3>
                <h4><a id="taskname8"></a></h4>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if ($role === 0)
                    <h4>Username: <a id="gradingusername8"></a></h4>
                @endif
                <a id="gradingtaskid8" style="display: none"></a>
                <a id="gradinguserid8" style="display: none"></a>
                <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                <h4>Task Grade:</h4><input style="width: 100px" type="text" id="givenpoints8" maxlength="3"/> out of <a id="totalpointoftask8"></a>
                <h4>Feedback:</h4>
                <textarea spellcheck="true" style="max-width: 100%;width : 100%" id="taskgradingfeedback8"  rows="2"></textarea>

            </div>
            @if ($role === 0)
                <div class="box-footer ">
                    <button class="btn btn-xs btn-primary pull-right" onclick="submitgrade(8)">Save</button>
                </div>
            @endif
        </div>
    </section>

    <section class="col-lg-9 connectedSortable ui-sortable">
        <div class="box box-primary" style="display:none; position: relative; left: 0px; top: 0px;" id="submissionbox9">
            <div class="box-header ui-sortable-handle" >
            </div>
            <div class="box-body" id="tobegrading9">
            </div>
        </div>
    </section>
    <section class="col-lg-3 connectedSortable ui-sortable">
        <div  id="gradingbox9" style="display:none;" class="box box-primary" style="position: relative; left: 0px; top: 0px;">
            <div class="box-header ui-sortable-handle" >
                <h3 class="box-title" id="tasktitle9">Submission Grading for Task</h3>
                <h4><a id="taskname9"></a></h4>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if ($role === 0)
                    <h4>Username: <a id="gradingusername9"></a></h4>
                @endif
                <a id="gradingtaskid9" style="display: none"></a>
                <a id="gradinguserid9" style="display: none"></a>
                <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                <h4>Task Grade:</h4><input style="width: 100px" type="text" id="givenpoints9" maxlength="3"/> out of <a id="totalpointoftask9"></a>
                <h4>Feedback:</h4>
                <textarea spellcheck="true" style="max-width: 100%;width : 100%" id="taskgradingfeedback9"  rows="2"></textarea>

            </div>
            @if ($role === 0)
                <div class="box-footer ">
                    <button class="btn btn-xs btn-primary pull-right" onclick="submitgrade(9)">Save</button>
                </div>
            @endif
        </div>
    </section>

    <section class="col-lg-9 connectedSortable ui-sortable">
        <div class="box box-primary" style="display:none; position: relative; left: 0px; top: 0px;" id="submissionbox10">
            <div class="box-header ui-sortable-handle" >
            </div>
            <div class="box-body" id="tobegrading10">
            </div>
        </div>
    </section>
    <section class="col-lg-3 connectedSortable ui-sortable">
        <div  id="gradingbox10" style="display:none;" class="box box-primary" style="position: relative; left: 0px; top: 0px;">
            <div class="box-header ui-sortable-handle" >
                <h3 class="box-title" id="tasktitle10">Submission Grading for Task</h3>
                <h4><a id="taskname10"></a></h4>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if ($role === 0)
                    <h4>Username: <a id="gradingusername10"></a></h4>
                @endif
                <a id="gradingtaskid10" style="display: none"></a>
                <a id="gradinguserid10" style="display: none"></a>
                <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                <h4>Task Grade:</h4><input style="width: 100px" type="text" id="givenpoints10" maxlength="10"/> out of <a id="totalpointoftask10"></a>
                <h4>Feedback:</h4>
                <textarea spellcheck="true" style="max-width: 100%;width : 100%" id="taskgradingfeedback10"  rows="2"></textarea>

            </div>
            @if ($role === 0)
                <div class="box-footer ">
                    <button class="btn btn-xs btn-primary pull-right" onclick="submitgrade(10)">Save</button>
                </div>
            @endif
        </div>
    </section>

    <section class="col-lg-9 connectedSortable ui-sortable">
        <div class="box box-primary" style="display:none; position: relative; left: 0px; top: 0px;" id="submissionbox11">
            <div class="box-header ui-sortable-handle" >
            </div>
            <div class="box-body" id="tobegrading11">
            </div>
        </div>
    </section>
    <section class="col-lg-3 connectedSortable ui-sortable">
        <div  id="gradingbox11" style="display:none;" class="box box-primary" style="position: relative; left: 0px; top: 0px;">
            <div class="box-header ui-sortable-handle" >
                <h3 class="box-title" id="tasktitle11">Submission Grading for Task</h3>
                <h4><a id="taskname11"></a></h4>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if ($role === 0)
                    <h4>Username: <a id="gradingusername11"></a></h4>
                @endif
                <a id="gradingtaskid11" style="display: none"></a>
                <a id="gradinguserid11" style="display: none"></a>
                <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                <h4>Task Grade:</h4><input style="width: 100px" type="text" id="givenpoints11" maxlength="3"/> out of <a id="totalpointoftask11"></a>
                <h4>Feedback:</h4>
                <textarea spellcheck="true" style="max-width: 100%;width : 100%" id="taskgradingfeedback11"  rows="2"></textarea>

            </div>
            @if ($role === 0)
                <div class="box-footer ">
                    <button class="btn btn-xs btn-primary pull-right" onclick="submitgrade(11)">Save</button>
                </div>
            @endif
        </div>
    </section>

    <section class="col-lg-9 connectedSortable ui-sortable">
        <div class="box box-primary" style="display:none; position: relative; left: 0px; top: 0px;" id="submissionbox12">
            <div class="box-header ui-sortable-handle" >
            </div>
            <div class="box-body" id="tobegrading12">
            </div>
        </div>
    </section>
    <section class="col-lg-3 connectedSortable ui-sortable">
        <div  id="gradingbox12" style="display:none;" class="box box-primary" style="position: relative; left: 0px; top: 0px;">
            <div class="box-header ui-sortable-handle" >
                <h3 class="box-title" id="tasktitle12">Submission Grading for Task</h3>
                <h4><a id="taskname12"></a></h4>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if ($role === 0)
                    <h4>Username: <a id="gradingusername12"></a></h4>
                @endif
                <a id="gradingtaskid12" style="display: none"></a>
                <a id="gradinguserid12" style="display: none"></a>
                <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                <h4>Task Grade:</h4><input style="width: 100px" type="text" id="givenpoints12" maxlength="3"/> out of <a id="totalpointoftask12"></a>
                <h4>Feedback:</h4>
                <textarea spellcheck="true" style="max-width: 100%;width : 100%" id="taskgradingfeedback12"  rows="2"></textarea>

            </div>
            @if ($role === 0)
                <div class="box-footer ">
                    <button class="btn btn-xs btn-primary pull-right" onclick="submitgrade(12)">Save</button>
                </div>
            @endif
        </div>
    </section>

    <section class="col-lg-9 connectedSortable ui-sortable">
        <div class="box box-primary" style="display:none; position: relative; left: 0px; top: 0px;" id="submissionbox13">
            <div class="box-header ui-sortable-handle" >
            </div>
            <div class="box-body" id="tobegrading13">
            </div>
        </div>
    </section>
    <section class="col-lg-3 connectedSortable ui-sortable">
        <div  id="gradingbox13" style="display:none;" class="box box-primary" style="position: relative; left: 0px; top: 0px;">
            <div class="box-header ui-sortable-handle" >
                <h3 class="box-title" id="tasktitle13">Submission Grading for Task</h3>
                <h4><a id="taskname13"></a></h4>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if ($role === 0)
                    <h4>Username: <a id="gradingusername13"></a></h4>
                @endif
                <a id="gradingtaskid13" style="display: none"></a>
                <a id="gradinguserid13" style="display: none"></a>
                <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                <h4>Task Grade:</h4><input style="width: 100px" type="text" id="givenpoints13" maxlength="3"/> out of <a id="totalpointoftask13"></a>
                <h4>Feedback:</h4>
                <textarea spellcheck="true" style="max-width: 100%;width : 100%" id="taskgradingfeedback13"  rows="2"></textarea>

            </div>
            @if ($role === 0)
                <div class="box-footer ">
                    <button class="btn btn-xs btn-primary pull-right" onclick="submitgrade(13)">Save</button>
                </div>
            @endif
        </div>
    </section>

    <section class="col-lg-9 connectedSortable ui-sortable">
        <div class="box box-primary" style="display:none; position: relative; left: 0px; top: 0px;" id="submissionbox14">
            <div class="box-header ui-sortable-handle" >
            </div>
            <div class="box-body" id="tobegrading14">
            </div>
        </div>
    </section>
    <section class="col-lg-3 connectedSortable ui-sortable">
        <div  id="gradingbox14" style="display:none;" class="box box-primary" style="position: relative; left: 0px; top: 0px;">
            <div class="box-header ui-sortable-handle" >
                <h3 class="box-title" id="tasktitle14">Submission Grading for Task</h3>
                <h4><a id="taskname14"></a></h4>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if ($role === 0)
                    <h4>Username: <a id="gradingusername14"></a></h4>
                @endif
                <a id="gradingtaskid14" style="display: none"></a>
                <a id="gradinguserid14" style="display: none"></a>
                <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                <h4>Task Grade:</h4><input style="width: 100px" type="text" id="givenpoints14" maxlength="3"/> out of <a id="totalpointoftask14"></a>
                <h4>Feedback:</h4>
                <textarea spellcheck="true" style="max-width: 100%;width : 100%" id="taskgradingfeedback14"  rows="2"></textarea>

            </div>
            @if ($role === 0)
                <div class="box-footer ">
                    <button class="btn btn-xs btn-primary pull-right" onclick="submitgrade(14)">Save</button>
                </div>
            @endif
        </div>
    </section>

</div>
    @if ($role === 0)
        <script src="{{ URL::asset('js/labgrading.js') }}"></script>
    @elseif($role === 1)
        <script src="{{ URL::asset('js/viewgrading.js') }}"></script>
    @endif
{{--<script src="{{ URL::asset('js/vis-canvas-utility.js') }}"></script>--}}
{{--<script src="{{ URL::asset('js/icon-img-assets.js') }}"></script>--}}
{{--<script src="{{ URL::asset('js/vis-canvas-network-topology.js') }}"></script>--}}
{{--<script src="{{ URL::asset('packages/vis/dist/vis.min.js') }}"></script>--}}
<script type="text/javascript" nonce="4AEemasdGb0xJptoIGFP3Nd">
    $(document).ready(function(){
        $('#classselector').val( '{{ $id }}' );
        $('#labselector').val( '{{ $labid }}' );
        if('{{ $id }}'=='0'){
            $('.dataTables_empty').html('Please Select one Class and one Lab First.')
        }else if('{{ $labid }}'=='0'){
            $('.dataTables_empty').html('Please Select one Lab First.')
        }

        $('#classselector').change(function() {
            window.location = '/grade/' + $('#classselector').val()+'/0';
        });
        $('#labselector').change(function() {
            window.location = '/grade/' + $('#classselector').val()+'/'+$('#labselector').val();
        });
//        $('#dataTableBuilder').DataTable().columns(8).search($('#classselector').val()).draw();
        @if ($role === 0)
            $( ".dt-buttons.btn-group" ).prepend( "<button class=\"btn  btn-default\" onclick=\"gradingpolicy({{ $tasks }},{{ $id }},{{ $labid }})\">Grading Policy</button>&nbsp" );
            $(".buttons-csv").html('Export Grades to CSV File');
        @elseif($role === 1)
            $(".buttons-csv").remove();
             document.getElementById('givenpoints').readOnly = true;
             document.getElementById('taskgradingfeedback').readOnly = true;
        @endif

//        var labs = {
//            'Fall2018CSE468': ['','Iptables Firewall Setup','Secure Web Service','Penetration Test and Vulnerability Exploration'],
//
//        };
//        var tasks = {
//            'Iptables Firewall Setup': ['','task1','task2','task3'],
//            'Secure Web Service': ['','task1','task2'],
//            'Penetration Test and Vulnerability Exploration':['','task1','task2']
//        };


//        $('#classselector').change(function () {
//
//            $('#dataTableBuilder').DataTable().columns(8).search($('#classselector').val()).draw();
//
//            var classselector = $(this).val(), lcns = labs[classselector] || [];
//
//            var html = $.map(lcns, function(lcn){
//                return '<option value="' + lcn + '">' + lcn + '</option>'
//            }).join('');
//            $('#labselector').html(html)
//        });
//        $('#labselector').change(function () {
//            $('#dataTableBuilder').DataTable().columns(8).search($('#classselector').val()).draw();
//            $('#dataTableBuilder').DataTable().columns(6).search($('#labselector').val()).draw();
//            var labselector = $(this).val(), lcns = tasks[labselector] || [];
//
//            var html = $.map(lcns, function(lcn){
//                return '<option value="' + lcn + '">' + lcn + '</option>'
//            }).join('');
//            $('#taskselector').html(html)
//        });
        $('#taskselector').change(function () {
            var url = window.location.href;
            if($('#taskselector option:selected')[0].value==0){

            }else if($('#taskselector option:selected')[0].value==5){
                if(url.substring(window.location.href.lastIndexOf('/') + 1)=='all'){

                }else{
                    window.location.href = url+'/all';
                }
            }else if($('#taskselector option:selected')[0].value==6){

                window.location.href = './';

            }else{
                $('#dataTableBuilder').DataTable().columns(8).search($('#taskselector option:selected')[0].text).draw();
            }
//            $('#dataTableBuilder').DataTable().columns(8).search($('#classselector').val()).draw();
//            $('#dataTableBuilder').DataTable().columns(6).search($('#labselector').val()).draw();


        });



    });

</script>
@endsection

@push('dataTable-scripts')
    {!! $dataTable->scripts() !!}
@endpush

@section('javascript')
    <script>
        {{--window.route_mass_crud_entries_destroy = '{{ route('admin.roles.mass_destroy') }}';--}}
    </script>
@endsection