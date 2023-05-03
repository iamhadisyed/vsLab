{{--<a class="btn btn-xs btn-primary" href="/sites/{{$id}}/edit">Edit</a>--}}
{{--<button class="btn btn-xs btn-danger" onclick="Swal('', 'Site {{$name}} cannot be deleted!', 'warning')">Delete</button>--}}
<div class="btn-group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Action <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" style="min-width: 10px;">
        {{--<li><a href="#" onclick="modal_mysite_user_add($(this))">Add</a></li>--}}
        <li><a href="#" onclick="modal_mysite_user_edit($(this))">Edit</a></li>
        <li role="separator" class="divider"></li>
        <li><a href="#" onclick="mysite_user_remove($(this))" style="color:red">Remove</a></li>
    </ul>
</div>