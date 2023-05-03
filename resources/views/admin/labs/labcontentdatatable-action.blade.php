{{--<button class="btn btn-xs btn-primary" id="group-management-edit-button" onclick="group_management_edit($(this))"--}}
        {{--data-groupId="{{ $id }}" data-groupName="{{ $name }}" data-groupStatus="{{ $status }}">Edit</button>--}}
{{--<button class="btn btn-xs btn-danger" onclick="alert('You are not allowed to delete a group!')">Delete</button>--}}
<div class="btn-group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Action <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" style="min-width: 10px;">
        <li><a href="#" onclick="showlabeditor($(this))">Edit Lab</a></li>
        <li><a href="#" onclick="previewlab($(this))">Preview Lab</a></li>
        {{--<li><a href="#" onclick="group_members($(this))">Members</a></li>--}}
        <li role="separator" class="divider"></li>
        <li><a href="#" onclick="deletelabcontent($(this))" style="color:red">Delete Lab</a></li>
    </ul>
</div>