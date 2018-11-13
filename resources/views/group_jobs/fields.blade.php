<!-- Group Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('group_id', 'Group Id:') !!}
    {!! Form::number('group_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Job Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('job_id', 'Job Id:') !!}
    {!! Form::number('job_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::number('status', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('groupJobs.index') !!}" class="btn btn-default">Cancel</a>
</div>
