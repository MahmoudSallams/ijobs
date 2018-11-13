<table class="table table-responsive" id="groups-table">
    <thead>
        <tr>
            <th>Name</th>
        <th>Image</th>
        <th>Members Counts</th>
        <th>Status</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($groups as $group)
        <tr>
            <td>{!! $group->name !!}</td>
            <td>{!! $group->image !!}</td>
            <td>{!! $group->members_counts !!}</td>
            <td>{!! $group->status !!}</td>
            <td>
                {!! Form::open(['route' => ['groups.destroy', $group->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('groups.show', [$group->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('groups.edit', [$group->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>