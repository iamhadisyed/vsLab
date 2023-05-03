<div class="btn-group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Action <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" style="min-width: 10px;">
        <li><a href="#" onclick="startlab($(this))">Start Lab</a></li>
        <li><a href="#" onclick="gotousertimeline($(this))">Show Activity History</a></li>

        @if (isset($teacher))
            <li><a href="#" onclick="gotolabgrading($(this))" style="color:orange">Grading</a></li>
            <li><a href="#" onclick="modal_edit_due_date($(this))" style="color:red">Change Due Date</a></li>
            <li><a href="#" onclick="reopentask($(this))" style="color:red">Reopen All Tasks</a></li>
        @else
            <li><a href="#" onclick="gotolabgrading($(this))">View Grades</a></li>
        @endif
        {{--<li role="separator" class="divider"></li>--}}
        {{--<li><a href="#" onclick="delete_teams($(this))" style="color:red">Delete</a></li>--}}
    </ul>
</div>