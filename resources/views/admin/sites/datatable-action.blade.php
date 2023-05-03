{{--<a class="btn btn-xs btn-primary" href="/sites/{{$id}}/edit">Edit</a>--}}
{{--<button class="btn btn-xs btn-danger" onclick="Swal('', 'Site {{$name}} cannot be deleted!', 'warning')">Delete</button>--}}
<div class="btn-group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Action <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" style="min-width: 10px;">
        <li><a href="#" onclick="modal_site_edit($(this))">Edit</a></li>
        {{--<li><a href="#" onclick="group_members($(this))">Members</a></li>--}}
        <li role="separator" class="divider"></li>
        <li><a href="#" onclick="site_delete($(this))" style="color:red">Delete</a></li>
    </ul>
</div>