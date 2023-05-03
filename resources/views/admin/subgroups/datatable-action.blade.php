{{--<button class="btn btn-xs btn-primary" onclick="modal_team_edit($(this), '{{ url('groups/members-json') }}')">Edit</button>--}}
{{--<button class="btn btn-xs btn-danger" onclick="alert('You cannot delete the user!')">Delete</button>--}}
<div class="btn-group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Action <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" style="min-width: 10px;">
        <li><a href="#" onclick="modal_team_edit($(this))">Edit</a></li>
        <li><a href="#" onclick="modal_team_members($(this))">Members</a></li>
        <li role="separator" class="divider"></li>
        <li><a href="#" onclick="delete_teams($(this))" style="color:red">Delete</a></li>
    </ul>
</div>