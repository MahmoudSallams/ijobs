<table class="table table-responsive" id="userCompanies-table">
    <thead>
        <tr>
            <th>User Id</th>
        <th>Company Id</th>
        <th>Status</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($userCompanies as $userCompany)
        <tr>
            <td>{!! $userCompany->user_id !!}</td>
            <td>{!! $userCompany->company_id !!}</td>
            <td>{!! $userCompany->status !!}</td>
            <td>
                {!! Form::open(['route' => ['userCompanies.destroy', $userCompany->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('userCompanies.show', [$userCompany->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('userCompanies.edit', [$userCompany->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>