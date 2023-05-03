@if(isset($group->id))
{!! Form::open(["route" => "groups.edit.members","role"=>"form", 'class' => 'form-add-members']) !!}
<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title bariol-thin"><i class="fa fa-pencil"></i> Group Members</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4 col-xs-12">
                {{-- group base form --}}
                <select name="current_members[]" id="membersSel" class="form-control" multiple style="width:400px">
                    {{ $groupOwner = $groupinfo->owner_id }}
                    @foreach($groupmem as $mem)
                        @if($mem->id != $groupOwner)
                        <option value="{{ $mem->id }}">{{ $mem->email }}</option>
                        @endif
                    @endforeach
                </select>

            </div>
            <div class="col-md-4 col-xs-12">
                        <span style="display: inline-block; width: 300px; background: white; vertical-align: top; margin-left: 50px">
                        <p>{!! Form::button('<-', array("class"=>"btn btn-info",'id'=>'left','style'=>'width:160px')) !!}</p>
                            {!! Form::button('->', array("class"=>"btn btn-info",'id'=>'right','style'=>'width:160px')) !!}
                        </span>
            </div>
            <div class="col-md-4 col-xs-12">
                {{-- group permission form --}}

                <select name="all_users[]" id="membersAll" class="form-control" multiple style="width:400px">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->email }}</option>
                    @endforeach
                </select>
                {!! Form::hidden('id', $group->id) !!}
                {!! Form::submit('Update', array("class"=>"btn btn-info","style"=>'margin-top:20px','id'=>'Update')) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endif
@section('footer_scripts')
@parent
    <script>
        $(".delete").click(function(){
            return confirm("Are you sure to delete this item?");
        });

        $('#left').click(function () {
            moveItems('#membersAll', '#membersSel');
        });

        $('#right').on('click', function () {
            moveItems('#membersSel', '#membersAll');
        });

        function moveItems(origin, dest) {
            $(origin).find(':selected').appendTo(dest);
        }

        /*$('#Update').click(function() {
            $('#membersSel option').prop('selected', true);
        });*/

        /*$('#Update').click(function() {
            $('#membersAll option').prop('selected', true);
        });*/
    </script>
@stop