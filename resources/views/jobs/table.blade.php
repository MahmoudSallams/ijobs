<table class="table table-responsive" id="jobs-table">
    <thead>
        <tr>
            <th>Title</th>
        <th>Description</th>
        <th>Image</th>
        <th>Applied Count</th>
        <th>Forwarded Count</th>
        <th>Shared Count</th>
        <th>User Id</th>
        <th>Group Id</th>
        <th>Parent Id</th>
        <th>Status</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($jobs as $job)
        <tr>
            <td>{!! $job->title !!}</td>
            <td>{!! $job->description !!}</td>
            <td>{!! $job->image !!}</td>
            <td>{!! $job->applied_count !!}</td>
            <td>{!! $job->forwarded_count !!}</td>
            <td>{!! $job->shared_count !!}</td>
            <td>{!! $job->user_id !!}</td>
            <td>{!! $job->group_id !!}</td>
            <td>{!! $job->parent_id !!}</td>
            <td>{!! $job->status !!}</td>
            <td>
                {!! Form::open(['route' => ['jobs.destroy', $job->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('jobs.show', [$job->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('jobs.edit', [$job->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>