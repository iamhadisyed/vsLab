{{--<button class="btn btn-xs btn-primary" onclick="alert('TODO: Edit user {{$id}}')">Edit</button>--}}
{{--<button class="btn btn-xs btn-primary" id="user-management-edit-button" onclick="user_management_edit($(this))"--}}
        {{--data-userId="{{ $id }}" data-userName="{{ $name }}" data-userRoles="{{ $roles }}">Edit</button>--}}
{{--<button class="btn btn-xs btn-danger" onclick="alert('You cannot delete the user!')">Delete</button>--}}

<div class="btn-group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Action <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" style="min-width: 10px;">
        <li><a href="#" onclick="user_management_edit($(this))">Edit</a></li>
        <li role="separator" class="divider"></li>
        <li><a href="#" onclick="user_management_delete($(this))" style="color:red">Delete</a></li>
    </ul>
</div>