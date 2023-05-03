{{--<button class="btn btn-xs btn-primary" onclick="window.location = '/labenbdesign/{!! $id !!}';">Edit Lab Env</button>--}}
{{--<button class="btn btn-xs btn-primary" onclick="deploytestenv({!! $id !!});">Deploy Test Env</button>--}}
{{--<button style="display: none;" class="btn btn-xs btn-primary" onclick="window.location = '/labenbdesign/env/{!! $id !!}';">View Test Env</button>--}}
{{--<button  style="display: none;" class="btn btn-xs btn-primary" onclick="deletetestenv({!! $id !!});">Delete Test Env</button>--}}
<div class="btn-group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Action <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" style="min-width: 10px;">
        <li><a href="#" onclick="deploy_lab_in_labenv($(this));">Deploy Test Env</a></li>
        <li><a href="#" onclick="edit_lab_env($(this));">Edit</a></li>
        <li><a href="#" onclick="modal_view_deployed_env($(this))">View Test Env</a></li>
        <li role="separator" class="divider"></li>
        <li><a href="#" onclick="release_test_env($(this))" style="color:red">Release Resource</a></li>
        <li><a href="#" onclick="delete_test_env($(this))" style="color:red">Delete Template</a></li>
    </ul>
</div>