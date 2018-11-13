<table class="table table-responsive" id="groupUsers-table">
    <thead>
        <tr>
            <th>Group Id</th>
        <th>User Id</th>
        <th>Status</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($groupUsers as $groupUser)
        <tr>
            <td>{!! $groupUser->group_id !!}</td>
            <td>{!! $groupUser->user_id !!}</td>
            <td>{!! $groupUser->status !!}</td>
            <td>
                {!! Form::open(['route' => ['groupUsers.destroy', $groupUser->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('groupUsers.show', [$groupUser->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('groupUsers.edit', [$groupUser->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>