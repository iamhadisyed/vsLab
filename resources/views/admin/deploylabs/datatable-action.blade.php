{{--<button class="btn btn-xs btn-primary" onclick="modal_update_labs($(this))">Edit</button>--}}
{{--<button class="btn btn-xs btn-warning" onclick="modal_deploy_labs($(this))">Deploy</button>--}}
{{--<button class="btn btn-xs btn-success" onclick="modal_view_deployed_labs($(this))">view</button>--}}
{{--<button class="btn btn-xs btn-danger" onclick="alert('You cannot delete the user!')">Delete</button>--}}
<div class="btn-group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Action <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" style="min-width: 10px;">
        @if (isset($deploy))
            <li><a href="#" onclick="deploy_labs($(this))">Deploy</a></li>
        @endif
        @if (isset($labview))
        <li><a href="#" onclick="modal_view_deployed_labs($(this))">View</a></li>
        @endif
        <li><a href="#" onclick="modal_update_labs($(this))">Edit</a></li>
        {{--<li><a href="#" onclick="deploy_events($(this))">Status</a></li>--}}
        <li role="separator" class="divider"></li>
        @if (isset($release))
        <li><a href="#" onclick="release_labs($(this))" style="color:red">Release</a></li>
        @endif
        @if (isset($delete))
        <li><a href="#" onclick="delete_labs($(this))" style="color:red">Delete</a></li>
        @endif
    </ul>
</div>