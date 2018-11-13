<table class="table table-responsive" id="profiles-table">
    <thead>
        <tr>
            <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Mobile</th>
        <th>Mobile Verify Code</th>
        <th>Mobile Verify Status</th>
        <th>Region Id</th>
        <th>Country Id</th>
        <th>City Id</th>
        <th>Gender</th>
        <th>Brief</th>
        <th>Photo</th>
        <th>Status</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($profiles as $profile)
        <tr>
            <td>{!! $profile->first_name !!}</td>
            <td>{!! $profile->last_name !!}</td>
            <td>{!! $profile->email !!}</td>
            <td>{!! $profile->mobile !!}</td>
            <td>{!! $profile->mobile_verify_code !!}</td>
            <td>{!! $profile->mobile_verify_status !!}</td>
            <td>{!! $profile->region_id !!}</td>
            <td>{!! $profile->country_id !!}</td>
            <td>{!! $profile->city_id !!}</td>
            <td>{!! $profile->gender !!}</td>
            <td>{!! $profile->brief !!}</td>
            <td>{!! $profile->photo !!}</td>
            <td>{!! $profile->status !!}</td>
            <td>
                {!! Form::open(['route' => ['profiles.destroy', $profile->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('profiles.show', [$profile->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('profiles.edit', [$profile->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>