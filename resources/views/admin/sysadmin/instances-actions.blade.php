<div class="btn-group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Action <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" style="min-width: 10px;">
        <li><a href="#" onclick="modal_instance_details($(this))">Details</a></li>
        {{--<li><a href="#" onclick="deploy_events($(this))">Status</a></li>--}}
        <li role="separator" class="divider"></li>
        @if (isset($resume))
        <li><a href="#" onclick="instance_actions($(this))" class="vm-resume" style="color:red">Resume</a></li>
        @endif
        @if (isset($start))
            <li><a href="#" onclick="instance_actions($(this))" class="vm-start" style="color:red">Start</a></li>
        @endif
        @if (isset($suspend))
        <li><a href="#" onclick="instance_actions($(this))" class="vm-suspend" style="color:red">Suspend</a></li>
            <li><a href="#" onclick="instance_actions($(this))" class="vm-reboot" style="color:red">Reboot</a></li>
            <li><a href="#" onclick="instance_actions($(this))" class="vm-shutdown" style="color:red">Shutdown</a></li>
        @endif
    </ul>
</div>