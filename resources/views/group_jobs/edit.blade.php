@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Group Job
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($groupJob, ['route' => ['groupJobs.update', $groupJob->id], 'method' => 'patch']) !!}

                        @include('group_jobs.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection