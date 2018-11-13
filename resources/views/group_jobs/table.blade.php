<table class="table table-responsive" id="groupJobs-table">
    <thead>
        <tr>
            <th>Group Id</th>
        <th>Job Id</th>
        <th>Status</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($groupJobs as $groupJob)
        <tr>
            <td>{!! $groupJob->group_id !!}</td>
            <td>{!! $groupJob->job_id !!}</td>
            <td>{!! $groupJob->status !!}</td>
            <td>
                {!! Form::open(['route' => ['groupJobs.destroy', $groupJob->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('groupJobs.show', [$groupJob->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('groupJobs.edit', [$groupJob->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>