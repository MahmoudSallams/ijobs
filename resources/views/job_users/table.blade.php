<table class="table table-responsive" id="jobUsers-table">
    <thead>
        <tr>
            <th>Job Id</th>
        <th>User Id</th>
        <th>Status</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($jobUsers as $jobUser)
        <tr>
            <td>{!! $jobUser->job_id !!}</td>
            <td>{!! $jobUser->user_id !!}</td>
            <td>{!! $jobUser->status !!}</td>
            <td>
                {!! Form::open(['route' => ['jobUsers.destroy', $jobUser->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('jobUsers.show', [$jobUser->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('jobUsers.edit', [$jobUser->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>